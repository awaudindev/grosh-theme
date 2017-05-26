<?php
/**
 * My Orders
 *
 * @deprecated  2.6.0 this template file is no longer used. My Account shortcode uses orders.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
	'order-number'  => __( 'Order', 'woocommerce' ),
	'order-date'    => __( 'Date', 'woocommerce' ),
	'order-status'  => __( 'Status', 'woocommerce' ),
	'order-total'   => __( 'Total', 'woocommerce' ),
	'order-actions' => '&nbsp;',
) );

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => $order_count,
	'meta_query' => array(
		array(
			'key'    => '_customer_user',
			'value'  => get_current_user_id(),
		),
		array(
			'key'	=> '_payment_method',
			'value' => 'purchase_order'
		)
	),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );

if ( $customer_orders ) : ?>

	<h2><?php echo  __( 'Wishlist', 'woocommerce' ) ; ?></h2>

	<table class="shop_table shop_table_responsive my_account_orders">

		<thead>
			<tr>
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>
				<tr class="order">
					<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
						<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo wc_get_order_status_name( $order->get_status() ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php echo sprintf( _n( '%s for %s item', '%s for %s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ); ?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
									$actions = array(
										'pay'    => array(
											'url'  => $order->get_checkout_payment_url(),
											'name' => __( 'Pay', 'woocommerce' )
										),
										'view'   => array(
											'url'  => $order->get_view_order_url(),
											'name' => __( 'View', 'woocommerce' )
										),
										'cancel' => array(
											'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
											'name' => __( 'Cancel', 'woocommerce' )
										)
									);

									if ( ! $order->needs_payment() ) {
										unset( $actions['pay'] );
									}

									if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
										unset( $actions['cancel'] );
									}

									if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
										echo '<a href="#" class="button btn btn-default save-pdf" data-product="'.$order->get_order_number().'"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> PDF</a>';
										foreach ( $actions as $key => $action ) {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										}
									}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
<?php add_action('wp_footer',function(){ ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
 <script type="text/javascript">
  jQuery(function($){
  	 var canvasToImage = function(canvas){
        var img = new Image();
        var dataURL = canvas.toDataURL('image/png');
        img.src = dataURL;
        return img;
    };
    var canvasShiftImage = function(oldCanvas,shiftAmt){
        shiftAmt = parseInt(shiftAmt) || 0;
        if(!shiftAmt){ return oldCanvas; }
        
        var newCanvas = document.createElement('canvas');
        newCanvas.height = oldCanvas.height - shiftAmt;
        newCanvas.width = oldCanvas.width;
        var ctx = newCanvas.getContext('2d');
        
        var img = canvasToImage(oldCanvas);
        ctx.drawImage(img,0, shiftAmt, img.width, img.height, 0, 0, img.width, img.height);
        
        return newCanvas;
    };
    
    
    var canvasToImageSuccess = function(canvas,product){
        var pdf = new jsPDF('p','pt','a4'),
            pdfInternals = pdf.internal,
            pdfPageSize = pdfInternals.pageSize,
            pdfScaleFactor = pdfInternals.scaleFactor,
            pdfPageWidth = pdfPageSize.width,
            pdfPageHeight = pdfPageSize.height,
            totalPdfHeight = 0,
            htmlPageHeight = canvas.height,
            htmlScaleFactor = canvas.width / (pdfPageWidth * pdfScaleFactor),
            safetyNet = 0;
        
        while(totalPdfHeight < htmlPageHeight && safetyNet < 15){
            var newCanvas = canvasShiftImage(canvas, totalPdfHeight);
            pdf.addImage(newCanvas, 'png', 15, 15, 520, 0, null, 'NONE');
            
            totalPdfHeight += (pdfPageHeight * pdfScaleFactor * htmlScaleFactor);
            
            if(totalPdfHeight < htmlPageHeight){
                pdf.addPage();
            }
            safetyNet++;
        }

	    pdf.save('Order #'+product+'.pdf');
	};
    $(document).ready( function() {
    	$(window).on('load',function(){
    	$('.save-pdf').on('click',function(e){
    		e.preventDefault();
    		var product = $(this).attr('data-product');
	    	$.ajax({
			  type: 'POST',
			  url: '<?php echo admin_url('admin-ajax.php'); ?>/?action=save_pdf&id='+product,
			  beforeSend:function(){
			  	$('.download-loading,.temp-box').remove();
			  	$('body').append('<div style="position:fixed;top:50%;left:50%;transform:translateX(-50%);padding:30px;background:rgba(0,0,0,0.5);color:#fff;font-weight:bold;font-size:24px;" class="download-loading">Processing....</div>');
			  },
			  success: function(data){
    			$('body').append('<div class="temp-box" style="max-width:540px;">'+data+'</div>');
	            html2canvas($('.temp-box')[0],{	
				    onrendered: function(canvas){
				        canvasToImageSuccess(canvas,product);
				    }
				});

	            setTimeout(function() { 
			    $(document).find($('canvas,.temp-box')).remove();
			    }, 2000);
			    $('.download-loading').remove();	
			  },
			  error:function(jqXHR,textStatus,errorThrown){
			  	console.log(textStatus);
			  }
			});
	       });
    		});
	    });
  	});
</script>

<?php },15);

