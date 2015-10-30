<?php

namespace Admin\Content;

use Admin\BaseGrid;
use Admin\Grid;
use Model\ModuleFacade;

/**
 * Datagrid pro zobrazeni modulu
 * @author Petr Hlavac
 */
class ModuleGrid extends BaseGrid
{

	/** @var ModuleFacade */
	protected $moduleFacade;

	/**
	 * @param \Model\ModuleFacade $moduleFacade
	 */
	function __construct(ModuleFacade $moduleFacade)
	{
		parent::__construct();
		$this->moduleFacade = $moduleFacade;
	}

	/**
	 * @return \Admin\Grid
	 */
	protected function createGrid()
	{
		$grid = $this->createPreparedGrid();
		$grid->setModel($this->moduleFacade->all());

		$grid->addColumnText('code', 'Fyzický modul aplikace')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();

		return $grid;
	}

}

interface IModuleGridFactory
{
	/**
	 * @return ModuleGrid
	 */
	public function create();
}
