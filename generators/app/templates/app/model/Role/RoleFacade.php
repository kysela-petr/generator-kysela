<?php

namespace Model;

use Model\SimpleModel\IsSimpleFacade;
use Nette\Object;

/**
 * @author Generator
 */
class RoleFacade extends Object
{

	use IsSimpleFacade;

	/** @var RoleService */
	protected $roleService;

	/** @var RoleFilter */
	protected $roleFilter;

	/** @var RoleHasResourceService */
	protected $roleHasResourceService;

	function __construct(RoleService $roleService, RoleFilter $roleFilter, RoleHasResourceService $roleHasResourceService)
	{
		$this->roleService = $roleService;
		$this->roleFilter = $roleFilter;
		$this->roleHasResourceService = $roleHasResourceService;
		$this->simpleService = $this->roleService;
	}

	/**
	 * Povoli pro roli vazbu zdroj => akce
	 * Vstupni pole je ve formatu:
	 * [
	 *        'resource'    =>    ZDROJ
	 *        'action'    =>    AKCE
	 * ]
	 *
	 * @param string $roleId
	 * @param array[] $data
	 */
	public function setupResources($roleId, $data)
	{
		$this->roleHasResourceService->setupResources($roleId, $data);
	}

	/**
	 * @param string $roleId
	 * @return Selection
	 */
	public function findResources($roleId)
	{
		$context = $this->roleHasResourceService->all();

		return $this->roleFilter->filterId($context, $roleId);
	}

	/**
	 * @param string $id
	 * @param array $data
	 */
	public function add($id, $data)
	{
		$data['code'] = $id;
		$this->roleService->add($data);
	}

	public function addResource($id, $resourceId, $actionId)
	{
		$this->roleHasResourceService->add($id, $resourceId, $actionId);
	}

	public function deleteResource($id, $resourceId, $actionId)
	{
		$this->roleHasResourceService->delete($id, $resourceId, $actionId);
	}

}
