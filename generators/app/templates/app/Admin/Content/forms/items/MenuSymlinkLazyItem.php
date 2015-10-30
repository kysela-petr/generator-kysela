<?php

namespace Admin\Content;

use Admin\ILazyItem;
use Admin\LazyContainer;
use Admin\MenuFormItemFactory;
use Model\MenuTypeConst;
use Model\MenuFacade;
use Model\MenuFilter;
use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuSymlinkLazyItem extends Object implements ILazyItem
{

	/** @var MenuItemDataSource */
	protected $menuItemDataSource;

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var MenuFilter */
	protected $menuFilter;

	/** @var MenuFormItemFactory */
	protected $menuFormItemFactory;

	function __construct(MenuItemDataSource $menuItemDataSource, MenuFacade $menuFacade, MenuFilter $menuFilter, MenuFormItemFactory $menuFormItemFactory)
	{
		$this->menuItemDataSource = $menuItemDataSource;
		$this->menuFacade = $menuFacade;
		$this->menuFilter = $menuFilter;
		$this->menuFormItemFactory = $menuFormItemFactory;
	}

	public function load($id, LazyContainer $container)
	{
		$menu = $this->menuItemDataSource->get($id, function (Selection $context) {
			$key = ':menu_has_symlink.symlink.id';
			$context->select($key)
				->where("$key IS NOT NULL");
		});

		$data = $container->getValues(TRUE);

		if ($menu) {
			$data['id_menu'] = $menu['id'];
		}

		$container->setDefaults($data);
	}

	public function save($id, LazyContainer $container)
	{
		$symlinkId = $container['id_menu']->getValue();
		$this->menuFacade->setSymlink($id, $symlinkId);
	}

	public function setup(LazyContainer $container, $sectionId)
	{
		$menu = $container['id_menu'] = $this->menuFormItemFactory->create();
		$menu->caption = 'Cílová kategorie';
	}

	public function validate(LazyContainer $container)
	{
		$symlinkId = $container['id_menu']->getValue();
		$menu = $this->menuFilter->filterId($this->menuFacade->all(), $symlinkId)
			->fetch();

		if (!$menu) {
			$container['id_menu']->addError("Položka neexistuje");

			return;
		}

		if ($menu['type'] == MenuTypeConst::SYMLINK) {
			$container['id_menu']->addError("Cílová položka nemůže být odkaz na další položku");
		}
	}

}
