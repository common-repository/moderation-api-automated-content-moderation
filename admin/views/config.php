<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<div id="modapi-plugin-container">
	<div class="modapi-masthead">
		<div class="modapi-masthead__inside-container">
			<?php Moderation_Api::view( 'logo' ); ?>
		</div>
	</div>
	<div class="modapi-lower">
		<?php if ( Moderation_Api::get_api_key() ) { ?>
			<?php Moderation_Api::display_status(); ?>
		<?php } ?>
		<?php if ( ! empty( $notices ) ) { ?>
			<?php foreach ( $notices as $notice ) { ?>
				<?php Moderation_Api::view( 'notice', $notice ); ?>
			<?php } ?>
		<?php } ?>

		

					<div class="modapi-card">
					<div class="modapi-section-header">
						<h2 class="modapi-section-header__label">
							<span><?php esc_html_e( 'Account' , 'modapi'); ?></span>
						</h2>
					</div>

					<div class="inside">
						<table class="modapi-account">
							<tbody>
								<tr>
									<th scope="row"><?php esc_html_e( 'Project', 'modapi' ); ?></th>
									<td>
										<?php echo esc_html( $modapi_user->current_project->name ); ?>
									</td>
								</tr>

								<tr>
									<th scope="row"><?php esc_html_e( 'Subscription type', 'modapi' ); ?></th>
									<td>
										<?php echo esc_html( $modapi_user->paid_plan_name ); ?>
									</td>
								</tr>
							

								<tr>
									<th scope="row"><?php esc_html_e( 'Quota', 'modapi' ); ?></th>
									<td>
										<?php echo number_format(esc_html( $modapi_user->text_api_quota )); ?>
									</td>
								</tr>

								<tr>
									<th scope="row"><?php esc_html_e( 'Remaining quota', 'modapi' ); ?></th>
									<td>
										<?php echo number_format(esc_html( $modapi_user->remaining_quota )); ?>
									</td>
								</tr>
							
								
							</tbody>
						</table>
						<div class="modapi-settings__row"></div>
						<div class="modapi-card-actions">
						<div id="delete-action">
									<a class="submitdelete deletion" href="<?php echo esc_url( Moderation_Api_Admin::get_page_url( 'delete_key' ) ); ?>"><?php esc_html_e( 'Disconnect this account', 'modapi' ); ?></a>
								</div>

							<div id="publishing-action">
								<a href="https://moderationapi.com/app/upgrade" target="_blank">
									<button type="button"  class="modapi-button " >
										<?php esc_attr_e( 'Upgrade plan', 'modapi' ); ?>
									</button>
								</a>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>

			<div class="modapi-card">
				<div class="modapi-section-header">
					<h2 class="modapi-section-header__label">
						<span><?php esc_html_e( 'Settings' , 'modapi'); ?></span>
					</h2>
				</div>

				<div class="inside">
					<form action="<?php echo esc_url( Moderation_Api_Admin::get_page_url() ); ?>" autocomplete="off" method="POST" id="modapi-settings-form">
						
						<div class="modapi-settings">
								<div class="modapi-settings__row">
									<h3 class="modapi-settings__row-title">
										<label class="modapi-settings__row-label" for="key"><?php esc_html_e( 'API key', 'modapi' ); ?></label>
									</h3>
									<div class="modapi-settings__row-input">
										<span class="api-key"><input id="key" name="key" type="text" style="width: 100%;" value="<?php echo esc_attr( get_option('moderation_api_key') ); ?>" ></span>
									</div>
								</div>


								<div class="modapi-settings__row">
									<h3 class="modapi-settings__row-title">
										<label class="modapi-settings__row-label" for="key"><?php esc_html_e( 'Content filter', 'modapi' ); ?></label>
									</h3>
									<div class="modapi-settings__row-input">
										<a href="<?php echo esc_url(Moderation_Api::API_URL . '/app/projects/' . esc_attr($modapi_user->current_project->id)); ?>" target="_blank">
										<button type="button"  class="modapi-button secondary" >
										<?php esc_attr_e( 'Edit content filter', 'modapi' ); ?>
										</button>
										</a>
									</div>

								</div>

							
							
							<div class="modapi-settings__row is-radio">
								<div class="modapi-settings__row-text">
									<h3 class="modapi-settings__row-title"><?php esc_html_e( 'Flagged comments', 'modapi' ); ?></h3>
								</div>
								<div class="modapi-settings__row-input">
									<fieldset>
										
										<div>
											<label class="modapi-settings__row-input-label" for="modapi_flagged_action_3">
												<input type="radio" name="modapi_flagged_action" id="modapi_flagged_action_3" value="3" <?php checked( '3', get_option( 'modapi_flagged_action' ) ); ?> /> 
												<span class="modapi-settings__row-label-text">
													<?php esc_html_e( 'Move flagged comments to spam.', 'modapi' ); ?>
												</span>
											</label>
										</div>
										<div>
											<label class="modapi-settings__row-input-label" for="modapi_flagged_action_2">
												<input type="radio" name="modapi_flagged_action" id="modapi_flagged_action_2" value="2" <?php checked( '2', get_option( 'modapi_flagged_action' ) ); ?> /> 
												<span class="modapi-settings__row-label-text">
													<?php esc_html_e( 'Move flagged comments to pending for review.', 'modapi' ); ?>
												</span>
											</label>
										</div>

										<div>
											<label class="modapi-settings__row-input-label" for="modapi_flagged_action_4">
												<input type="radio" name="modapi_flagged_action" id="modapi_flagged_action_4" value="4" <?php checked( '4', get_option( 'modapi_flagged_action' ) ); ?> /> 
												<span class="modapi-settings__row-label-text">
													<?php esc_html_e( 'Move flagged comments to trash.', 'modapi' ); ?>
												</span>
											</label>
										</div>
										
										<div>
											<label class="modapi-settings__row-input-label" for="modapi_flagged_action_1">
												<input type="radio" name="modapi_flagged_action" id="modapi_flagged_action_1" value="1" <?php checked( '1', get_option( 'modapi_flagged_action' ) ); ?> /> 
												<span class="modapi-settings__row-label-text">
													<?php esc_html_e( 'Move flagged comments to approved.', 'modapi' ); ?>
												</span>
											</label>
										</div>
										<div>
											<label class="modapi-settings__row-input-label" for="modapi_flagged_action_0">
												<input type="radio" name="modapi_flagged_action" id="modapi_flagged_action_0" value="0" <?php checked( '0', get_option( 'modapi_flagged_action' ) ); ?> /> 
												<span class="modapi-settings__row-label-text">
													<?php esc_html_e( 'Do not do anything.', 'modapi' ); ?>
												</span>
											</label>
										</div>
									</fieldset>


									<div class="modapi-settings__row-note">
										<strong><?php esc_html_e( 'Note:', 'modapi' ); ?></strong>
										We recommend to use the <a href="https://moderationapi.com/app/moderation/queue" target="_blank">content queue</a> in the Moderation API dashboard to review flagged comments.
									</div>
									
								</div>
							</div>
								
							
						</div>
						
						<div class="modapi-card-actions">
								
							
							<?php wp_nonce_field( Moderation_Api_Admin::NONCE ); ?>
							
							<div id="publishing-action">
								<input type="hidden" name="action" value="enter-key">
								<input type="submit" name="submit" id="submit" class="modapi-button modapi-could-be-primary" value="<?php esc_attr_e( 'Save changes', 'modapi' ); ?>">
							</div>
							<div class="clear"></div>
						</div>
					</form>
				</div>
			</div>

			
	</div>
</div>