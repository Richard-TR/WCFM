<?php

/** Remove from catalog when status is archived **/

add_action( 'wcfm_after_product_archived', function( $product_id ) {
	$product = wc_get_product(	$product_id );
	$product->set_catalog_visibility( 'hidden' );
	$product->save();
});
