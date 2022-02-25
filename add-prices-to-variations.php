<?php

// This adds the prices of product variations to the drop down selection

/**
 * Add prices to variations drop down
*/
function display_price_in_variation_option_name( $term ) {
    global $product;

    if ( empty( $term ) ) {
        return $term;
    }
    if ( empty( $product->id ) ) {
        return $term;
    }

    $variation_id = $product->get_children();

    foreach ( $variation_id as $id ) {
        $_product       = new WC_Product_Variation( $id );
        $variation_data = $_product->get_variation_attributes();

        foreach ( $variation_data as $key => $data ) {
            if ( $data == $term ) {
                $html = $term;
                $html .= ( $_product->get_stock_quantity() ) ? ' - ' . $_product->get_stock_quantity() : '';
                $html .= ' - ' . wp_kses( woocommerce_price( $_product->get_price() ), array() );
                return $html;
            }
        }
    }

    return $term;
}
add_filter( 'woocommerce_variation_option_name','display_price_in_variation_option_name', 10 , 1 );
