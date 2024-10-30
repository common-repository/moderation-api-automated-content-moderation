<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="modapi-enter-api-key-box centered">
	<a href="#"><?php esc_html_e( 'Manually enter an API key', 'modapi' ); ?></a>
	<div class="enter-api-key">
		<form action="<?php echo esc_url( Moderation_Api_Admin::get_page_url() ); ?>" method="post">
			<?php wp_nonce_field( Moderation_Api_Admin::NONCE ) ?>
			<input type="hidden" name="action" value="enter-key">
			<p style="width: 100%; display: flex; flex-wrap: nowrap; box-sizing: border-box;">
				<input id="key" name="key" type="text" size="15" value="" placeholder="<?php esc_attr_e( 'Enter your API key' , 'modapi' ); ?>" class="regular-text code" style="flex-grow: 1; margin-right: 1rem;">
				<input type="submit" name="submit" id="submit" class="modapi-button" value="<?php esc_attr_e( 'Connect API key', 'modapi' );?>">
			</p>
		</form>
	</div>
</div>