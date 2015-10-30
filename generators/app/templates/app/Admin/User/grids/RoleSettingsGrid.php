<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\User;

use Admin\BaseGrid;
use Model\RoleFacade;
use Model\RoleFilter;

class RoleSettingsGrid extends BaseGrid
{

	/** @var string */
	protected $id;

	/** @var \Model\RoleFacade */
	private $roleFacade;

	/** @var \Model\RoleFilter */
	private $roleFilter;


	/**
	 * @param int $id
	 * @param \Model\RoleFacade $roleFacade
	 * @param \Model\RoleFilter $roleFilter
	 */
	function __construct($id, RoleFacade $roleFacade, RoleFilter $roleFilter)
	{
		parent::__construct();
		$this->id = $id;
		$this->roleFacade = $roleFacade;
		$this->roleFilter = $roleFilter;
	}

	protected function createGrid()
	{
		$grid = $this->createPreparedGrid();
		$grid->setModel($this->getModel());

		$grid->addColumnText('resource_name', 'Zdroj')
			->setSortable()
			->setFilterText()
			->setColumn('resource.name');

		$grid->addColumnText('action_name', 'Akce')
			->setSortable()
			->setFilterText()
			->setColumn('action.name');

		$grid->addActionHref('delete', 'Delete')
			->setCustomHref(function ($row) {
				return $this->link('delete!', [$row['resource_code'], $row['action_code']]);
			});

		return $grid;
	}

	protected function getModel()
	{
		return $this->roleFacade->findResources($this->id)
			->select('resource.name AS resource_name')
			->select('resource.code AS resource_code')
			->select('action.name AS action_name')
			->select('action.code AS action_code');
	}

	/**
	 * @param $resource
	 * @param $action
	 */
	public function handleDelete($resource, $action)
	{
		$this->onDelete($resource, $action);
	}

	/**
	 * @param $resource
	 * @param $action
	 */
	public function deleteRole($resource, $action)
	{
		$this->roleFacade->deleteResource($this->id, $resource, $action);
	}

}

interface IRoleSettingsGridFactory
{

	/**
	 * @param string $id
	 * @return RoleSettingsGrid
	 */
	public function create($id);
}
