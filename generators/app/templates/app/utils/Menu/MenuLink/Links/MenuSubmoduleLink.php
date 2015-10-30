<?php

namespace App;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuSubmoduleLink extends Object implements IMenuLink
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
			->get($id, 'section.module, section_id, :menu_has_submodule.submodule');

		return $this->presenterLinkSettingsFactory->create(":{$item->module}:{$item->submodule}:Homepage:", ['section' => $item->section_id]);
	}

}
