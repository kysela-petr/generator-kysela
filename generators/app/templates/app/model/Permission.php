<?php

namespace Model;

use App\Helper\Role;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class Permission extends Object {

	/** @var RoleFacade */
	protected $roleFacade;

	/** @var \App\Helper\Role */
	protected $roleHelper;

	/** @var array Opravneni */
	protected $permissions = [];

	/**
	 * @param \Model\RoleFacade $roleFacade
	 * @param \App\Helper\Role $roleHelper
	 */
	function __construct(RoleFacade $roleFacade, Role $roleHelper) {
		$this->roleFacade = $roleFacade;
		$this->roleHelper = $roleHelper;
	}

	/**
	 * @param string $role
	 * @return array
	 */
	public function get($role) {
		if (!isset($this->permissions[$role])) {
			$this->permissions[$role] = $this->findPermissions($role);
		}

		return $this->permissions[$role];
	}

	/**
	 * @param string $role
	 * @return array
	 */
	protected function findPermissions($role) {
		$resources = $this->roleFacade->findResources($role);
		return $this->roleHelper->toArray($resources);
	}

}

interface IPermissionFactory {

	/**
	 * @return Permission
	 */
	public function create();
}
