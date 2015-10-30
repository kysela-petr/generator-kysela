<?php

namespace Model;

use Esports\Repository\Repository,
	Model\SimpleModel\IsSimpleRepository;

/**
 * @author Generator
 */
class RoleResourceRepository extends Repository {

	use IsSimpleRepository;

	public function __construct($tableName, \Nette\Database\Context $db) {
		parent::__construct($db);
		$this->setTableName($tableName);
	}

}
