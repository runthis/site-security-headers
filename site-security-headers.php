<?php
/**
 * @package Site_Security_Headers
 * @version 1.0.1
 *
 * Plugin Name: Site Security Headers
 * Description: Check your application for security headers
 * Author: Robert Miller
 * Author URI: https://www.robertjessemiller.com/
 * Version: 1.0.1
 */

use SiteSecurityHeaders\Controllers\Application;

require_once 'autoload.php';

global $wpdb;

$Application = new Application($wpdb);

define('SiteSecurityHeaders\CONTROLLERS', __DIR__ . '/App/Controllers/');
define('SiteSecurityHeaders\DATABASE', __DIR__ . '/App/Database/');
define('SiteSecurityHeaders\MODELS', __DIR__ . '/App/Models/');
define('SiteSecurityHeaders\VIEWS', __DIR__ . '/Views/');

register_activation_hook(__FILE__, [$Application, 'activate']);
register_deactivation_hook(__FILE__, [$Application, 'deactivate']);
add_action('admin_menu', [$Application, 'add_menu']);

wp_enqueue_style('SiteSecurityHeadersHome', plugins_url('/public/css/home.css', __FILE__));
