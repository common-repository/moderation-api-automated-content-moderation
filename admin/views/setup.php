<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="modapi-setup-instructions">
	<p><?php esc_html_e( 'Set up your Moderation API account to enable content moderation on this site.', 'modapi' ); ?></p>
	<?php Moderation_Api::view( 'get', array( 'text' => __( 'Connect your Moderation API account' , 'modapi' ), 'classes' => array( 'modapi-button', 'modapi-is-primary' ) ) ); ?>
</div>
