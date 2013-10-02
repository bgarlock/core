<?php
/**
 * ownCloud
 *
 * @author Thomas Müller
 * @copyright 2013 Thomas Müller deepdiver@owncloud.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCP\AppFramework;


/**
 * Class App
 * @package OCP\AppFramework
 *
 * Any application must inherit this call - all controller instances to be used are
 * to be registered using IContainer::registerService
 */
class App {
	public function __construct($appName) {
		$this->container = new \OC\AppFramework\DependencyInjection\DIContainer($appName);
	}

	private $container;

	/**
	 * @return IAppContainer
	 */
	public function getContainer() {
		return $this->container;
	}

	/**
	 * This function is called by the routing component to fire up the frameworks dispatch mechanism.
	 *
	 * Example code in routes.php of the task app:
	 * $this->create('tasks_index', '/')->get()->action(
	 *		function($params){
	 *			$app = new TaskApp();
	 *			$app->dispatch('PageController', 'index', $params);
	 *		}
	 *	);
	 *
	 *
	 * Example for for TaskApp implementation:
	 * class TaskApp extends \OCP\AppFramework\App {
	 *
	 *		public function __construct(){
	 *			parent::__construct('tasks');
	 *
	 *			$this->getContainer()->registerService('PageController', function(IAppContainer $c){
	 *				$a = $c->query('API');
	 *				$r = $c->query('Request');
	 *				return new PageController($a, $r);
	 *			});
	 *		}
	 *	}
	 *
	 * @param string $controllerName the name of the controller under which it is
	 *                               stored in the DI container
	 * @param string $methodName the method that you want to call
	 * @param array $urlParams an array with variables extracted from the routes
	 */
	public function dispatch($controllerName, $methodName, array $urlParams) {
		\OC\AppFramework\App::main($controllerName, $methodName, $urlParams, $this->container);
	}
}
