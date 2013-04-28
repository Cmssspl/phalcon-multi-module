<?php

namespace Admin;

chdir(__DIR__);

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface {
	public function registerAutoloaders() { }

	public function registerServices($di) {
		//ładowanie configu
		$config = $di->get('config');

		if(!empty($config->application->configsDir)) {
			$configPath = $config->application->configsDir.'module.ini';

			if(file_exists($configPath)) {
				$moduleConfig = new \Phalcon\Config\Adapter\Ini($configPath);

				$config->merge($moduleConfig);
			}
		}

		//loader namespace
		$loader = new \Phalcon\Loader();

		$namespaces = array(
			__NAMESPACE__.'\Controllers' 	=> $config->application->controllersDir,
			__NAMESPACE__.'\Models' 		=> $config->application->modelsDir,
			__NAMESPACE__.'\Forms' 			=> $config->application->formsDir,
			__NAMESPACE__.'\Plugins' 		=> $config->application->pluginsDir,
		);

		if(!empty($config->library)) {
			foreach($config->library as $library) {
				$namespaces[$library->name] = $config->application->libraryDir.$library->path;
			}
		}

		$loader->registerNamespaces($namespaces);

		$loader->register();

		//Registering a dispatcher
		$di->setShared('dispatcher', function() use ($di, $config) {
			//Obtain the standard eventsManager from the DI
			$eventsManager = $di->getShared('eventsManager');

			//Listen for events produced in the dispatcher using the Security plugin
			if(!empty($config->plugins)) {
				foreach($config->plugins as $plugin) {
					if(!empty($plugin->global)) {
						$pluginClass = $plugin->name;
					} else {
						$pluginClass = __NAMESPACE__.'\Plugins\\'.$plugin->name;
					}

					$eventsManager->attach($plugin->trigger, new $pluginClass($di));
				}
			}

			$dispatcher = new \Phalcon\Mvc\Dispatcher();
			$dispatcher->setDefaultNamespace(__NAMESPACE__.'\Controllers');

			//Bind the EventsManager to the Dispatcher
			$dispatcher->setEventsManager($eventsManager);

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

		$di->set('url', function() use ($config){
			$url = new \Phalcon\Mvc\Url();
			$url->setBaseUri('/'.$config->application->baseUrl);

			return $url;
		});

		//Registering the view component
		$di->set('view', function() use ($di ,$config) {
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

			$auth = $di->get('session')->get('auth');

			if ($auth){
				$view->setLayout('login');
			} else {
//				$view->setLayout('logout');
			}

			return $view;
		});
	}

}