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
	$('.caption').on('click', function(event){
		var product_number = $(this).data('id');
		var url = "http://s3.amazonaws.com/tndr/grosh/assets/" + product_number +".gif";
		document.getElementById("img-animation").src = url;
		$('#popupMsg').modal('show');
	});

	$('input[type=radio][name=filetype]').change(function() {
        $("#product-type-form").submit();
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