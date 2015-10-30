<?php

namespace Admin\Content;

use Admin\ILazyItem;
use Admin\LazyContainer;
use Admin\SubmoduleOptionFactory;
use Model\MenuFacade;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuSubmoduleLazyItem extends Object implements ILazyItem
{

	/** @var MenuItemDataSource */
	protected $menuItemDataSource;

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var SubmoduleOptionFactory */
	protected $submoduleOptionFactory;

	function __construct(MenuItemDataSource $menuItemDataSource, MenuFacade $menuFacade, SubmoduleOptionFactory $submoduleOptionFactory)
	{
		$this->menuItemDataSource = $menuItemDataSource;
		$this->menuFacade = $menuFacade;
		$this->submoduleOptionFactory = $submoduleOptionFactory;
	}

	public function load($id, LazyContainer $container)
	{
		$menu = $this->menuItemDataSource->get($id, function (\Nette\Database\Table\Selection $context) {
			$sectionIdKey = ':menu_has_submodule.submodule';
			$context->select($sectionIdKey)
				->where("$sectionIdKey IS NOT NULL");
		});

		$defaults = $container->getValues(TRUE);

		if ($menu) {
			$defaults['submodule'] = $menu['submodule'];
		}

		$container->setDefaults($defaults);
	}

	public function save($id, LazyContainer $container)
	{
		$submodule = $container['submodule']->getValue();
		$this->menuFacade->setSubmodule($id, $submodule);
	}

	public function setup(LazyContainer $container, $sectionId)
	{
		$container->addSelect('submodule', 'Submodul', $this->submoduleOptionFactory->getOptionsBySection($sectionId));
	}

	public function validate(LazyContainer $container)
	{

	}

}
