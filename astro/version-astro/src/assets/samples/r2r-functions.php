<?php

/****************
** Other functions removed for readbility **
****************/


/****************
** WOOCOMMERCE **
****************/
// add woocommerce support
function mytheme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    // add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
        
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' ); 

// enable gutenberg for woocommerce
function activate_gutenberg_product( $can_edit, $post_type ) {
 if ( $post_type == 'product' ) {
        $can_edit = true;
    }
    return $can_edit;
}
add_filter( 'use_block_editor_for_post_type', 'activate_gutenberg_product', 10, 2 );


// enable taxonomy fields for woocommerce with gutenberg on
function enable_taxonomy_rest( $args ) {
    $args['show_in_rest'] = true;
    return $args;
}
add_filter( 'woocommerce_taxonomy_args_product_cat', 'enable_taxonomy_rest' );
add_filter( 'woocommerce_taxonomy_args_product_tag', 'enable_taxonomy_rest' );


function vc523_address_fields($address_fields) {
    // new display classes for desktop
    $address_field_arr = array(
        'address_1' =>  array('form-row-80', 'd-inline-block', 'address-field'),
        'address_2' =>  array('form-row-20', 'd-inline-block', 'address-field'),
        'city' =>       array('form-row-50', 'd-inline-block', 'address-field'),
        'state' =>      array('form-row-30', 'd-inline-block', 'address-field'),
        'postcode' =>   array('form-row-20', 'd-inline-block', 'address-field'),
        'email' =>      array('form-row-two-thirds', 'd-inline-block', ),
        'phone' =>      array('form-row-one-third', 'd-inline-block', )
    );

    foreach ($address_field_arr as $k => $v) {
        $address_fields['billing']['billing_'.$k]['class'] = $v;
        if ($k != 'email' && $k != 'phone') {
            $address_fields['shipping']['shipping_'.$k]['class'] = $v;
        }
    }

    // shorter placeholder text
    $address_2 = 'Apt/Suite';
    $address_fields['billing']['billing_address_2']['label'] = $address_2;
    $address_fields['billing']['billing_address_2']['placeholder'] = $address_2;
    $address_fields['shipping']['shipping_address_2']['label'] = $address_2;
    $address_fields['shipping']['shipping_address_2']['placeholder'] = $address_2;

    // reorder fields
    $address_fields['billing']['billing_email']['priority'] = 100;
    $address_fields['billing']['billing_phone']['priority'] = 110;
    return $address_fields;
}
add_filter('woocommerce_checkout_fields', 'vc523_address_fields');

function custom_order_button_html( $button ) {
    $order_button_text = __('Place your order', 'woocommerce');

    $button = '<button type="submit" class="button alt' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>';

    return $button;
}
add_filter( 'woocommerce_order_button_html', 'custom_order_button_html');

function register_r2r_statuses() {
    register_post_status('wc-print-pickup',
		array(
            'label'		=> 'Print and manual pickup at NextPage',
			'public'	=> true,
			'show_in_admin_status_list' => true,
			'label_count'	=> _n_noop( 'Print and manual pickup at NextPage (%s)', 'Print and manual pickup at NextPage (%s)' )
            )
    );
    register_post_status('wc-manual-print-ship',
		array(
            'label'		=> 'Manual print and manual ship at MossPrinting',
			'public'	=> true,
			'show_in_admin_status_list' => true,
			'label_count'	=> _n_noop( 'Manual print and manual ship at MossPrinting (%s)', 'Manual print and manual ship at MossPrinting (%s)' )
            )
    );
    register_post_status('wc-quick-order',
		array(
            'label'		=> 'Quick Order / Processing',
			'public'	=> true,
			'show_in_admin_status_list' => true,
			'label_count'	=> _n_noop( 'Quick Order / Processing" (%s)', 'Quick Order / Processing (%s)' )
            )
    );
}
add_action( 'init', 'register_r2r_statuses' );

/* Rename order status display names */
// Changes status display name
function rename_order_status_msg( $order_statuses ) {
    $order_statuses['wc-completed']         = _x( 'Completed/Shipped', 'Order status', 'woocommerce' );
    $order_statuses['wc-processing']        = _x( 'Payment Received. On Hold', 'Order status', 'woocommerce' );
    $order_statuses['wc-quick-order']       = _x( 'Quick Order / Processing', 'Order status', 'woocommerce' );
    $order_statuses['wc-print-pickup']      = _x( 'Print and manual pickup at NextPage', 'Order status', 'woocommerce' );
    $order_statuses['wc-manual-print-ship'] = _x( 'Manual print and manual ship at MossPrinting', 'Order status', 'woocommerce' );
    $order_statuses['wc-on-hold']           = _x( 'Print and ship', 'Order status', 'woocommerce' );
    
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'rename_order_status_msg', 20, 1 );

// Changes status sort display name
function rename_order_status_top( $views ) {
    if(isset($views['wc-completed'])) {
        $views['wc-completed']  = str_replace( 'Completed', __( 'Completed/Shipped', 'woocommerce'), $views['wc-completed'] );
    }
    
    if(isset($views['wc-processing'])) {
        $views['wc-processing'] = str_replace( 'Processing', __( 'Payment Received. On Hold', 'woocommerce'), $views['wc-processing'] );
    }
    
    if(isset($views['wc-on-hold'])) {
        $views['wc-on-hold']    = str_replace( 'On hold', __( 'Print and ship', 'woocommerce'), $views['wc-on-hold'] );
    }
    
    return $views;
}
foreach ( array('post', 'shop_order') as $hook ) {
    add_filter( "views_edit-shop_order", 'rename_order_status_top' );
}

// Changes status action text
function rename_order_status_actions( $actions ) {
    $actions['mark_completed']          =   __( 'Change status to Completed/Shipped', 'woocommerce' );
    $actions['mark_processing']         =   __( 'Change status to Payment Received. On Hold.', 'woocommerce' );
    // $actions['mark_print-ship']      =   __( 'Change status to Print and ship', 'woocommerce' );
    $actions['mark_on-hold']            =   __( 'Change status to Print and ship', 'woocommerce' );
    $actions['mark_print-pickup']       = __( 'Change status to Print and manual pickup at NextPage', 'woocommerce' );
    $actions['mark_manual-print-ship']  = __( 'Change status to Manual print and manual ship at MossPrinting', 'woocommerce' );
    $actions['mark_quick-order']        = __( 'Change status to Quick Order / Processing', 'woocommerce' );
    $actions['mark_cancelled']          =   __( 'Change status to Cancelled', 'woocommerce' );
    return $actions;
}
add_filter( 'bulk_actions-edit-shop_order', 'rename_order_status_actions', 20, 1 );

// Customize status display colors
function customize_status_colors() {    ?>
    <style>
        .order-status.status-processing {
            background: #f8dda7;
            color: #94660c;
        }
        .order-status.status-print-ship,
        .order-status.status-on-hold {
            background-color: #c0f0f0;
            color: #305050;
        }
        .order-status.status-print-pickup {
            background-color: #f0c0f0;
            color: #000;
        }
        .order-status.status-manual-print-ship {
            background-color: #d2b4dc;
            color: #000;
        }
        .order-status.status-quick-order {
            background-color: #023047;
            color: #8ecae6;
        }
        .order-status.status-cancelled {
            background-color: #f0b0b0;
            color: #000;
        }
        .order-status.status-completed {
            background-color: #80cc80;
            color: #000;
        }
    </style>
    <?php
}
add_action('admin_head', 'customize_status_colors');

/* END Rename order status display names */




// Update Add to Cart buttons everywhere but on Single Product Page
// Replacing the button add to cart by a link to the product in Shop and archives pages
add_filter( 'woocommerce_loop_add_to_cart_link', 'replace_simple_add_to_cart_button', 10, 2 );
function replace_simple_add_to_cart_button( $button  ) {
	global $product;
    $product_type = $product->get_type();
    if ($product_type == 'simple' || $product_type == 'variable') {
        $button_text = __("Select product", "woocommerce");
        $button = '<a class="button btn btn-primary add_to_cart_button product-type-zakeke product_type_' . $product_type . '"';
        $button .= ' href="' . $product->get_permalink() . '"'; 
        $button .= ' data-product_id="' . $product->get_id() . '"'; 
    }
    
    if ($product_type == 'simple') {
        $button .= ' aria-label="Customize your ' . $product->get_name() . '">';
    }
    if ($product_type == 'variable') {
        $button .= ' aria-label="Select options for your ' . $product->get_name() . '"';
        $button .= ' aria-describedby="This product has multiple variants that may be customized. The options may be chosen on the product page">';
    }
    if ($product_type == 'simple' || $product_type == 'variable') {
        $button .= $button_text . '</a>';
    }

    return $button;
}

// Update Add to Cart buttons on Single Product Page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'replace_single_product_button', 9999, 2);
function replace_single_product_button( $button ) {
    global $product;

    $product_slug = $product->slug;
    
    if ($product_slug == 'mynames-bibs' || $product_slug == 'backstories-bibs') {
        return __('Customize This', 'zakeke');
    }
    return $button;
}

// Hide "Added to Cart" message
add_filter( 'wc_add_to_cart_message_html', '__return_false' );

// Remove Sorting dropdown on products archive
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Remove category from Single Product Display
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Set the maximum quantity for all products
// This enables HTML to block the click-through to the Zakeke Customizer and allows us to display the JS error message.
add_filter( 'woocommerce_quantity_input_max', 'woocommerce_quantity_input_max_callback', 10, 2 );
function woocommerce_quantity_input_max_callback( $max, $product ) {
    $max = get_field('product_maximum') ?: 40;  
    return $max;
}

add_filter( 'woocommerce_checkout_fields' , 'vc523_checkout_notes', 9999 );
function vc523_checkout_notes( $fields ) {
    $fields['order']['order_comments']['label'] = 'When do you need your order? Any special notes?';
    $fields['order']['order_comments']['placeholder'] = "";
    return $fields;
}

add_action('woocommerce_pre_payment_complete', 'add_card_details_to_order_meta');
function add_card_details_to_order_meta($order_id) {
    // prevent fatal errors if the plugin is not activated.
    if ( !is_plugin_active('woocommerce-gateway-stripe/woocommerce-gateway-stripe.php') ) {
        return;
    }
      
    $order = wc_get_order($order_id);
  
    if ($order->get_payment_method() === 'stripe') {
        $UPE = new WC_Stripe_UPE_Payment_Gateway();
        $default = new WC_Gateway_Stripe();
  
        $payment_method_title = strval(WC_Stripe_Feature_Flags::is_upe_checkout_enabled() ? $UPE->get_title() : $default->get_title());

        if ($order->get_payment_method_title() !== $payment_method_title) {
            return;
        }
  
        $found = '';
  
        // Loop through the order meta to check for Stripe source ID or Payment method ID.
        foreach ($order->get_meta_data() as $object) {
            $object_array = array_values((array)$object);
            foreach ($object_array as $object_item) {
                if ($object_item['key'] == '_stripe_source_id' || $object_item['key'] == '_payment_method_id') {
                    $found = $object_item['value'];
                    break; 
                }
            }
        }
    
        // Verify that data we're adding does not already exist in the order
        // If not, add card brand and last 4 digits
        if ( !$order->meta_exists('_card_brand')) {
            $data_dump = WC_Stripe_API::get_payment_method($found);
            $cardBrand = $data_dump->card->brand;
            $last4digits = $data_dump->card->last4;
            $order->add_meta_data('_card_brand', $cardBrand);
            $order->add_meta_data('_card_last_4', $last4digits);
            $order->save();
        }
    }
}

/* Add Order Status Details to track orders with NextPage and Moss Printing */
//
// 1. Add and save meta checkboxes on individual orders
add_action( 'add_meta_boxes', 'vc523_add_order_meta_box' );
function vc523_add_order_meta_box() {
    add_meta_box(
        'status_checkboxes',
        'Order Status Details',
        'vc523_order_display_callback',
        'shop_order',
        'side',
        'high'
    );
}

function vc523_order_display_callback( $post ) {
    $nextPage = get_post_meta( $post->ID, 'NextPage', true ); 
    $mossPrinting = get_post_meta( $post->ID, 'MossPrinting', true ); 

    woocommerce_wp_checkbox( array(
        'id'    => 'NextPage',
        'label' => __('Sent to NextPage FTP?', 'woocommerce'),
        'value' => $nextPage == 1 ? 'yes' : '',
    ));
    woocommerce_wp_checkbox( array(
        'id'    => 'MossPrinting',
        'label' => __('Sent to Moss Printing?', 'woocommerce'),
        'value' => $mossPrinting == 1 ? 'yes' : '',
    ));
} 

add_action( 'save_post', 'vc523_save_order_meta_box_data' );
function vc523_save_order_meta_box_data( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'shop_order' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_shop_order', $post_id ) ) {
            return;
        }
    }

    $nextPage = isset($_POST['NextPage']);
    update_post_meta( $post_id, 'NextPage', $nextPage );
    $mossPrinting = isset($_POST['MossPrinting']);
    update_post_meta( $post_id, 'MossPrinting', $mossPrinting );
}

add_action('admin_head', 'vc523_customize_order_status_meta_box');
function vc523_customize_order_status_meta_box() {    ?>
    <style>
        #status_checkboxes .form-field {
            display: flex;
            width: 180px;
            justify-content: space-between;
            font-size: 13px;
        }

        #status_checkboxes label {
            margin-right: 0.75rem;
        }
        .post-type-shop_order .wp-list-table .column-nextPage,
        .post-type-shop_order .wp-list-table .column-moss {
            width: 80px;
        }
    </style>
    <?php
}

// 2. Add Order Status Details to Admin Orders Table
// Add new columns
add_filter( 'manage_edit-shop_order_columns', 'vc523_order_status_meta_column', 10, 1 );
function vc523_order_status_meta_column( $columns ) {
    $columns['nextPage'] = __( 'Sent NP?', 'woocommerce' ); 
    $columns['moss'] = __( 'Sent Moss?', 'woocommerce' ); 
    return $columns;
}

//  Populate the Column
add_action( 'manage_shop_order_posts_custom_column' , 'vc523_order_status_meta_content', 10, 2 );
function vc523_order_status_meta_content( $column, $post_id ) {
    if ( $column === 'nextPage' ) {
        global $the_order; 
        $nextPage = get_post_meta( $the_order->id, 'NextPage', true ); 
        if ( !empty($nextPage) ) {  echo _e('Yes', "woocommerce"); }
    }

    if ( $column === 'moss' ) {
        global $the_order; 
        $moss = get_post_meta( $the_order->id, 'MossPrinting', true ); 
        if ( !empty($moss) ) {  echo _e('Yes', "woocommerce");  }
    }
}
/* End Add Order Status Details */

/* Add Order Item Thumbnails to Admin Orders Table */
// Add new columns
add_filter( 'manage_edit-shop_order_columns', 'vc523_order_thumbs_column', 10, 1 );
function vc523_order_thumbs_column( $columns ) {
    $columns['itemThumbs'] = __( 'Item Thumbnails', 'woocommerce' ); 
    
    return $columns;
}

//  Populate the Column
add_action( 'manage_shop_order_posts_custom_column' , 'vc523_order_thumbs_content', 10, 2 );
function vc523_order_thumbs_content( $column, $post_id ) {
    if ( $column === 'itemThumbs' ) {
        global $the_order; 
        $items = $the_order->get_items();
        if (!empty($items)) {
            foreach ($items as $item) { 
                $item_preview = $item->get_meta('zakeke_data') ? $item->get_meta('zakeke_data')['previews'][0]->url : '';
                $item_details = '<img src="'.$item_preview.'">';
                echo $item_details;
            }
        }
    }
}

add_action('admin_head', 'vc523_customize_order_thumbs_column');
function vc523_customize_order_thumbs_column() {    ?>
    <style>
        .post-type-shop_order .wp-list-table .column-itemThumbs  {
            width: 320px;
        }
        
        .post-type-shop_order .wp-list-table .column-itemThumbs img {
            max-width: 100px;
        }
    </style>
    <?php
}
/* End Add Order Item Thumbnails */

/* Add Quantity Ordered to Admin Orders Table */
add_filter('manage_edit-shop_order_columns', 'vc523_order_items_column' );
function vc523_order_items_column( $order_columns ) {
    $order_columns['order_products'] = "Product Quantity";
    return $order_columns;
}

add_action( 'manage_shop_order_posts_custom_column' , 'vc523_order_items_column_qty', 10, 2 );
function vc523_order_items_column_qty( $column ) {
  global $the_order; 
  if( $column == 'order_products' ) {
    
    $order_items = $the_order->get_items();
    if ( !is_wp_error( $order_items ) ) {
      foreach( $order_items as $order_item ) {
        $product = $order_item->get_product();
  
        $sku = ( $product && $product->get_sku() ) ? ' - ' . $product->get_sku() : '';
        echo $order_item['quantity'] .' × <a href="' . admin_url('post.php?post=' . $order_item['product_id'] . '&action=edit' ) . '" target="_blank">'. $order_item['name'] . '</a><br />';
      }
    }
  } 
}
/* End Add Order Item Qty */


/* Returns limited Order Status details by order number entered at /order-status */
add_action('gform_after_submission_2', 'get_order_status', 10, 2);
function get_order_status($entry, $form){
    $order_id = rgar($entry, '1');
    
    // Get the WooCommerce order
    $order = wc_get_order($order_id);
    if (!$order) {
        echo '<br>Sorry, we could not find order #' . $order_id . '.';
        return;
    }
    

    // Retrieve order data
    $status = $order->get_status();
    $all_statuses = wc_get_order_statuses();
    $full_status = $all_statuses['wc-'.$status];

    $order_data = $order->get_data();
    $order_created = $order->date_created;
    $order_email = $order_data['billing']['email'];

    $shipping_address = $status == 'print-pickup' ? '<th>Billing Address: </th>' : '<th>Shipping Address: </th>';
    $shipping_address .= '<td>'.$order_data['shipping']['address_1'];
    $shipping_address .= $order_data['shipping']['address_2'] 
        ? ', '.$order_data['shipping']['address_2'].'<br>' 
        : '<br>';
    $shipping_address .= $order_data['shipping']['city'].', '.$order_data['shipping']['state'].' '.$order_data['shipping']['postcode'].'</td>';

    // Retrieve details of line items in order
    $item_details = '<tr><td colspan="2"><div class="row-title">Item Details</div>';
    $item_details .= '<div class="items-preview">';
    $item_count = 1;
    foreach ( $order->get_items() as $item_id => $item ) {
        $item_name =        $item->get_name();
        $item_preview =     $item->get_meta('zakeke_data') ? $item->get_meta('zakeke_data')['previews'][0]->url : '';
        $item_quantity =    $item->get_quantity();
        $item_details .=    '<div class="item-preview"><h4>Item '.$item_count.': '.$item_name.'</h4>';
        $item_details .=    '<img src="'.$item_preview.'">';
        $item_details .=    '<p>Quantity: '.$item_quantity.'</p></div>';
        $item_count++;
    }
    $item_details .= '</div></td></tr>';

    // Build and Print the full status
    $order_status =  '<div class="order-status">';
    $order_status .= '<table class="table align-top">';
    $order_status .= '<tr><th colspan="2" class="table-title text-primary">Order number '.$order_id.'</th></tr>';
    $order_status .= '<tr><th>Order Date: </th><td>'.$order_created->format("F j, Y").'</td></tr>';
    $order_status .= '<tr><th>Order Status: </th><td>'.$full_status.'</td></tr>';
    $order_status .= '<tr><th>Email Address: </th><td>'.$order_email.'</td></tr>';
    $order_status .= $item_details;
    $order_status .= '<tr>'.$shipping_address.'</tr>';

    $order_notes = wc_get_order_notes( array('order_id' => $order_id) );
    if ($order_notes) {
        $tracking_details = array();
        $canceled = array();
        foreach ($order_notes as $note) {
            if ( !$note->customer_note ) {
                $note_content = $note->content;
                
                preg_match( '/Order\sshipped\svia\s(.*)\swith\stracking\snumber\s(.*)/', $note_content, $matches );
                
                /* if we have a shipment tracking number, and 
                ** there is not a later tracking number, 
                ** build the tracking details array
                */
                if ($matches) {
                    $note_date_created_obj = $note->date_created;
                    $note_date_created = $note_date_created_obj->format('Y-m-d-H:i:s');
                    if (!$tracking_details || $tracking_details['date'] > $note_date_created) {
                        $tracking_details['provider'] = $matches[1];
                        $tracking_details['number'] =   $matches[2];
                        $tracking_details['date'] =     $note_date_created;
                    }
                }
                
                preg_match( '/Order.*canceled/', $note_content, $canceled_matches );

                /* if we have a canceled shipment, and
                ** there is not a later cancelation, 
                ** build the canceled array
                */
                if ($canceled_matches) {
                    $note_date_created_obj = $note->date_created;
                    $note_date_created = $note_date_created_obj->format('Y-m-d-H:i:s');
                    if (!$canceled || $canceled['date'] > $note_date_created) {
                        $canceled['date'] = $note_date_created;
                    }
                }
            }
        }

        $order_shipped = true; 
        if ( !$tracking_details['date'] || $tracking_details['date'] < $canceled['date'] ) {
            $order_shipped = false;
        } 
        
        if ($order_shipped) {
            $order_status .= '<tr><th>Shipment Date: </th><td>'.$note_date_created_obj->format("F j, Y").'</td></tr>';
            $order_status .= '<tr><th>Tracking Info: </th>'.'<td>'.$tracking_details['provider'].': '.$tracking_details['number'].'</td></tr>';
        } elseif ($status != 'print-pickup') {
            $order_status .= '<tr><th>Shipment Date: </th><td>Pending</td></tr>';
        }
    }

    // Add card details from Stripe to order status, if applicable
    add_card_details_to_order_meta($order_id);
    $card_brand = $order->get_meta('_card_brand');
    $card_last_4 = $order->get_meta('_card_last_4');

    if ($card_brand && $card_last_4) {
        $order_status .= '<tr><th>Billed To: </th><td class="text-uppercase">'.$card_brand.' '.$card_last_4.'</td>';
    }

    $order_status .= '</div>';
    echo $order_status;
}
/* End Display Order Status details at /order-status/ */
