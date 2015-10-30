<?php

namespace Admin\Article;

use Admin\BaseGrid;
use Model\PageFacade;

/**
 * @author Petr Hlavac
 */
class PageGrid extends BaseGrid
{

	/** @var PageFacade */
	protected $pageFacade;

	/** @var int */
	private $sectionId;

	/**
	 * @param int $sectionId
	 * @param \Model\PageFacade $pageFacade
	 */
	function __construct($sectionId, PageFacade $pageFacade)
	{
		parent::__construct();
		$this->pageFacade = $pageFacade;
		$this->sectionId = (int)$sectionId;
	}

	protected function createGrid()
	{
		$grid = $this->createPreparedGrid();
		$grid->setModel($this->getModel());

		$grid->addColumnNumber('id', 'ID')
			->setSortable()
			->setDefaultSort('DESC')
			->setFilterNumber()
			->setColumn('page.id');

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText()
			->setColumn('page.name');

		$activeCol = $grid->addColumnText('show', 'Zobrazit');
		$this->helpers->setupAsBool($activeCol);

		$this->helpers->addEditAction($grid);
		$this->helpers->addDeleteEvent($grid, $this->deleteRow);

		return $grid;
	}

	public function delete($id)
	{
		$this->pageFacade->delete($id);
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	protected function getModel()
	{
		return $this->sectionFilter->filterId($this->pageFacade->all(), $this->sectionId);
	}

}

interface IPageGridFactory
{
	/**
	 * @param $sectionId
	 * @return \Admin\Article\PageGrid
	 */
	public function create($sectionId);
}
