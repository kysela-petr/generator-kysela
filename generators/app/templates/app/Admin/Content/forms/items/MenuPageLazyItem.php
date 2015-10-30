<?php

namespace Admin\Content;

use Admin\ILazyItem;
use Admin\LazyContainer;
use Admin\PageFormItemFactory;
use Model\MenuFacade;
use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuPageLazyItem extends Object implements ILazyItem
{

	/** @var MenuItemDataSource */
	protected $menuItemDataSource;

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var PageFormItemFactory */
	protected $pageFormItemFactory;

	function __construct(MenuItemDataSource $menuItemDataSource, MenuFacade $menuFacade, PageFormItemFactory $pageFormItemFactory)
	{
		$this->menuItemDataSource = $menuItemDataSource;
		$this->menuFacade = $menuFacade;
		$this->pageFormItemFactory = $pageFormItemFactory;
	}

	public function load($id, LazyContainer $container)
	{
		$menu = $this->menuItemDataSource->get($id, function (Selection $context) {
			$menuKeyId = ':menu_has_page.page.id';
			$context->select($menuKeyId)
				->where("$menuKeyId IS NOT NULL");
		});

		$defaults = $container->getValues(TRUE);

		if ($menu) {
			$defaults['id_page'] = $menu['id'];
		}

		$container->setDefaults($defaults);
	}

	public function save($id, LazyContainer $container)
	{
		$pageId = $container['id_page']->getValue();
		$this->menuFacade->setPage($id, $pageId);
	}

	public function setup(LazyContainer $container, $sectionId)
	{
		$container['id_page'] = $this->pageFormItemFactory->create($sectionId);
	}

	public function validate(LazyContainer $container)
	{

	}

}
