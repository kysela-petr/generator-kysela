<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\User;

use Admin\BaseGrid;
use Model\RoleFacade;

class RoleGrid extends BaseGrid
{

	/** @var \Model\RoleFacade */
	private $roleFacade;

	/**
	 * @param \Model\RoleFacade $roleFacade
	 */
	function __construct(RoleFacade $roleFacade)
	{
		parent::__construct();

		$this->roleFacade = $roleFacade;
	}

	/**
	 * @return \Admin\Grid
	 */
	protected function createGrid()
	{
		$grid = $this->createPreparedGrid();
		$grid->setPrimaryKey('code');
		$grid->setModel($this->getModel());

		$grid->addColumnText('code', 'Kód')
			->setSortable()
			->setDefaultSort('asc')
			->setFilterText();

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();

		$superAdmin = $grid->addColumnText('super', 'Super role');
		$this->helpers->setupAsBool($superAdmin);

		$editButton = $this->helpers->addEditAction($grid);
		$editButton->setCustomHref(function ($row) use ($editButton) {
			return $editButton->presenter->link($editButton->getDestination(), ['id' => $row['code']]);
		});

		$this->helpers->addDeleteEvent($grid, $this->deleteRow);

		return $grid;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	private function getModel()
	{
		return $this->roleFacade->all();
	}

	/**
	 * @param int $id
	 */
	public function delete($id)
	{
		$this->roleFacade->delete($id);
	}

}

interface IRoleGridFactory
{
	/** @return \Admin\User\RoleGrid */
	public function create();
}
