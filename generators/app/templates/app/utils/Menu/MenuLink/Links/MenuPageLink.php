<?php

namespace App;

use Nette\Database\Table\Selection;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * Link staticke stranky
 * @author Petr Hlavac
 */
class MenuPageLink extends Object implements IMenuLink
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
			->get($id, function (Selection $context) {
				$pageKey = ':menu_has_page.page.id';

				$context->select('section.module, menu.section_id, menu.name');
				$context->select("$pageKey");

				$context->where("$pageKey IS NOT NULL");
			});

		$module = Strings::capitalize($item->module);
		return $this->presenterLinkSettingsFactory->create(":$module:Page:default", ['id' => $item->id]);
	}

}
