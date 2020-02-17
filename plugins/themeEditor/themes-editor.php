<?php
/**
 * Theme file editor
 *
 * @package admin/themeEditor
 * @author Ozh
 */
// force UTF-8 Ø

define('OFFSET_PATH', 1);
require_once(file_get_contents(dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . '/core-locator.npg') . "admin-globals.php");

admin_securityChecks(THEMES_RIGHTS, currentRelativeURL());

if (!isset($_GET['theme'])) {
	header("Location: " . getAdminLink('admin-tabs/themes.php'));
	exit();
}

$ok_extensions = array('css', 'php', 'js', 'txt', 'inc');

function isTextFile($file) {
	global $ok_extensions;
	$ext = strtolower(getSuffix($file));
	return (in_array($ext, $ok_extensions) );
}

$messages = $file_to_edit = $file_content = null;
$what = 'edit';
$themes = $_gallery->getThemes();
$theme = basename(sanitize($_GET['theme']));

if (themeIsEditable($theme)) {
	$themedir = SERVERPATH . '/themes/' . internalToFilesystem($theme);
	$themefiles = listDirectoryFiles($themedir);
	$themefiles_to_ext = array();

	foreach ($themefiles as $file) {
		if (isTextFile($file)) {
			$themefiles_to_ext[getSuffix($file)][] = $file; // array(['php']=>array('file.php', 'image.php'),['css']=>array('style.css'))
		} else {
			unset($themefiles[$file]); // $themefile will eventually have all editable files and nothing else
		}
	}
	if (isset($_GET['file'])) {
		if (!in_array($themedir . '/' . $_GET['file'], $themefiles)) {
			$messages['errorbox'][] = gettext('Cannot edit this file!');
		}
		$file_to_edit = str_replace('\\', '/', SERVERPATH . '/themes/' . internalToFilesystem($theme) . '/' . sanitize($_GET['file']));
	}
	// Handle POST that updates a file
	if (isset($_POST['action']) && $_POST['action'] == 'edit_file' && $file_to_edit && !isset($messages['errorbox'])) {
		XSRFdefender('edit_theme');
		$file_content = sanitize($_POST['newcontent'], 0);
		$theme = urlencode($theme);
		if (is_writeable($file_to_edit)) {
			//is_writable() not always reliable, check return value. see comments @ http://uk.php.net/is_writable
			$f = @fopen($file_to_edit, 'w+');
			if ($f !== FALSE) {
				@fwrite($f, $file_content);
				fclose($f);
				clearstatcache();
				$messages['messagebox fade-message'][] = gettext('File updated successfully');
			} else {
				$messages['messagebox fade-message'][] = gettext('Could not write file. Please check its write permissions');
			}
		} else {
			$messages['errorbox'][] = gettext('Could not write file. Please check its write permissions');
		}
	}

	// Get file contents
	if ($file_to_edit && !isset($messages['errorbox'])) {
		$file_content = @file_get_contents($file_to_edit);
		$file_content = html_encode($file_content);
		$what = 'edit»' . basename($file_to_edit);
	}
} else {
	$messages['errorbox'][] = gettext('Cannot edit this theme!');
	$themefiles_to_ext = array();
}
printAdminHeader('themes', $what);
echo "\n</head>";
echo "\n<body>";
printLogoAndLinks();
echo "\n" . '<div id="main">';
printTabs();
echo "\n" . '<div id="content">';
?>


<h1><?php echo gettext('Theme File Editor'); ?></h1>
<h2><?php echo html_encode($themes[$theme]['name']); ?></h2>
<?php
if (!empty($messages)) {
	foreach ($messages as $type => $messageList) {
		echo '<div class="' . $type . '">';
		foreach ($messageList as $message) {
			echo "<h2>$message</h2>";
		}
		echo '</div>';
	}
}
?>

<p>
	<?php backButton(array('buttonTitle' => gettext('Back to the theme list'), 'buttonLink' => getAdminLink('admin-tabs/themes.php'))); ?>
</p>
<br class="clearall" />
<div id="theme-editor">
	<?php
	if (!empty($themefiles_to_ext)) {
		?>
		<div id="files">
			<?php
			foreach ($themefiles_to_ext as $ext => $files) {
				echo '<h2 class="h2_bordered">';
				switch ($ext) {
					case 'php':
						echo gettext('Theme template files (.php)');
						break;
					case 'js':
						echo gettext('JavaScript files (.js)');
						break;
					case 'css':
						echo gettext('Style sheets (.css)');
						break;
					default:
						echo gettext('Other text files');
				}
				echo '</h2><ul>';
				foreach ($files as $file) {
					$file = str_replace($themedir . '/', '', $file);
					echo "<li><a title='" . gettext('Edit this file') . "' href='?theme=$theme&file=$file'>$file</a></li>";
				}
				echo '</ul>';
			}
			?>
		</div>
		<?php
	}
	?>

	<?php
	if ($file_to_edit) {
		?>
		<div id="editor">
			<h2 class="h2_bordered"><?php echo sprintf(gettext('File <tt>%s</tt> from theme %s'), html_encode(sanitize($_GET['file'])), html_encode($themes[$theme]['name'])); ?></h2>
			<?php
			if (!isset($messages['errorbox'])) {
				?>
				<form class="dirtylistening" onReset="setClean('themeedit_form');" id="themeedit_form" method="post" action="">
					<?php XSRFToken('edit_theme'); ?>
					<p><textarea cols="70" rows="35" name="newcontent" id="newcontent"><?php echo $file_content ?></textarea></p>
					<input type="hidden" name="action" value="edit_file"/>
					<p>
						<?php
						applyButton();
						resetButton();
						?>
					</p>
					<br class="clearall" />
				</form>
				<?php
			}
			?>
		</div>

		<?php
	} else if (!empty($themefiles_to_ext)) {
		?>
		<div id="editor">
			<p><?php echo gettext('Select a file to edit from the list on your right hand. Keep in mind that you can <strong>break everything</strong> if you are not careful when updating files.'); ?></p>
		</div>
		<?php
	}
	?>

</div> <!-- theme-editor -->

<?php
echo "\n" . '</div>'; //content
printAdminFooter();
echo "\n" . '</div>'; //main
echo "\n</body>";
echo "\n</html>";
?>



