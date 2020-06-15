<?php
// force UTF-8 Ø
if (!defined('WEBPATH'))
	die();
if (function_exists('printSlideShow')) {
	?>
	<!DOCTYPE html>
	<html<?php i18n::htmlLanguageCode(); ?>>
		<head>

			<?php
			npgFilters::apply('theme_head');

			scriptLoader($basic_CSS);
			scriptLoader(dirname(dirname($basic_CSS)) . '/common.css');
			?>
		</head>

		<body>
			<?php
			npgFilters::apply('theme_body_open');
			switch (getOption('Theme_colors')) {
				case 'light':
				case 'sterile-light':
					$class = 'slideshow_light';
					break;
				case 'dark':
				case 'sterile-dark':
				default:
					$class = 'slideshow_dark';
					break;
			}
			?>
			<div id="slideshowpage" class="<?php echo $class; ?>">
				<?php
				printSlideShow(true, true);
				?>
			</div>
	</body>
	<?php npgFilters::apply('theme_body_close'); ?>
	</html>
	<?php
} else {
	include(CORE_SERVERPATH . '404.php');
}
?>