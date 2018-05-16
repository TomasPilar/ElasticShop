<?php declare(strict_types=1);

define('SNIPPET_DIR', __DIR__ . '/../.snippets');

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

//$configurator->setDebugMode(FALSE);

$configurator->enableTracy(__DIR__ . '/../log');
$configurator->setTempDirectory(__DIR__ . '/../temp');

if (php_sapi_name() === 'cli') {
	restore_exception_handler();
	restore_error_handler();
}

$configurator->addParameters([
	// fix for bin scripts miss location
	'wwwDir' => __DIR__ . '/../www',
]);

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
