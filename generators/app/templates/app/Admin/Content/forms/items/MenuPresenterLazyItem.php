<?php

namespace Admin\Content;

use Admin\ILazyItem;
use Admin\LazyContainer;
use Admin\PresenterOptionFactory;
use Model\MenuFacade;
use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuPresenterLazyItem extends Object implements ILazyItem
{

	/** @var MenuItemDataSource */
	protected $menuItemDataSource;

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var PresenterOptionFactory */
	protected $presenterOptionFactory;

	function __construct(MenuItemDataSource $menuItemDataSource, MenuFacade $menuFacade, PresenterOptionFactory $presenterOptionFactory)
	{
		$this->menuItemDataSource = $menuItemDataSource;
		$this->menuFacade = $menuFacade;
		$this->presenterOptionFactory = $presenterOptionFactory;
	}

	public function load($id, LazyContainer $container)
	{
		$menu = $this->menuItemDataSource->get($id, function (Selection $context) {
			$sectionIdKey = ':menu_has_presenter.presenter';
			$context->select($sectionIdKey)
				->where("$sectionIdKey IS NOT NULL");
		});

		$defaults = $container->getValues(TRUE);

		if ($menu) {
			$defaults['presenter'] = $menu['presenter'];
		}

		$container->setDefaults($defaults);
	}

	public function save($id, LazyContainer $container)
	{
		$presenter = $container['presenter']->getValue();
		$this->menuFacade->setPresenter($id, $presenter);
	}

	public function setup(LazyContainer $container, $sectionId)
	{
		$container->addSelect('presenter', 'Presenter', $this->presenterOptionFactory->getOptionsBySection($sectionId));
	}

	public function validate(LazyContainer $container)
	{

	}

}
