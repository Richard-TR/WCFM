<?php
/**
/ The PHP filter below will add a CSS class to the single product page body.
/ This allows you to target product types in css. Eg; ".simple-product .tab1 {display:none;}"
/ 


/** Add product type body class **/
add_filter('body_class','woocommerce_body_classes');
function woocommerce_body_classes( $classes ) {
    global $woocommerce, $post, $product;
    $product = get_product( $post->ID );
    $product_type = $product->product_type;
    if ( $product->product_type == 'external' ) $classes[] = 'external-product';
    if ( $product->product_type == 'grouped' ) $classes[] = 'grouped-product';
    if ( $product->product_type == 'simple' ) $classes[] = 'simple-product';
    if ( $product->product_type == 'variable' ) $classes[] = 'variable-product';
    return $classes;
}
