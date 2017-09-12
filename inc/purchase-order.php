<?php

/**
 * Add the gateway to WC Available Gateways
 * 
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + offline gateway
 */
function wc_po_add_to_gateways( $gateways ) {
	$gateways[] = 'WC_Gateway_PO';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'wc_po_add_to_gateways' );

add_action('init','wc_po_gateway_init');
function wc_po_gateway_init() {
	class WC_Gateway_PO extends WC_Payment_Gateway {
		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {
	  
			$this->id                 = 'purchase_order';
			$this->icon               = apply_filters('woocommerce_po_icon', '');
			$this->has_fields         = false;
			$this->method_title       = __( 'Purchase Order', 'wc-gateway-po' );
			$this->method_description = __( 'Allows offline payments. Very handy if you use your cheque gateway for another payment method, and can help with testing. Orders are marked as "on-hold" when received.', 'wc-gateway-po' );
		  
			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();
		  
			// Define user set variables
			$this->title        = $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->instructions = $this->get_option( 'instructions', $this->description );
		  
			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		  
			// Customer Emails
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		}
	
	
		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function init_form_fields() {
	  
			$this->form_fields = apply_filters( 'wc_po_form_fields', array(
		  
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'wc-gateway-po' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Purchase Order Payment', 'wc-gateway-po' ),
					'default' => 'yes'
				),
				
				'title' => array(
					'title'       => __( 'Title', 'wc-gateway-po' ),
					'type'        => 'text',
					'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'wc-gateway-po' ),
					'default'     => __( 'Purchase Order', 'wc-gateway-po' ),
					'desc_tip'    => true,
				),
				
				'description' => array(
					'title'       => __( 'Description', 'wc-gateway-po' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'wc-gateway-po' ),
					'default'     => __( 'Please remit payment to Store Name upon pickup or delivery.', 'wc-gateway-po' ),
					'desc_tip'    => true,
				),
				
				'instructions' => array(
					'title'       => __( 'Instructions', 'wc-gateway-po' ),
					'type'        => 'textarea',
					'description' => __( 'Instructions that will be added to the thank you page and emails.', 'wc-gateway-po' ),
					'default'     => '',
					'desc_tip'    => true,
				),
			) );
		}
	
	
		/**
		 * Output for the order received page.
		 */
		public function thankyou_page() {
			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) );
			}
		}
	
	
		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order
		 * @param bool $sent_to_admin
		 * @param bool $plain_text
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		
			if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
		}
	
	
		/**
		 * Process the payment and return the result
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
	
			$order = wc_get_order( $order_id );

			// if(isset($_POST[ $this->id.'-po-number']) && trim($_POST[ $this->id.'-po-number'])!=''){
				// add_post_meta( $order_id, 'po_number', trim($_POST[ $this->id.'-po-number']) );
				
				// Mark as on-hold (we're awaiting the payment)
				$order->update_status( 'on-hold', __( 'Awaiting purchase order', 'wc-gateway-po' ) );
				
				// Reduce stock levels
				// $order->reduce_order_stock();
				
				// Remove cart
				WC()->cart->empty_cart();
				
				// Return thankyou redirect
				return array(
					'result' 	=> 'success',
					'redirect'	=> $this->get_return_url( $order )
				);

			// }else{
			// 	wc_add_notice( __('P.O number can not be empty', 'woothemes'), 'error' );
			// 	return;
			// }
		}

		public function payment_fields(){
		?>
		<?php
	}
	
  } // end \WC_Gateway_Offline class
}