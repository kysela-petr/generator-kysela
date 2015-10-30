<?php

namespace Admin\Content;

use Admin\BaseGrid;
use Admin\Grid;
use Model\SubmoduleFacade;

/**
 * @author Petr Hlavac
 */
class SubmoduleGrid extends BaseGrid
{

	/** @var SubmoduleFacade */
	protected $submoduleFacade;

	function __construct(SubmoduleFacade $submoduleFacade)
	{
		parent::__construct();
		$this->submoduleFacade = $submoduleFacade;
	}

	protected function createGrid()
	{
		$grid = $this->createPreparedGrid();
		$grid->setModel($this->submoduleFacade->all());

		$grid->addColumnText('code', 'Fyzický submodul aplikace')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();

		return $grid;
	}

}

interface ISubmoduleGridFactory
{
	/**
	 * @return SubmoduleGrid
	 */
	public function create();
}
