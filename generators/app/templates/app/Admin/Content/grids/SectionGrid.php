<?php

namespace Admin\Content;

use Admin\BaseGrid;
use Model\SectionFacade;
use Admin\Grid;

/**
 * Datagrid pro zobrazeni sekci webu
 * @author Petr Hlavac
 */
class SectionGrid extends BaseGrid
{

	/** @var SectionFacade */
	protected $sectionFacade;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 */
	function __construct(SectionFacade $sectionFacade)
	{
		parent::__construct();
		$this->sectionFacade = $sectionFacade;
	}

	/**
	 * @return \Admin\Grid
	 */
	protected function createGrid()
	{
		$grid = $this->createPreparedGrid();
		$grid->setModel($this->sectionFacade->all());

		$grid->addColumnNumber('id', 'ID')
			->setSortable()
			->setFilterNumber();

		$grid->addColumnText('domain', 'Doména třetího řádu')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('model_name', 'Modul')
			->setColumn('m.name')
			->setSortable()
			->setFilterText()
			->setColumn('m.name');

		$grid->addColumnText('name', 'Název sekce')
			->setSortable()
			->setFilterText()
			->setColumn('section.name');

		$grid->addColumnText('priority', 'Priorita')
			->setSortable()
			->setDefaultSort('ASC')
			->setFilterNumber()
			->setColumn('section.priority');

		$grid->addActionHref('edit', 'Edit')->setIcon('pencil')->getElementPrototype()->class = 'btn-dark-blue';

		$grid->addActionEvent('delete', 'Delete', $this->deleteRow)
			->setConfirm(function ($item) {
				return "Opravdu chcete smazat sekci '{$item['name']}'?";
			})
			->setIcon('trash-o')
			->getElementPrototype()->class = 'btn-danger';

		return $grid;
	}

	/**
	 * @param $id
	 */
	public function delete($id)
	{
		$this->sectionFacade->delete($id);
	}

}

interface ISectionGridFactory
{

	/**
	 * @return SectionGrid
	 */
	public function create();
}
