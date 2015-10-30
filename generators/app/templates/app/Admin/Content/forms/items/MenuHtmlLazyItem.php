<?php

namespace Admin\Content;

use Admin\ILazyItem;
use Admin\LazyContainer;
use Model\MenuFacade;
use Model\MenuFilter;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuHtmlLazyItem extends Object implements ILazyItem
{

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var MenuFilter */
	protected $menuFilter;

	function __construct(MenuFacade $menuFacade, MenuFilter $menuFilter)
	{
		$this->menuFacade = $menuFacade;
		$this->menuFilter = $menuFilter;
	}

	public function load($id, LazyContainer $container)
	{
		$menu = $this->menuFilter->filterId($this->menuFacade->all(), $id)
			->select('html')
			->fetch();

		$defaults = $container->getValues(TRUE);

		if ($menu) {
			$defaults['html'] = $menu['html'];
		}

		$container->setDefaults($defaults);
	}

	public function save($id, LazyContainer $container)
	{
		$this->menuFacade->edit($id, $container->getValues());
	}

	public function setup(LazyContainer $container, $sectionId)
	{
		$container->addTextAreaNull('html', 'HTML kód');
	}

	public function validate(LazyContainer $container)
	{

	}

}
