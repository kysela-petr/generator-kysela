<?php

namespace App\Router;

use Nette;

/**
 * Upraveny RouteList pro pouziti zaroven se SectionRoute.
 * Z Nette\Application\Request odstranuje modul z nazvu presenteru,
 * nebot SectionRoute do nazvu pridava modul dynamicky dle vstupnich parametru.
 *
 * @author Petr Hlavac
 */
class RouteList extends \Nette\Application\Routers\RouteList {

	/** @var array */
	private $cachedRoutes;

	/**
	 * Constructs absolute URL from Request object.
	 * @param \Nette\Application\Request $appRequest
	 * @param \Nette\Http\Url $refUrl
	 * @return null|string
	 */
	public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl) {
		$cachedRoutes = $this->getCachedRoutes();
		$presenter = $this->getPresenterName($appRequest);

		if (!isset($cachedRoutes[$presenter])) {
			$presenter = '*';
		}

		foreach ($cachedRoutes[$presenter] as $route) {
			$url = $route->constructUrl($appRequest, $refUrl);
			if ($url !== NULL) {
				return $url;
			}
		}

		return NULL;
	}

	/**
	 * @return array
	 */
	protected function getCachedRoutes() {
		if (!$this->cachedRoutes) {
			$this->cachedRoutes = $this->createCachedRoutes();
		}

		return $this->cachedRoutes;
	}

	/**
	 * @return array
	 */
	protected function createCachedRoutes() {
		$routes = array();
		$routes['*'] = array();

		foreach ($this as $route) {
			$presenters = $route instanceof \Nette\Application\Routers\Route && is_array($tmp = $route->getTargetPresenters())
				? $tmp : array_keys($routes);

			foreach ($presenters as $presenter) {
				if (!isset($routes[$presenter])) {
					$routes[$presenter] = $routes['*'];
				}
				$routes[$presenter][] = $route;
			}
		}

		return $routes;
	}

	/**
	 * @param Nette\Application\Request $appRequest
	 * @return string
	 */
	protected function getPresenterName(Nette\Application\Request $appRequest) {
		return $this->removeModule($appRequest->getPresenterName());
	}

	/**
	 * @param string $presenter
	 * @return string
	 */
	protected function removeModule($presenter) {
		$pos = strpos($presenter, ':');

		if ($pos === false) {
			return $presenter;
		}

		return substr($presenter, $pos + 1);
	}

}
