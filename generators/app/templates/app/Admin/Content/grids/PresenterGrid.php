<?php

namespace Admin\Content;

use Admin\BaseGrid;
use Admin\Grid;
use Model\PresenterFacade;

/**
 * @author Petr Hlavac
 */
class PresenterGrid extends BaseGrid
{

	/** @var PresenterFacade */
	protected $presenterFacade;

	function __construct(PresenterFacade $presenterFacade)
	{
		parent::__construct();
		$this->presenterFacade = $presenterFacade;
	}

	protected function createGrid()
	{
		$grid = $this->createPreparedGrid();
		$grid->setModel($this->presenterFacade->all());

		$grid->addColumnText('code', 'Fyzický presenter modulu aplikace')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();

		return $grid;
	}

}

interface IPresenterGridFactory
{

	/**
	 * @return PresenterGrid
	 */
	public function create();
}
