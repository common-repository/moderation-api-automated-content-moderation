<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

$submit_classes_attr = 'modapi-button';

if ( isset( $classes ) && ( is_countable( $classes ) ? count( $classes ) : 0 ) > 0 ) {
	$submit_classes_attr = implode( ' ', $classes );
}
?>

<form name="modapi_activate" action="<?php echo esc_url(Moderation_Api::API_URL . '/api/account/integration-quick-setup'); ?>" method="GET" target="_blank">
	<input type="hidden" name="successUrl" value="<?php echo esc_url( Moderation_Api_Admin::get_page_url() ); ?>"/>
	<input type="hidden" name="projectName" value="<?php echo esc_attr(get_bloginfo('name')); ?>"/>
	<button type="submit" class="<?php echo esc_attr( $submit_classes_attr ); ?>" value="<?php echo esc_attr( $text ); ?>"><?php echo esc_attr( $text ) . '<span class="screen-reader-text">' . esc_html__( '(opens in a new tab)', 'modapi' ) . '</span>'; ?></button>
</form>