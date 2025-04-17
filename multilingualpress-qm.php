<?php
/**
 * MultilingualPress Query Monitor Integration
 *
 * @package           multilingualpress-qm
 * @author            Bernhard Kau
 * @license           GPLv3
 *
 * @wordpress-plugin
 * Plugin Name:       MultilingualPress Query Monitor
 * Plugin URI:        https://github.com/2ndkauboy/multilingualpress-qm
 * Description:       Add-on to the Query Monitor plugin to show MultilingualPress content translations.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Author:            Bernhard
 * Author URI:        https://kau-boys.com
 * Text Domain:       multilingualpress-qm
 * Network:           true
 * Update URI:        https://github.com/2ndkauboy/multilingualpress-qm/
 * Requires Plugins:  query-monitor, multilingualpress
 */


if ( defined( 'QM_DISABLED' ) && constant( 'QM_DISABLED' ) ) {
	return;
}

add_action(
	'plugins_loaded',
	static function () {
		require_once __DIR__ . '/data/languages_mlp_translations.php';
		require_once __DIR__ . '/collectors/languages_mlp_translations.php';
		require_once __DIR__ . '/output/html/languages_mlp_translations.php';
	}
);
