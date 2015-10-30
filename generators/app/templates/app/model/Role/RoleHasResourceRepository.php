<?php

namespace Model;

use Esports\Repository\Repository,
	Model\SimpleModel\Repository\HasTable,
	Nette\Database\Table\Selection,
	Nette\Database\Table\ActiveRow;

/**
 * @author Generator
 */
class RoleHasResourceRepository extends Repository {

	use HasTable;

	public function __construct($tableName, \Nette\Database\Context $db) {
		parent::__construct($db);
		$this->setTableName($tableName);
	}

	/**
	 *
	 * @return Selection
	 */
	public function all() {
		return $this->table();
	}

	/**
	 * @param int $roleId
	 * @param int $resourceId
	 * @param int $actionId
	 * @return ActiveRow
	 */
	public function add($roleId, $resourceId, $actionId) {
		$data = $this->createDataArray($roleId, $resourceId, $actionId);
		return $this->table()->insert($data);
	}

	/**
	 * @param int $roleId
	 * @param int $resourceId
	 * @param int $actionId
	 */
	public function delete($roleId, $resourceId, $actionId) {
		$data = $this->createDataArray($roleId, $resourceId, $actionId);
		$this->table()->where($data)->delete();
	}

	/**
	 * @param int $roleId
	 * @param int $resourceId
	 * @param int $actionId
	 * @return array
	 */
	public function createDataArray($roleId, $resourceId, $actionId) {
		return [
			'role' => $roleId,
			'resource' => $resourceId,
			'action' => $actionId
		];
	}

}
