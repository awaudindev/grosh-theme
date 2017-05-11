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


	$('.caption').on('click', function(event){
		var product_number = $(this).data('id');
		var url = "http://s3.amazonaws.com/groshdigital/thumbnails/watermark/" + product_number +".mp4";
		playerpopup.setSrc(url);
		playerpopup.play();
		$('#popupMsg').modal('show');
	});

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
	
	$('video').mediaelementplayer();
});