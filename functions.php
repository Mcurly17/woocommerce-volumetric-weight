<?php

function add_volumetric_weight( $cart ) {

  if( !is_cart() && !is_checkout() )
      return;

  if ( is_admin() && ! defined( 'DOING_AJAX' ) )
      return;

  if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
      return;

  foreach ( $cart->get_cart() as $cart_item ) {

      $weight = $cart_item['data']->get_weight();
      $length = $cart_item['data']->get_length();
      $width  = $cart_item['data']->get_width();
      $height = $cart_item['data']->get_height();

      if( !$length || !$width || !$height )
              continue;


      $total_volume        = $length*$width*$height;
      $total_volume_weight = $total_volume/5000;

      // Get the highter weight
      $final_weight = (float)$weight;

      if( $final_weight < (float)$total_volume_weight ) {
          $final_weight = $total_volume_weight;
      }

      $cart_item['data']->set_weight( $final_weight );
  }

  // Make sure to update the order review
  WC()->cart->calculate_shipping();
  WC()->cart->calculate_totals();

}

add_action( 'woocommerce_before_calculate_totals', 'add_volumetric_weight', 1, 1 );

?>
