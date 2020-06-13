<?php
// force UTF-8 Ø
if (!defined('WEBPATH'))
	die();
if (function_exists('printRegistrationForm')) {
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml"<?php i18n::htmlLanguageCode(); ?>>
		<head>
			<?php include("inc-head.php"); ?>
		</head>
		<body>
			<?php include("inc-header.php"); ?>
			<div class="stage" <?php echo $stageStyle; ?>>
				<div class="title border colour" <?php echo $titleStyle; ?>>
					<?php echo gettext('User Registration'); ?>
				</div>
				<div class="content border colour">
					<div class="contactbox" <?php echo $commentStyle; ?>>
						<?php printRegistrationForm(NULL, false); ?>
					</div>
				</div>
			</div>
			<?php include("inc-footer.php"); ?>
		</body>
		<?php npgFilters::apply('theme_body_close'); ?>
	</html>
	<?php
} else {
	include(__DIR__ . '/404.php');
}
?>