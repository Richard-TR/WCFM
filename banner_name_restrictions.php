<?php
/**  do not copy. just for syntax highlighting

Restrict vendors from choosing a banner type or changing their name position.

We wanted to keep stores quite uniform and this enabled us to limit the banner
type and the store name position. In our example we wanted to keep it out of 
the header image).

--------------------------------------------------------------------------

/** Disable store banner type selection for vendors **/
add_filter( 'wcfm_is_allow_store_banner_type', '__return_false' );

/** Disable store name position option for vendors**/
add_filter( 'wcfm_marketplace_settings_fields_visibility', function( $visibility_fields, $store_id ) {
	$visibility_fields = wcfm_hide_field( 'store_name_position', $visibility_fields );
  return $visibility_fields;
}, 50, 2 );

--------------------------------------------------------------------------
