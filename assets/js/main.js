jQuery(document).ready(function($){
	 $('#datepicker1').datepicker();
    $('#datepicker2').datepicker();
	 $('.selectpicker').selectpicker({
  
      });
	//open search form
	$('.cd-search-trigger').on('click', function(event){
		event.preventDefault();
		toggleSearch();
		//closeNav();
	});
	$('.category-post').on('click', function(event){
		var product_number = $(this).data('id');
		$('#popupMsg').modal('show');
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

	
});