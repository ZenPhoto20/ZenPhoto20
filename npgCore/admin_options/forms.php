<?php
/*
 * Guts of the search options tab
 */
$optionRights = OPTIONS_RIGHTS;

function saveOptions() {
	global $_gallery;

	if (isset($_POST['revert'])) {
		unlink(USER_PLUGIN_SERVERPATH . 'forms/mailForm.htm');
	} else {
		$form = $_POST['email_form'];
		$form = str_replace('href="' . FULLWEBPATH, 'href="%WEBPATH%', $form);
		$form = str_replace('src="' . $_gallery->getSiteLogo(FULLWEBPATH) . '"', 'src="%LOGO%"', $form);

		$formFile = USER_PLUGIN_SERVERPATH . 'forms/mailForm.htm';
		if (!is_dir(USER_PLUGIN_SERVERPATH . 'forms')) {
			mkdir(USER_PLUGIN_SERVERPATH . 'forms');
		}

		file_put_contents($formFile, $form);
	}

	$returntab = "&tab=forms";

	return array($returntab, NULL, NULL, NULL, NULL);
}

function getOptionContent() {
	global $_gallery;
	?>
	<div id="tab_forms" class="tabbox">
		<form class="dirtylistening" onReset="toggle_passwords('', false);
					setClean('form_options');" id="form_options" action="?action=saveoptions" method="post" autocomplete="off" >
					<?php XSRFToken('saveoptions'); ?>
			<input	type="hidden" name="saveoptions" value="forms" />

			<p>
				<?php
				applyButton();
				resetButton();
				?>
			</p>

			<?php
			$formFile = getPlugin('forms/mailForm.htm');
			if ($formFile) {
				$form = file_get_contents($formFile);
				$form = preg_replace('~\<\!--.*--\>~mUs', '', $form); // remove the comments

				$form = str_replace('href="%WEBPATH%', 'href="' . FULLWEBPATH, $form);
				$form = str_replace('src="%LOGO%"', 'src="' . $_gallery->getSiteLogo() . '"', $form);
			}
			echo gettext('E-mail form');
			?>
			<div style="width:800px;">
				<textarea name="email_form" class="texteditor" cols="<?php echo TEXTAREA_COLUMNS; ?>"	style="width: 800px" rows="30">
					<?php echo $form; ?>
				</textarea>
				<?php
				if (basename(dirname(dirname($formFile))) == USER_PLUGIN_FOLDER) {
					?>
					<input type="checkbox" name="revert"><?php echo gettext('Restore default form'); ?>
					<?php
				}
				?>
			</div>

			<p>
				<?php
				applyButton();
				resetButton();
				?>
			</p>

		</form>
		<br clear="all">
	</div>
	<!-- end of tab-search div -->
	<?php
}
