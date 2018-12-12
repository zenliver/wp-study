<?php

/**
 * Runs on Admin area of the plugin.
 *
 * @package    All In One WP Solution
 * @subpackage Templates
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

/**
 * Maintenance mode template
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">	
	<link rel="stylesheet" href="<?php echo plugins_url( 'css/maintenance.min.css', dirname( __FILE__ ) ); ?>">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700" rel="stylesheet">

	<title><?php echo get_option('aiows_plugin_global_options')['aiows_plugin_maintenance_custom_title']; ?></title>
</head>
<body>
    <div class="container">
        <h1><?php echo get_option('aiows_plugin_global_options')['aiows_plugin_maintenance_header']; ?></h1><p>
<h2><?php echo get_option('aiows_plugin_global_options')['aiows_plugin_maintenance_body']; ?></h2></p>
    </div>
</body>
</html>
