<?php

/**
 *
 * The plugin provides login to ZenPhoto20 via a Twitter OAuth protocol.
 *
 *
 * You must configure the plugin with your Twitter Developer credentials. You will
 * need an <b><i>Client ID</i></b> as well as an <b><i>Client Secret</i></b>.
 * You can obtain these from
 * {@link https://apps.twitter.com/ Twitter Application Management}
 *
 * You will need to set a <i>Callback URL</i> that
 * points to <var>%FULLWEBPATH%/%ZENFOLDER%/%PLUGIN_FOLDER%/twitterLogin/twitter.php</var>
 *
 * For Twitter to return the user's e-mail address you will need to go to the permissions tab
 * for the app you defined above and check <i>Request email addresses from users</i> under
 * <b>Additional Permissions</b>. Note that your site must have a <i>Terms and Conditions</i> and a <i>Privacy Statement</i>
 * and you will need to provide those URL to Twitter.
 * The e-mail address supplied by Twitter OAuth will become the user's <i>user ID</i>
 * if present. If no e-mail address is supplied with the login, a user ID will be created
 * from the user's Twitter ID. If this <i>user ID</i> does not exist as a ZenPhoto20 user,
 * a new user will be created. The user will be assigned to the group indicated by
 * the plugin's options. If <var>Notify</var> option is checked an e-mail will be sent to
 * the site administrator informing him of the new user.
 *
 * You can place a login button on your webpage by calling the function <var>twitterLogin::loginButton();</var>
 *
 * @author Stephen Billard (sbillard)
 * @Copyright 2017 by Stephen L Billard for use in {@link https://github.com/ZenPhoto20/ZenPhoto20 ZenPhoto20}
 *
 * @package plugins
 * @subpackage users
 */
$plugin_is_filter = 900 | CLASS_PLUGIN;
$plugin_description = gettext("Handles logon via the user's <em>Twitter</em> account.");
$plugin_author = "Stephen Billard (sbillard)";
$plugin_notice = sprintf(gettext('The PHP <var>curl</var> module is required for this plugin.'));
$plugin_disable = (extension_loaded('curl')) ? false : gettext('The PHP Curl is required.');

require_once(SERVERPATH . '/' . ZENFOLDER . '/' . PLUGIN_FOLDER . '/common/oAuth/oAuthLogin.php');

$option_interface = 'twitterLogin';

if ($plugin_disable) {
	enableExtension('twitterLogin', 0);
} else {
	zp_register_filter('alt_login_handler', 'twitterLogin::alt_login_handler');
	zp_register_filter('edit_admin_custom_data', 'twitterLogin::edit_admin');
}
zp_session_start();

/**
 * Option class
 *
 */
class twitterLogin extends oAuthLogin {

	/**
	 * Option instantiation
	 */
	function __construct() {
		parent::__construct();
		setOptionDefault('tweet_news_consumer', NULL);
		setOptionDefault('tweet_news_consumer_secret', NULL);
	}

	/**
	 * Provides option list
	 */
	function getOptionsSupported() {
		$options = array(
				gettext('Consumer key') => array('key' => 'tweet_news_consumer', 'type' => OPTION_TYPE_TEXTBOX,
						'order' => 11,
						'desc' => gettext('This <code>tweet_news</code> app for this site needs a <em>consumer key</em>, a <em>consumer key secret</em>, an <em>access token</em>, and an <em>access token secret</em>.') . '<p class="notebox">' . gettext('Get these from <a href="http://dev.twitter.com/">Twitter developers</a>') . '</p>'),
				gettext('Secret') => array('key' => 'tweet_news_consumer_secret', 'type' => OPTION_TYPE_TEXTBOX,
						'order' => 12,
						'desc' => gettext('The <em>secret</em> associated with your <em>consumer key</em>.'))
		);
		$options = array_merge($options, parent::getOptionsSupported());
		return $options;
	}

	/**
	 * Handles the custom option $option
	 * @param $option
	 * @param $currentValue
	 */
	function handleOption($option, $currentValue) {

	}

}

?>