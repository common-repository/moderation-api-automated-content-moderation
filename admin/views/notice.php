<?php
/**
 * Admin Notice View
 *
 * @param string $message The message to display.
 * @param string $type    The type of notice ('success', 'error', 'warning', 'info').
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure the type is one of the allowed classes
$allowed_types = array( 'success', 'error', 'warning', 'info' );
$notice_type = in_array( $type, $allowed_types, true ) ? $type : 'info';
?>
<div class="notice moderation-api-notice">
  <a class="modapi-button" href="<?php echo esc_url( Moderation_Api_Admin::get_page_url( 'init' ) ); ?>">
  Set up Moderation API
</a>

    <p style="font-size: 15px;"><?php echo esc_html( $message ); ?></p>
</div>
