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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.28/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.28/vfs_fonts.js"></script>
 <script type="text/javascript">
  jQuery(function($){
  	function ParseContainer(cnt, e, p, styles) {
	var elements = [];
	var children = e.childNodes;
	if (children.length != 0) {
	    for (var i = 0; i < children.length; i++) p = ParseElement(elements, children[i], p, styles);
	}
	if (elements.length != 0) {            
	    for (var i = 0; i < elements.length; i++) cnt.push(elements[i]);
	}
	return p;
	}

	function ComputeStyle(o, styles) {
	for (var i = 0; i < styles.length; i++) {
	    var st = styles[i].trim().toLowerCase().split(":");
	    if (st.length == 2) {
	        switch (st[0]) {
	            case "font-size":{
	                o.fontSize = parseInt(st[1]);
	                break;
	            }
	            case "text-align": {
	                switch (st[1]) {
	                    case "right": o.alignment = 'right'; break;
	                    case "center": o.alignment = 'center'; break;
	                }
	                break;
	            }
	            case "font-weight": {
	                switch (st[1]) {
	                    case "bold": o.bold = true; break;
	                }
	                break;
	            }
	            case "text-decoration": {
	                switch (st[1]) {
	                    case "underline": o.decoration = "underline"; break;
	                }
	                break;
	            }
	            case "font-style": {
	                switch (st[1]) {
	                    case "italic": o.italics = true; break;
	                }
	                break;
	            }
	        }
	    }
	}
	}

	function ParseElement(cnt, e, p, styles) {
	if (!styles) styles = [];
	if (e.getAttribute) {
	    var nodeStyle = e.getAttribute("style");
	    if (nodeStyle) {
	        var ns = nodeStyle.split(";");
	        for (var k = 0; k < ns.length; k++) styles.push(ns[k]);
	    }
	}

	switch (e.nodeName.toLowerCase()) {
	    case "#text": {
	        var t = { text: e.textContent.replace(/\n/g, "") };
	        if (styles) ComputeStyle(t, styles);
	        p.text.push(t);
	        break;
	    }
	    case "b":case "strong": {
	        //styles.push("font-weight:bold");
	        ParseContainer(cnt, e, p, styles.concat(["font-weight:bold"]));
	        break;
	    }
	    case "u": {
	        //styles.push("text-decoration:underline");
	        ParseContainer(cnt, e, p, styles.concat(["text-decoration:underline"]));
	        break;
	    }
	    case "i": {
	        //styles.push("font-style:italic");
	        ParseContainer(cnt, e, p, styles.concat(["font-style:italic"]));
	        //styles.pop();
	        break;
	        //cnt.push({ text: e.innerText, bold: false });
	    }
	    case "span": {
	        ParseContainer(cnt, e, p, styles);
	        break;
	    }
	    case "br": {
	        p = CreateParagraph();
	        cnt.push(p);
	        break;
	    }
	    case "table":
	        {
	            var t = {
	                table: {
	                	style: [{
							margin: [0, 5, 0, 15]
						}],
	                    widths: [],
	                    body: []
	                }
	            }
	            var border = e.getAttribute("border");
	            var isBorder = false;
	            if (border) if (parseInt(border) == 1) isBorder = true;
	            if (!isBorder) t.layout = 'noBorders';
	            ParseContainer(t.table.body, e, p, styles);

	            var widths = e.getAttribute("widths");
	            if (!widths) {
	                if (t.table.body.length != 0) {
	                    if (t.table.body[0].length != 0) for (var k = 0; k < t.table.body[0].length; k++) t.table.widths.push("*");
	                }
	            } else {
	                var w = widths.split(",");
	                for (var k = 0; k < w.length; k++) t.table.widths.push(w[k]);
	            }
	            cnt.push(t);
	            break;
	        }
	    case "tbody": {
	        ParseContainer(cnt, e, p, styles);
	        //p = CreateParagraph();
	        break;
	    }
	    case "tr": {
	        var row = [];
	        ParseContainer(row, e, p, styles);
	        cnt.push(row);
	        break;
	    }
	    case "td": {
	        p = CreateParagraph();
	        var st = {stack: []}
	        st.stack.push(p);

	        var rspan = e.getAttribute("rowspan");
	        if (rspan) st.rowSpan = parseInt(rspan);
	        var cspan = e.getAttribute("colspan");
	        if (cspan) st.colSpan = parseInt(cspan);

	        ParseContainer(st.stack, e, p, styles);
	        cnt.push(st);
	        break;
	    }
	    case "div":case "p": {
	        p = CreateParagraph();
	        var st = {stack: []}
	        st.stack.push(p);
	        ComputeStyle(st, styles);
	        ParseContainer(st.stack, e, p);

	        cnt.push(st);
	        break;
	    }
	    default: {
	        console.log("Parsing for node " + e.nodeName + " not found");
	        break;
	    }
	}
	return p;
	}

	function ParseHtml(cnt, htmlText) {
	var html = $(htmlText.replace(/\t/g, "").replace(/\n/g, ""));
	var p = CreateParagraph();
	for (var i = 0; i < html.length; i++) ParseElement(cnt, html.get(i), p);
	}

	function CreateParagraph() {
	var p = {text:[]};
	return p;
	}
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
			    $('.download-loading').remove();
				 content = [];
				ParseHtml(content, data);
				pdfMake.createPdf({content: content}).download('order #'+product+'.pdf');	
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

