<?php
/**
 * @author Martin Kovařčík.
 */

namespace App;

use Nette\Database\Table\Selection;
use Nette\Object;
use Nette\Utils\Strings;

class MenuPresenterLink extends Object implements IMenuLink
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
				$pageKey = ':menu_has_presenter.presenter.code';

				$context->select('section.module, menu.section_id, menu.name');
				$context->select("$pageKey");

				$context->where("$pageKey IS NOT NULL");
			});

		$module = Strings::capitalize($item->module);
		$presenter = Strings::firstUpper($item->code);

		return $this->presenterLinkSettingsFactory->create(":$module:$presenter:", []);
	}

}
