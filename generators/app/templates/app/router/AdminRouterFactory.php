<?php
/**
 * Routy pro administraci
 * @author Martin Kovařčík.
 */

namespace App\Router;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Object;

class AdminRouterFactory extends Object
{

	/**
	 * @return RouteList
	 */
	public function create()
	{
		$router = new RouteList('Admin');

		$router[] = new Route('admin/<module>/<presenter>/<action>[/<id>]', [
			'module' => 'Dashboard',
			'presenter' => 'Homepage',
			'action' => 'default',
			'id' => NULL
		]);

		return $router;
	}

}
