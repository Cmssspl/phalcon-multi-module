<?php

namespace Admin;

chdir(__DIR__);

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface {
	public function registerAutoloaders() { }

	public function registerServices($di) {
		$config = $di->get('config');

		$loader = new \Phalcon\Loader();

		$namespacesModule = array();

		if(!empty($config->namespaceModule)) {
			foreach($config->namespaceModule as $namespaceModule) {
				$namespacesModule[__NAMESPACE__.'\\'.$namespaceModule->name] = $namespaceModule->path;
			}

			$loader->registerNamespaces($namespacesModule);

			$loader->register();
		}

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
				$config->view->volt->extension => function($view, $di) use ($config) {
					$volt = new $config->view->volt->engine($view, $di);

					$volt->setOptions(array(
						'compiledPath' 		=> $config->application->cacheDir.$config->view->volt->cacheDir,
						'compiledExtension' => $config->view->volt->compiledExtension
					));

					return $volt;
				},
				$config->view->php->extension => $config->view->php->engine
			));

			return $view;
		});
	}

}