jQuery(document).ready(function($){



	if($('#datepicker1').length || $('#datepicker2').length){

	     var dateFormat = "mm-dd-yyyy",

	     today = new Date(),

	      from = $( "#datepicker1" )

	        .datepicker({

	          minDate : today,

	          changeMonth: true,

	          autoclose : true,

	          startDate: today

	        })

	        .on( "changeDate", function() {

	          $('body').append('<div class="loading" style="position:fixed;top:0;left:0;z-index:999;width:100%;height:100%;color:#fff;background:rgba(0,0,0,0.6);text-align:center;"><strong style="position:relative;top:50%;transform:translateY(-50%);font-size:40px;letter-spacing:1px;">Updating Rental Rate....</strong></div>');

	          $('.rental-rate').submit();

	          $( "#datepicker2" ).datepicker('setStartDate', getDate( this, true ));

	          $( "#datepicker2" ).datepicker('update', getDate( this, true ));

	        }),

	      to = $( "#datepicker2" ).datepicker({

	        // minDate: "+1w",

	        changeMonth: true,

	        autoclose: true,

	        //startDate: document.getElementById("datepicker1").value

	      })

	      .on( "changeDate", function() {

	      	$('body').append('<div class="loading" style="position:fixed;top:0;left:0;z-index:999;width:100%;height:100%;color:#fff;background:rgba(0,0,0,0.6);text-align:center;"><strong style="position:relative;top:50%;transform:translateY(-50%);font-size:40px;letter-spacing:1px;">Updating Rental Rate....</strong></div>');

	        $('.rental-rate').submit();

	        // from[0].datepicker("setEndDate", getDate( this, false ) );

	      });

	 

	    function getDate( element, isadded ) {

	      var date;

	      try {

	        date = $.datepicker.parseDate( dateFormat, element.value );

	      } catch( error ) {

	        date = null;

	      }

	 		



	 		date = element.value;

	 		var myDate = new Date(date);

	 		if(isadded == true){

	 			myDate.setDate(myDate.getDate() + 7);

	 		}

	      return myDate;

	    }

	    $( "#datepicker2" ).datepicker('setStartDate', getDate( document.getElementById("datepicker1"), false ));

	    $( "#datepicker2" ).datepicker('update', getDate( document.getElementById("datepicker2"), false ));

	 }

    

	//open search form

	$('.cd-search-trigger').on('click', function(event){

		event.preventDefault();

		toggleSearch();

		//closeNav();

	});



	$('#myCarousel').carousel({

        interval:10000,

        pause: null

    });



	var $carousel = $('#myCarousel');

	$carousel.bind('slide.bs.carousel', function (e) {

		if(e.relatedTarget !== undefined){

		    if(e.relatedTarget.dataset['type'] == 'animation'){

		    	var ids = e.relatedTarget.id;

		    	var videoid = $(e.relatedTarget).find('video').attr('id');

		    	var cPlayer = videojs(videoid);

		    	cPlayer.ready(function() {

				  cPlayer.play();

				  setTimeout(function(){

					  cPlayer.pause();

					}, 4500);

				});

		  //   	var cur = "mep_"+ ids;

		  //   	for (var player in mejs.players) {

		  //   		if(cur == player){

				// 		mejs.players[player].play();

		  //   		}

				// }

		    }else{

		  //   	for (var player in mejs.players) {

		  //   		if(mejs.players[player].media.paused){

		  //   			mejs.players[player].pause();

		  //   		}

				// }

		    }

		}

	});

	$(".p_bundles_item").off().on("click", function(e){

		$('#p_preview_b').modal('show');

		var product_number = $(this).data('id');

		if(product_number){

			var url = "//s3.amazonaws.com/groshdigital/watermark/" + product_number +".jpg";

			$('#p_preview_b #img_p_bun').attr('src',url);

		}

	});



	$('#p_preview_b').on("hidden.bs.modal", function () {

		$('#p_preview_b #img_p_bun').attr('src','//groshdigital.com/wp-content/themes/grosh/assets/images/Spinner.gif');

	});



	

	var urlvideo = "";

	if($('#my-video').length){

		var myPlayer = videojs('my-video', {

		  children: {

		    controlBar: {

		      children: {

		        volumeControl: false 

		      }

		    }

		  }

		});



		$('body').on('click', '.thumb-post .caption', function(event) {

			$('#popupMsg').modal('show'); 

			var product_number = $(this).data('id');

			if(product_number){

				var url = "//s3.amazonaws.com/groshdigital/watermark/" + product_number +".mp4";

				urlvideo = url;

				myPlayer.src(urlvideo);

				myPlayer.ready(function() {

				  myPlayer.play();

				});

				// new MediaElementPlayer('playerpopup', {

				//     pluginPath: 'https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.0.3/',

				//     shimScriptAccess: 'always',

				//     stretching: 'fill',

				//     success: function(mediaElement) {

				// 	    setStream(mediaElement);



				// 	    mediaElement.addEventListener('canplay', function(e) {

				// 		    mejs.players['mep_0'].play();

				// 	    }, false);



				//     }

			 //    });



			}else{

				$('video source').attr('url','');

			}

		});



		$('body').on('hidden.bs.modal', '#popupMsg', function () {

			// for (var player in mejs.players) {

			//     mejs.players[player].media.pause();

			// }

			myPlayer.pause();

		});

	}



	function setStream(url){

		 mejs.players['mep_0'].setSrc([

            {

			    src: urlvideo,

			    type: "video/mp4"

		    }

		    ]);

		    mejs.players['mep_0'].load();

	}



	$('.quote-price input[type=radio][name=filetype]').change(function() {

        $(".quote-price form").submit();

    });

    

	function toggleSearch(type) {

		if(type=="close") {

			//close serach 

			$('.cd-search').removeClass('is-visible');

			$('.cd-search-trigger').removeClass('search-is-visible');

			$('.cd-overlay').removeClass('search-is-visible');

		} else {

			//toggle search visibility

			$('.cd-search').toggleClass('is-visible');

			$('.cd-search-trigger').toggleClass('search-is-visible');

			$('.cd-overlay').toggleClass('search-is-visible');

			setCaretPosition('s');

		}

	}



	function setCaretPosition(elemId, caretPos) {

	    var el = document.getElementById(elemId);



	    el.value = el.value;

	    // ^ this is used to not only get "focus", but

	    // to make sure we don't have it everything -selected-

	    // (it causes an issue in chrome, and having it doesn't hurt any other browser)



	    if (el !== null) {



	        if (el.createTextRange) {

	            var range = el.createTextRange();

	            range.move('character', caretPos);

	            range.select();

	            return true;

	        }



	        else {

	            // (el.selectionStart === 0 added for Firefox bug)

	            if (el.selectionStart || el.selectionStart === 0) {

	                el.focus();

	                el.setSelectionRange(caretPos, caretPos);

	                return true;

	            }



	            else  { // fail city, fortunately this never happens (as far as I've tested) :)

	                el.focus();

	                return false;

	            }

	        }

	    }

	}





	// Takes the gutter width from the bottom margin of .post

	var gutter = parseInt($('.post-box').css('marginBottom'));

	var container = $('#wrap');



	// Creates an instance of Masonry on #posts

	container.imagesLoaded(function(){

		container.masonry({

			gutter: 50,

			itemSelector: '.post-box',

			columnWidth: '.post-box'

		});

	});

	// if($('video source').attr('url')){

	// 	$('video').mediaelementplayer();

	// }



	// $(".playerslider").mediaelementplayer({



	// 	features: ['playpause','current','progress','duration','volume'] ,



	//     success: function(media, node, player) {



	//         var events = ['play' , 'ended'];



	//         for (var i=0, il=events.length; i<il; i++) {





	//             media.addEventListener(events[0], function(e) {

	//                 $('#myCarousel').carousel('pause');

	//             });



	//             media.addEventListener(events[1], function(e) {

	//                 $('#myCarousel').carousel('cycle');

	//                 $('.mejs-poster').show();

	//             });

	//         }

	//     }

	// });



	// $(".playersingle").mediaelementplayer();

	if($('#playerSingle').length){

		var sPlayer = videojs('playerSingle', {

			"loop":true

		});

		sPlayer.ready(function() {

		  sPlayer.play();

		});

	}

	$('.collapse').on('shown.bs.collapse', function(){

	$(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");

	}).on('hidden.bs.collapse', function(){

	$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");

	});

});