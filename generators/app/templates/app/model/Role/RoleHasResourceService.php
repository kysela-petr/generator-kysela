<?php

namespace Model;

use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class RoleHasResourceService extends Object
{

	/** @var RoleHasResourceRepository */
	protected $roleHasResourceRepository;

	/** @var RoleFilter */
	protected $roleFilter;

	function __construct(RoleHasResourceRepository $roleHasResourceRepository, RoleFilter $roleFilter)
	{
		$this->roleHasResourceRepository = $roleHasResourceRepository;
		$this->roleFilter = $roleFilter;
	}


	public function add($roleId, $resourceId, $actionId)
	{
		return $this->roleHasResourceRepository->add($roleId, $resourceId, $actionId);
	}

	/**
	 * @return Selection
	 */
	public function all()
	{
		return $this->roleHasResourceRepository->all();
	}


	public function delete($roleId, $resourceId, $actionId)
	{
		$this->roleHasResourceRepository->delete($roleId, $resourceId, $actionId);
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
		$oldRolesContext = $this->all();
		$oldRoles = $this->roleFilter->filterId($oldRolesContext, $roleId);

		$this->processSetupResource($roleId, $oldRoles, $data, function ($role, $resource, $action) {
			$this->roleHasResourceRepository->delete($role, $resource, $action);
		});

		$this->processSetupResource($roleId, $data, $oldRoles, function ($role, $resource, $action) {
			$this->roleHasResourceRepository->add($role, $resource, $action);
		});
	}

	protected function processSetupResource($roleId, $sourceArray, $targetArray, $callback)
	{
		foreach ($sourceArray as $sourceRow) {
			$found = FALSE;

			foreach ($targetArray as $targetRow) {
				if ($targetRow['resource'] == $sourceRow['resource']
					&& $targetRow['action'] == $sourceRow['action']
				) {
					$found = TRUE;
					break;
				}
			}

			if (!$found) {
				$callback($roleId, $sourceRow['resource'], $sourceRow['action']);
			}
		}
	}

}
