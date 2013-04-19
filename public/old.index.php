<?php

chdir(dirname(__DIR__));

try {
    //tworzy di
    $di = new Phalcon\DI\FactoryDefault();

    //pobiera główny config
    $config = new \Phalcon\Config\Adapter\Ini('app/configs/application.ini');
    $di->set('config', $config);

    //rejestracja autoloadera
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        $config->application->pluginsDir,
        $config->application->libraryDir,
        $config->application->controllersDir,
        $config->application->modelsDir
    ))->register();

    //uruchomienie sesii
    $di->set('session', function() {
        $session = new Phalcon\Session\Adapter\Files();
        $session->start();

        return $session;
    });

    //ustawienie bazy danych
    $di->set('db', function() use ($config) {
        $db =  new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            'host' => $config->database->host,
            'username' => $config->database->user,
            'password' => $config->database->pass,
            'dbname' => $config->database->name
        ));

        return $db;
    });

	$di->set('dispatcher', function() use ($di) {
		//Obtain the standard eventsManager from the DI
		$eventsManager = $di->getShared('eventsManager');

		//Instantiate the Security plugin
		$auth = new Auth($di);

		//Listen for events produced in the dispatcher using the Security plugin
		$eventsManager->attach('dispatch', $auth);

		$dispatcher = new Phalcon\Mvc\Dispatcher();

		//Bind the EventsManager to the Dispatcher
		$dispatcher->setEventsManager($eventsManager);

		return $dispatcher;
	});

    //router
	$di->set('router', function() use($config) {
		$router = new \Phalcon\Mvc\Router();
		$router->clear();
        $router->removeExtraSlashes(true);

        if(!empty($config->application->configsDir)) {
            $routerConfig = $config->application->configsDir.'/routing.ini';
        }

        if(!empty($routerConfig) && file_exists($routerConfig)) {
            $routingRules = new \Phalcon\Config\Adapter\Ini($routerConfig);

            if(!empty($routingRules)) {
                foreach($routingRules as $name => $rules) {
                    $router->add(
                        $rules->pattern,
                        array(
                            'controller' => $rules->controller,
                            'action'     => $rules->action
                        )
                    )->setName($name);
                }
            }
        }

        //return $router->handle();
        return $router;
    });//end

    //inicjacja widoku
    $di->set('view', function() use ($config) {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir($config->application->viewsDir);

        return $view;
    });

    //ustawienie aplikacji
    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);

    echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
    echo 'PhalconException: ', $e->getMessage();
}