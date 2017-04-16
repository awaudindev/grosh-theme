jQuery(function($){

$(document).ready( function() {

	function sendFeedback(url,data) {

		$('.woocommerce .cart-collaterals .cart_totals').append('<div class="loading" style="position:absolute;top:0;left:0;z-index:10;width:100%;height:100%;color:#fff;background:rgba(0,0,0,0.6);text-align:center;"><strong style="position:relative;top:50%;transform:translateY(-50%);font-size:40px;letter-spacing:1px;">Calculating....</strong></div>');

        jQuery.post(ajaxurl, data, function(result) {
            var response = JSON.parse(result),
            newtotal = '<tbody>'+
            	'<tr class="cart-subtotal">'+
            	'<th>Subtotal</th>'+
            	'<td data-title="Subtotal">'+response.total+'</td>'+
				'</tr>'+
				'<tr class="order-total">'+
				'<th>Total</th>'+
				'<td data-title="Total"><strong>'+response.total+'</strong> </td></tr>';
          
            $('.cart_totals table.shop_table').html(newtotal);
            $('.woocommerce .cart-collaterals .cart_totals .loading').remove();
        });
    }

	var dates = $( "#from, #to" ).datepicker({
	    dateFormat: 'mm/dd/yy',
	    minDate : 'today',
	    onSelect: function(dateText, inst) {
	        //set value
	        $("#" + this.id + "_value").val(dateText);
	        $("." + this.id + "_text").html(dateText);

	        var datepickerBegin = $("#from_value").val(); // lets, returning in mm/dd/yy format
			var datepickerEnd = $("#to_value").val(); // lets, returning in mm/dd/yy format
	        //set the min or max date
	        var option = this.id == "from" ? "minDate" : "maxDate",
	        origin = this.id == "from" ? "from" : "to",
	        instance = $( this ).data( "datepicker" ),
	        date = $.datepicker.parseDate(
	            instance.settings.dateFormat ||
	            $.datepicker._defaults.dateFormat,
	            dateText, instance.settings );
	        dates.not( this ).datepicker( "option", option, date );

			if ( ($.datepicker.parseDate('mm/dd/yy', datepickerBegin) >  $.datepicker.parseDate('mm/dd/yy', datepickerEnd))) {
				$("#to_value").val(dates.not( this ).datepicker( "option", option, date ).val());
		        $(".to_text").html(dates.not( this ).datepicker( "option", option, date ).val());
			}

	        sendFeedback($('#from_value').attr('data-url'),'action=check_total&fromdate='+$("#from").val()+'&todate='+$("#to_value").val());
	    }
	});
});

});