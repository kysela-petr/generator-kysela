<?php

namespace Model;

use Nette;
use Nette\Object;

/**
 * Autorizator pozadavku pro uzivatele administrace
 * @author Petr Hlavac
 */
class Authorizator extends Object implements Nette\Security\IAuthorizator {

	/** @var Permission */
	protected $permission;

	/** @var SuperRole */
	protected $superRole;

	function __construct(IPermissionFactory $permissionFactory, ISuperRoleFactory $superRoleFactory) {
		$this->permission = $permissionFactory->create();
		$this->superRole = $superRoleFactory->create();
	}

	public function isAllowed($role, $resource, $privilege) {
		if ($this->superRole->is($role)) {
			return true;
		}

		$permissions = $this->permission->get($role);
		$module = $this->getModulePermission($permissions, $resource);

		if (!$module) {
			return false;
		}

		return $this->hasPermission($module, $privilege);
	}

	/**
	 * Opravneni pro modul
	 * @param array $permissions
	 * @param string $resource
	 * @return array|null
	 */
	protected function getModulePermission($permissions, $resource) {
		if (!isset($permissions[$resource])) {
			return null;
		}

		return $permissions[$resource];
	}

	/**
	 * Zkontroluje opravneni modulu
	 * @param array $module
	 * @param string $privilege
	 * @return bool
	 */
	protected function hasPermission($module, $privilege) {
		return in_array($privilege, $module, true);
	}

}
