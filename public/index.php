<?php

chdir(dirname(__DIR__));

//tworzy di
$di = new \Phalcon\DI\FactoryDefault();

//pobiera główny config
$config = new \Phalcon\Config\Adapter\Ini('configs/application.ini');
$di->set('config', $config);$loader = new \Phalcon\Loader();

//uruchomienie sesii
$di->setShared('session', function() {
	$session = new Phalcon\Session\Adapter\Files();
	$session->start();

	return $session;
});

//ustawienie bazy danych
$di->setShared('db', function() use ($config) {
	$db =  new \Phalcon\Db\Adapter\Pdo\Mysql(array(
		'host' => $config->database->host,
		'username' => $config->database->user,
		'password' => $config->database->pass,
		'dbname' => $config->database->name
	));

	return $db;
});

//routeing
$di->setShared('router', function () use($config) {
	$router = new \Phalcon\Mvc\Router();
	$router->clear();
	$router->removeExtraSlashes(true);

	if(!empty($config->application->configsDir)) {
		$routerConfig = $config->application->configsDir.'/routing.ini';
	}

	$router->setDefaultModule("site");

	if(!empty($routerConfig) && file_exists($routerConfig)) {
		$routingRules = new \Phalcon\Config\Adapter\Ini($routerConfig);

		if(!empty($routingRules)) {
			foreach($routingRules as $name => $rule) {
				$pattern = $rule->pattern;
				unset($rule->pattern);

				$router->add($pattern, $rule->toArray())->setName($name);
			}
		}
	}

	return $router;
});

$di->setShared('flash', function() {
	return new \Phalcon\Flash\Session();
});

try {
	//Create an application
	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);

	// Register the installed modules
	$modules = array();

	if(!empty($config->modules)) {
		foreach($config->modules as $moduleName => $module) {
			$modules[$moduleName] = array(
				'className' => $module->className,
				'path'      => $config->application->modulesDir.$module->path
			);
		}
	}

	$application->registerModules($modules);

	//Handle the request
	echo $application->handle()->getContent();
} catch(Phalcon\Exception $e){
	echo $e->getMessage();
}