<?php

namespace Admin\Content;

use Admin\BaseGrid;
use Admin\Grid;
use Model\MenuTypeFacade;

/**
 * Datagrid pro zobrazeni typu menu
 * @author Petr Hlavac
 */
class MenuTypeGrid extends BaseGrid
{

	/** @var MenuTypeFacade */
	protected $menuTypeFacade;

	function __construct(MenuTypeFacade $menuTypeFacade)
	{
		parent::__construct();
		$this->menuTypeFacade = $menuTypeFacade;
	}

	protected function createGrid()
	{
		$grid = $this->createPreparedGrid();
		$grid->setModel($this->menuTypeFacade->all());

		$grid->addColumnText('code', 'Kód')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();

		return $grid;
	}

}

interface IMenuTypeGridFactory
{
	/**
	 * @return MenuTypeGrid
	 */
	public function create();
}
