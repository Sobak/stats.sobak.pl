<?php
@set_time_limit(0);
$time_start = microtime(true);

if (PHP_SAPI !== 'cli') {
	die;
}

// Initialize array for database
$database = array();

// Run through all enabled services
require 'config.php';
require 'services/ServiceInterface.php';

date_default_timezone_set($timezone);

foreach ($services as $service => $config) {
	require 'services/Service'.ucfirst($service).'.php';

	$class = 'Service'.ucfirst($service);
	$object = new $class($config);
	$database[$service] = $object->grab($config);
	echo "{$object->title} data grabbed...\n";
}

echo 'done in '.round((microtime(true) - $time_start), 3).' sec.' . "\n";

// Replace existing database with new one
file_put_contents('database.json', json_encode($database));
