<?php

namespace App\Router;

use Nette\Application\Routers\CliRouter;
use Nette\Application\Routers\RouteList;
use Nette\Object;

/**
 * Tovarna na routy aplikace
 */
class RouterFactory extends Object
{

	/** @var AdminRouterFactory */
	protected $adminRouterFactory;

	/** @var \App\Router\FrontRouterFactory */
	private $frontRouterFactory;

	/**
	 * @param \App\Router\AdminRouterFactory $adminRouterFactory
	 * @param \App\Router\FrontRouterFactory $frontRouterFactory
	 */
	function __construct(AdminRouterFactory $adminRouterFactory, FrontRouterFactory $frontRouterFactory)
	{
		$this->adminRouterFactory = $adminRouterFactory;
		$this->frontRouterFactory = $frontRouterFactory;
	}

	/**
	 * @param bool $consoleMode
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter($consoleMode)
	{
		if ($consoleMode) {
			return new CliRouter();
		}

		$router = new RouteList;
		$router[] = $this->adminRouterFactory->create();
		$router[] = $this->frontRouterFactory->create();

		return $router;
	}

}
