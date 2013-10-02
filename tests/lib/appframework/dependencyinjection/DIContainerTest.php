<?php

/**
 * ownCloud - App Framework
 *
 * @author Bernhard Posselt
 * @author Morris Jobke
 * @copyright 2012 Bernhard Posselt nukeawhale@gmail.com
 * @copyright 2013 Morris Jobke morris.jobke@gmail.com
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


namespace OC\AppFramework\DependencyInjection;

use \OC\AppFramework\Http\Request;


//require_once(__DIR__ . "/../classloader.php");


class DIContainerTest extends \PHPUnit_Framework_TestCase {

	private $container;

	protected function setUp(){
		$this->container = new DIContainer('name');
		$this->api = $this->getMock('OC\AppFramework\Core\API', array('getTrans'), array('hi'));
	}

	private function exchangeAPI(){
		$this->api->expects($this->any())
				->method('getTrans')
				->will($this->returnValue('yo'));
		$this->container['API'] = $this->api;
	}

	public function testProvidesAPI(){
		$this->assertTrue(isset($this->container['API']));
	}


	public function testProvidesRequest(){
		$this->assertTrue(isset($this->container['Request']));
	}


	public function testProvidesSecurityMiddleware(){
		$this->assertTrue(isset($this->container['SecurityMiddleware']));
	}


	public function testProvidesMiddlewareDispatcher(){
		$this->assertTrue(isset($this->container['MiddlewareDispatcher']));
	}


	public function testProvidesAppName(){
		$this->assertTrue(isset($this->container['AppName']));
	}


	public function testAppNameIsSetCorrectly(){
		$this->assertEquals('name', $this->container['AppName']);
	}


	public function testMiddlewareDispatcherIncludesSecurityMiddleware(){
		$this->container['Request'] = new Request();
		$security = $this->container['SecurityMiddleware'];
		$dispatcher = $this->container['MiddlewareDispatcher'];

		$this->assertContains($security, $dispatcher->getMiddlewares());
	}


	public function testMiddlewareDispatcherDoesNotIncludeTwigWhenTplDirectoryNotSet(){
		$this->container['Request'] = new Request();
		$this->exchangeAPI();
		$dispatcher = $this->container['MiddlewareDispatcher'];

		$this->assertEquals(1, count($dispatcher->getMiddlewares()));
	}

}
