<?php

namespace Model;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class SuperRole extends Object {

	/** @var RoleFacade */
	protected $roleFacade;

	/** @var RoleFilter */
	protected $roleFilter;

	/** @var array SuperRole */
	protected $superRoles = [];

	function __construct(RoleFacade $roleFacade, RoleFilter $roleFilter) {
		$this->roleFacade = $roleFacade;
		$this->roleFilter = $roleFilter;
	}

	/**
	 * @param string $role
	 * @return boolean
	 */
	public function is($role) {
		if (!isset($this->superRoles[$role])) {
			$this->superRoles[$role] = $this->findIsSuperRole($role);
		}

		return $this->superRoles[$role];
	}

	/**
	 * @param string $role
	 * @return boolean
	 */
	protected function findIsSuperRole($role) {
		$context = $this->roleFacade->all();
		$roleRecord = $this->roleFilter->filterId($context, $role)
				->select('super')
				->fetch();

		if (!$roleRecord) {
			return false;
		}

		return (bool)$roleRecord->super;
	}
}

interface ISuperRoleFactory {

	/**
	 * @return SuperRole
	 */
	public function create();
}
