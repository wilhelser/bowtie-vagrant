<div id="ga_access_code_modal" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1000000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php _e( 'Please paste the access code obtained from Google below:' ) ?></h4>
            </div>
            <div class="modal-body">
                <label for="ga_access_code"><strong><?php _e( 'Access Code' ); ?></strong>:</label>
                &nbsp;<input id="ga_access_code_tmp" type="text" style="width: 350px"
                             placeholder="<?php _e( 'Paste your access code here' ) ?>"/>
                <div class="ga-loader-wrapper">
                    <div class="ga-loader"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                        id="ga_save_access_code"
                        onclick="ga_popup.saveAccessCode(event)"><?php _e( 'Save Changes' ); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="wrap ga-wrap">
    <h2>Google Analytics - <?php _e( 'Settings' ); ?></h2>
    <div class="ga_container">
        <form id="ga_form" method="post" action="options.php">
			<?php settings_fields( 'googleanalytics' ); ?>
            <input id="ga_access_code" type="hidden"
                   name="<?php echo esc_attr( Ga_Admin::GA_OAUTH_AUTH_CODE_OPTION_NAME ); ?>" value=""/>
			<table class="form-table">
				<tr valign="top">
					<?php if ( ! empty( $data['popup_url'] ) ): ?>
						<th scope="row">
							<label <?php echo ( ! Ga_Helper::are_terms_accepted() ) ? 'class="label-grey ga-tooltip"' : '' ?>><?php echo _e( 'Google Profile' ) ?>
								:
								<span class="ga-tooltiptext ga-tt-abs"><?php _e( 'Please accept the terms to use this feature' ); ?></span>
							</label>
						</th>
						<td <?php echo ( ! Ga_Helper::are_terms_accepted() ) ? 'class="ga-tooltip"' : ''; ?>>
							<button id="ga_authorize_with_google_button" class="btn btn-primary"
								<?php if ( Ga_Helper::are_terms_accepted() ) : ?>
									onclick="ga_popup.authorize(event, '<?php echo esc_attr( $data['popup_url'] ); ?>')"
								<?php endif; ?>
								<?php echo( ( esc_attr( $data[ Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ] ) || ! Ga_Helper::are_terms_accepted() ) ? 'disabled="disabled"' : '' ); ?>
							><?php _e( 'Authenticate
						with Google' ) ?>
							</button>
							<span class="ga-tooltiptext"><?php _e( 'Please accept the terms to use this feature' ); ?></span>
							<?php if ( ! empty( $data[ Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ] ) ): ?>
								<div class="ga_warning">
									<strong><?php _e( 'Notice' ) ?></strong>:&nbsp;<?php _e( 'Please uncheck the "Manually enter Tracking ID" option to authenticate and view statistics.' ); ?>
								</div>
							<?php endif; ?>
						</td>
					<?php endif; ?>

					<?php if ( ! empty( $data['ga_accounts_selector'] ) ): ?>
						<th scope="row"><?php echo _e( 'Google Analytics Account' ) ?>:</th>
						<td><?php echo $data['ga_accounts_selector']; ?></td>
					<?php endif; ?>

				</tr>

				<tr valign="top">

					<th scope="row">
						<div class="checkbox">
							<label class="ga_checkbox_label <?php echo ( ! Ga_Helper::are_terms_accepted() ) ? 'label-grey ga-tooltip' : '' ?>"
								   for="ga_enter_code_manually"> <input
									<?php if ( Ga_Helper::are_terms_accepted() ) : ?>
										onclick="ga_events.click( this, ga_events.codeManuallyCallback( <?php echo Ga_Helper::are_terms_accepted() ? 1 : 0; ?> ) )"
									<?php endif; ?>
										type="checkbox"
									<?php echo ( ! Ga_Helper::are_terms_accepted() ) ? 'disabled="disabled"' : ''; ?>
										name="<?php echo esc_attr( Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ); ?>"
										id="ga_enter_code_manually"
										value="1"
									<?php echo( ( $data[ Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ] || ! Ga_Helper::are_terms_accepted() ) ? 'checked="checked"' : '' ); ?>/>&nbsp;
								<?php _e( 'Manually enter Tracking ID' ) ?>
								<span class="ga-tooltiptext ga-tt-abs"><?php _e( 'Please accept the terms to use this feature' ); ?></span>
							</label>
						</div>
					</th>
					<td></td>
				</tr>
				<tr valign="top"
					id="ga_manually_wrapper" <?php echo( ( $data[ Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ] || ! Ga_Helper::are_terms_accepted() ) ? '' : 'style="display: none"' ); ?> >

					<th scope="row"><?php _e( 'Tracking ID' ) ?>:</th>
					<td>
						<input type="text"
							   name="<?php echo esc_attr( Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME ); ?>"
							   value="<?php echo esc_attr( $data[ Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME ] ); ?>"
							   id="ga_manually_input"/>&nbsp;
						<div class="ga_warning">
							<strong><?php _e( 'Warning' ); ?></strong>:&nbsp;<?php _e( 'If you enter your Tracking ID manually, Analytics statistics will not be shown.' ); ?>
							<br>
							<?php _e( 'We strongly recommend to authenticate with Google using the button above.' ); ?>
						</div>
					</td>

				</tr>

				<tr valign="top">
					<th scope="row">
						<label <?php echo ( ! Ga_Helper::are_terms_accepted() ) ? 'class="label-grey ga-tooltip"' : '' ?>><?php _e( 'Exclude Tracking for Roles' ) ?>
							:
							<span class="ga-tooltiptext ga-tt-abs"><?php _e( 'Please accept the terms to use this feature' ); ?></span>
						</label>
					</th>
					<td>


						<?php
						if ( ! empty( $data['roles'] ) ) {
							$roles = $data['roles'];
							foreach ( $roles as $role ) {

								?>
								<div class="checkbox">
									<label class="ga_checkbox_label <?php echo ( ! Ga_Helper::are_terms_accepted() ) ? 'label-grey ga-tooltip' : ''; ?>"
										   for="checkbox_<?php echo $role['id']; ?>">
										<input id="checkbox_<?php echo $role['id']; ?>" type="checkbox"
											<?php echo ( ! Ga_Helper::are_terms_accepted() ) ? 'disabled="disabled"' : ''; ?>
											   name="<?php echo esc_attr( Ga_Admin::GA_EXCLUDE_ROLES_OPTION_NAME . "[" . $role['id'] . "]" ); ?>"
											   id="<?php echo esc_attr( $role['id'] ); ?>"
											<?php echo esc_attr( ( $role['checked'] ? 'checked="checked"' : '' ) ); ?> />&nbsp;
										<?php echo esc_html( $role['name'] ); ?>
										<span class="ga-tooltiptext"><?php _e( 'Please accept the terms to use this feature' ); ?></span>
									</label>
								</div>
								<?php
							}
						}
						?>

					</td>
				</tr>

			</table>

			<p class="submit">
				<input type="submit" class="button-primary"
					   value="<?php _e( 'Save Changes' ) ?>"/>
			</p>
        </form>
    </div>
</div>