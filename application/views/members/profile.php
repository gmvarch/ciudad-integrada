<?php
/**
 * Site view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
?>
			<div class="bg">
				<h2><?php echo Kohana::lang('ui_admin.my_profile');?></h2>
				<?php print form::open(); ?>
				<div class="report-form">
					<?php
					if ($form_error) {
					?>
						<!-- red-box -->
						<div class="red-box">
							<h3><?php echo Kohana::lang('ui_main.error');?></h3>
							<ul>
							<?php
							foreach ($errors as $error_item => $error_description)
							{
								print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
							}
							?>
							</ul>
						</div>
					<?php
					}

					if ($form_saved) {
					?>
						<!-- green-box -->
						<div class="green-box">
							<h3><?php echo Kohana::lang('ui_main.profile_saved');?></h3>
						</div>
					<?php
					}
					?>
					<div class="head">
						<input type="submit" class="save-rep-btn" value="<?php echo Kohana::lang('ui_admin.save_settings');?>" />
					</div>
					<!-- column -->
					<div class="sms_holder">

						<?php Event::run('ui_admin.profile_shown'); ?>					

						<div class="form-group">
							<label class="col-sm-3 control-label">
								<?php echo Kohana::lang('ui_main.current_password'); ?>
								<span class="required">*</span></label>
							<div class="col-sm-9">
								<?php print form::password('current_password', '', ' class="text form-control"'); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">
								<?php echo Kohana::lang('ui_main.full_name'); ?>
								<span class="required">	*</span></label>
							<div class="col-sm-9">
								<?php print form::input('name', $form['name'], ' class="text form-control"'); ?>
							</div>
						</div>


						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo Kohana::lang('ui_main.email'); ?>
							<span class="required">*</span></label>
							<div class="col-sm-9">
								<?php print form::input('email', $form['email'], ' class="text form-control "'); ?>
							</div>
						</div>


						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo Kohana::lang('ui_main.new_password'); ?>
							<span class="required">*</span></label>
							<div class="col-sm-9">
								<?php print form::password('new_password', $form['new_password'], ' class="form-control text"'); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo Kohana::lang('ui_main.password_again'); ?></label>
							<div class="col-sm-9">
								<?php print form::password('password_again', $form['password_again'], ' class="form-control text"'); ?>
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo Kohana::lang('ui_main.receive_notifications'); ?></label>
							<div class="col-sm-9">
								<?php print form::dropdown('notify', $yesno_array, $form['notify']); ?>
							</div>
						</div>	

						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo Kohana::lang('ui_main.public_profile_url'); ?></label>
							<div class="col-sm-9">
								<span style="float:left;"><?php echo url::site().'profile/user/'; ?></span>
								<?php print form::input('username', $form['username'], ' class="text form-control short3"'); ?>
							</div>
						</div>	
						
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo Kohana::lang('ui_main.public_profile'); ?></label>
							<div class="col-sm-9">
								<?php
								print form::label('profile_public', Kohana::lang('ui_main.on').': ');
								print form::radio('public_profile', '1', $profile_public, 'id="profile_public"').'&nbsp;&nbsp;&nbsp;&nbsp;';
								print form::label('profile_private', Kohana::lang('ui_main.off').': ');
								print form::radio('public_profile', '0', $profile_private, 'id="profile_private"').'<br />';
								?>
							</div>
						</div>	

						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo Kohana::lang('ui_main.change_picture'); ?></label>
							<div class="col-sm-9">
								<a href="http://www.gravatar.com/" target="_blank"><img src="<?php echo members::gravatar($form['email']); ?>" width="80" border="0" /></a>
							</div>
						</div>	

						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo Kohana::lang('ui_main.profile_color'); ?></label>
							<div class="col-sm-9">
								<?php print form::input('color', $form['color'], ' class="text"'); ?>
								<script type="text/javascript" charset="utf-8">
									$(document).ready(function() {
										$('#color').ColorPicker({
											onSubmit: function(hsb, hex, rgb) {
												$('#color').val(hex);
											},
											onChange: function(hsb, hex, rgb) {
												$('#color').val(hex);
											},
											onBeforeShow: function () {
												$(this).ColorPickerSetColor(this.value);
											}
										})
										.bind('keyup', function(){
											$(this).ColorPickerSetColor(this.value);
										});
									});
								</script>
							</div>
						</div>	


					</div>

					<div class="simple_border"></div>

					<input type="submit" class="save-rep-btn" value="<?php echo Kohana::lang('ui_admin.save_settings');?>" />
				</div>
				<?php print form::close(); ?>
			</div>
