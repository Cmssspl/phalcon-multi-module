<?php

namespace Admin;

chdir(__DIR__);

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface {
	public function registerAutoloaders() {
		$loader = new \Phalcon\Loader();

		$loader->registerNamespaces(
			array(
				'Admin\Controllers' => 'controllers/',
				'Admin\Models'      => 'models/',
				'Admin\Forms'      	=> 'forms/'
			)
		);

		$loader->register();
	}

	public function registerServices($di) {
		$config = $di->get('config');

		//Registering a dispatcher
		$di->set('dispatcher', function() {
			$dispatcher = new \Phalcon\Mvc\Dispatcher();
			$dispatcher->setDefaultNamespace('Admin\Controllers\\');
			return $dispatcher;
		});

		//konfigutacja router modułu
		if(!empty($config->application->configsDir)) {
			$configPath = $config->application->configsDir.'routing.ini';

			if(file_exists($configPath)) {
				$router = $di->get('router');
				$routers = new \Phalcon\Config\Adapter\Ini($configPath);

				if(!empty($routers)) {
					foreach($routers as $name => $rule) {
						$pattern = '/:module'.$rule->pattern;
						unset($rule->pattern);

						$router->add($pattern, $rule->toArray())->setName($name);
					}
				}

				$router->handle();
			}
		}

		//Registering the view component
		$di->set('view', function() use ($config) {
			$view = new \Phalcon\Mvc\View();
			$view->setViewsDir($config->application->viewsDir);
			$view->registerEngines(array(
				'.volt' => function($view, $di) use ($config) {
					$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

					$volt->setOptions(array(
						'compiledPath' 		=> $config->application->cacheDir.$config->view->cacheDir,
						'compiledExtension' => $config->view->extension
					));

					return $volt;
				},
				'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
			));

			return $view;
		});
	}

}