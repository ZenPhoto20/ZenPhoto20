<?php
// force UTF-8 Ø
if (!defined('WEBPATH'))
	die();
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
		<?php npgFilters::apply('theme_body_open'); ?>
		<div id="main">
			<div id="gallerytitle">
				<?php
				if (getOption('Allow_search')) {
					printSearchForm();
				}
				?>
				<h2>
					<span>
						<?php printHomeLink('', ' | '); ?>
						<a href="<?php echo html_encode(getGalleryIndexURL()); ?>" title="<?php echo gettext('Daily summary'); ?>"><?php printGalleryTitle(); ?></a> |
						<?php printParentBreadcrumb(); ?>
					</span>
					<?php echo gettext('Daily summary'); ?>
				</h2>
			</div>
			<div id="padbox">
				<?php
				include getPlugin('/daily-summary/daily-summary_content.php');
				?>
			</div>
		</div>
		<div id="credit">
			<?php
			if (function_exists('printFavoritesURL')) {
				printFavoritesURL(NULL, '', ' | ', '<br />');
			}
			printCustomPageURL(gettext("Archive View"), "archive", '', '', ' | ');
			printSoftwareLink();
			if (function_exists('printUserLogin_out')) printUserLogin_out(" | ");
			?>
		</div>
	</body>
	<?php npgFilters::apply('theme_body_close'); ?>
</html>