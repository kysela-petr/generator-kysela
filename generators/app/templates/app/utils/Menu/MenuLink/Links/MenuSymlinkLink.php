<?php

namespace App;

use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuSymlinkLink extends Object implements IMenuLink {

	/** @var MenuLinkMap */
	protected $menuLinkMap;

	/** @var MenuItemDataSource */
	protected $menuItemDataSource;

	/**
	 * @param \App\MenuLinkMap $menuLinkMap
	 * @param \App\MenuItemDataSource $menuItemDataSource
	 */
	function __construct(MenuLinkMap $menuLinkMap, MenuItemDataSource $menuItemDataSource) {
		$this->menuLinkMap = $menuLinkMap;
		$this->menuItemDataSource = $menuItemDataSource;
	}

	/**
	 * @param \App\type $id
	 * @return \App\ILinkSettings
	 */
	public function create($id) {
		$item = $this->menuItemDataSource
				->get($id, function (Selection $context) {
			$symlinkTable = ':menu_has_symlink.symlink';
			$context->select("$symlinkTable.id, $symlinkTable.type");
			$context->where("$symlinkTable.type != ?", MenuTypeConst::SYMLINK);
			$context->where("$symlinkTable.id IS NOT NULL");
		});

		return $this->menuLinkMap
						->get($item['type'])
						->create($item['id']);
	}

}
