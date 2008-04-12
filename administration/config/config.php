<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Base path of the web site. If this includes a domain, (eg, "localhost/kohana/") then
 * a full URL will be used (eg, "http://localhost/kohana/"). If it only includes the path, 
 * and a site_protocol is specified, the domain will be auto-detected.
 */
$config['site_domain'] = '/S7Ncms';

/**
 * Force a default protocol to be used by the site. If no site_protocol is specified, then the 
 * current protocol is used, or when possible, only an absolute path (with no protocol/domain) 
 * is used.
 */
$config['site_protocol'] = '';

/**
 * Name of the front controller for this application. Default: index.php
 *
 * This can be removed by using URL rewriting.
 */
$config['index_page'] = 'admin';

/**
 * Fake file extension that will be added to all generated URLs. Example: .html
 */
$config['url_suffix'] = '';

/**
 * Enable or disable gzip output compression. This can dramatically decrease
 * server bandwidth usage, at the cost of slightly higher CPU usage. Set to
 * the compression level (1-9) that you want to use, or FALSE to disable.
 *
 * Do not enable this option if you are using output compression in php.ini!
 */
$config['output_compression'] = FALSE;

/**
 * Enable or disable global XSS filtering of GET, POST, and SERVER data. This
 * option also accepts a string to specify a specific XSS filtering tool.
 */
$config['global_xss_filtering'] = FALSE;

/**
 * Enable or disable dynamic setting of configuration options. By default, all
 * configuration options are read-only.
 */
$config['allow_config_set'] = TRUE;

/**
 * Enable or display displaying of Kohana error pages. This will not affect
 * logging. Turning this off will disable ALL error pages.
 */
$config['display_errors'] = TRUE;

/**
 * Filename prefixed used to determine extensions. For example, an
 * extension to the Controller class would be named MY_Controller.php.
 */
$config['extension_prefix'] = 'MY_';

/**
 * Additional resource paths, or "modules". Each path can either be absolute
 * or relative to the docroot. Modules can include any resource that can exist
 * in your application directory, configuration files, controllers, views, etc.
 */
$config['modules'] = array
(
	'shared', // Shared stuff for S7Ncms
	MODPATH.'auth',   // Authentication
	MODPATH.'forge',  // Form generation
	// MODPATH.'kodoc',  // Self-generating documentation
	// MODPATH.'media',  // Media caching and compression
);