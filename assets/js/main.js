jQuery(document).ready(function($){


	if($('#datepicker1').length || $('#datepicker2').length){
		 $('#datepicker1').datepicker({
		 	startDate:new Date(), 
		 	autoclose:true
		 });
	     $('#datepicker2').datepicker({
	     	startDate:new Date(), 
	     	autoclose:true
	     });
	 }
     
	 $('.selectpicker').selectpicker({
  
      });
	//open search form
	$('.cd-search-trigger').on('click', function(event){
		event.preventDefault();
		toggleSearch();
		//closeNav();
	});

	var urlvideo = "";

	$('.caption').on('click', function(event){
		$('#popupMsg').modal('show'); 
		var product_number = $(this).data('id');

		if(product_number){
			var url = "http://s3.amazonaws.com/groshdigital/thumbnails/watermark/" + product_number +".mp4";
			urlvideo = url;
			new MediaElementPlayer('playerpopup', {
			    pluginPath: 'https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.0.3/',
			    shimScriptAccess: 'always',
			    stretching: 'fill',
			    success: function(mediaElement) {
				    setStream(mediaElement);

				    mediaElement.addEventListener('canplay', function(e) {
					    mejs.players['mep_0'].play();
				    }, false);

			    }
		    });
		}else{
			$('video source').attr('url','');
		}
	});

	$('body').on('hidden.bs.modal', '#popupMsg', function () {
		$('#playerpopup')[0].pause();
		for (var player in mejs.players) {
		    mejs.players[player].media.pause();
		}
	});

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
	if($('video source').attr('url')){
		$('video').mediaelementplayer();
	}

	$("#player1").mediaelementplayer();

	
});