<?php

namespace Admin\Content;

use Admin\ILazyItem;
use Admin\LazyContainer;
use Admin\SectionOptionFactory;
use Model\MenuFacade;
use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuSectionLazyItem extends Object implements ILazyItem
{

	/** @var MenuItemDataSource */
	protected $menuItemDataSource;

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var SectionOptionFactory */
	protected $sectionOptionFactory;

	function __construct(MenuItemDataSource $menuItemDataSource, MenuFacade $menuFacade, SectionOptionFactory $sectionOptionFactory)
	{
		$this->menuItemDataSource = $menuItemDataSource;
		$this->menuFacade = $menuFacade;
		$this->sectionOptionFactory = $sectionOptionFactory;
	}

	public function load($id, LazyContainer $container)
	{
		$menu = $this->menuItemDataSource->get($id, function (Selection $context) {
			$sectionIdKey = ':menu_has_section.section.id';
			$context->select($sectionIdKey)
				->where("$sectionIdKey IS NOT NULL");
		});

		$defaults = $container->getValues(TRUE);

		if ($menu) {
			$defaults['id_section'] = $menu['id'];
		}

		$container->setDefaults($defaults);
	}

	public function save($id, LazyContainer $container)
	{
		$sectionId = $container['id_section']->getValue();
		$this->menuFacade->setSection($id, $sectionId);
	}

	public function setup(LazyContainer $container, $sectionId)
	{
		$container->addSelect('id_section', 'Sekce webu', $this->sectionOptionFactory->getOptions());
	}

	public function validate(LazyContainer $container)
	{

	}

}
