<?php

namespace App;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuSectionLink extends Object implements IMenuLink
{

	/** @var MenuItemDataSource */
	protected $menuItemDataSource;

	/** @var IPresenterLinkSettingsFactory */
	protected $presenterLinkSettingsFactory;

	/**
	 * @param \App\MenuItemDataSource $menuItemDataSource
	 * @param \App\IPresenterLinkSettingsFactory $presenterLinkSettingsFactory
	 */
	function __construct(MenuItemDataSource $menuItemDataSource, IPresenterLinkSettingsFactory $presenterLinkSettingsFactory)
	{
		$this->menuItemDataSource = $menuItemDataSource;
		$this->presenterLinkSettingsFactory = $presenterLinkSettingsFactory;
	}

	/**
	 * @param \App\type $id
	 * @return \App\PresenterLinkSettings
	 */
	public function create($id)
	{
		$item = $this->menuItemDataSource
			->get($id, ':menu_has_section.section.id');

		return $this->presenterLinkSettingsFactory->create(':Home:Dashboard:Homepage:', ['section' => $item->id]);
	}

}
