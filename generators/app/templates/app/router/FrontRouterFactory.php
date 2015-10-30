<?php
/**
 * @author Martin Kovařčík.
 */

namespace App\Router;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Object;

class FrontRouterFactory extends Object
{
	/**
	 * @return RouteList
	 */
	public function create()
	{
		$router = new RouteList();
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Front:Homepage:default');
		return $router;
	}
}
