<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Database connection settings, defined as arrays, or "groups". If no group
 * name is used when loading the database library, the group named "default"
 * will be used.
 *
 * Each group can be connected to independantly, and multiple groups can be
 * connected at once.
 *
 * Group Options:
 *  show_errors   - Enable or disable database exceptions
 *  benchmark     - Enable or disable database benchmarking
 *  persistent    - Enable or disable a persistent connection
 *  connection    - DSN identifier: driver://user:password@server/database
 *  character_set - Database character set
 *  table_prefix  - Database table prefix
 *  object        - Enable or disable object results
 *  cache         - Enable or disable query caching
 */
$config['default'] = array
(
	'show_errors'   => TRUE,
	'benchmark'     => FALSE,
	'persistent'    => FALSE,
	'connection'    => '{driver}://{user}:{password}@{server}/{database}',
	'character_set' => 'utf8',
	'table_prefix'  => '{prefix}',
	'object'        => TRUE,
	'cache'         => TRUE
);