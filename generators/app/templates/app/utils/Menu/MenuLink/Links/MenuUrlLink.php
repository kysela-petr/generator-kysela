<?php

namespace App;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuUrlLink extends Object implements IMenuLink
{

	/** @var MenuItemDataSource */
	protected $menuItemDataSource;

	/**
	 * @param \App\MenuItemDataSource $menuItemDataSource
	 */
	function __construct(MenuItemDataSource $menuItemDataSource)
	{
		$this->menuItemDataSource = $menuItemDataSource;
	}

	/**
	 * @param \App\type $id
	 * @return \App\UrlLinkSettings
	 */
	public function create($id)
	{
		return new UrlLinkSettings($this->menuItemDataSource->get($id, 'url')->url);
	}

}
