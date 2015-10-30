<?php

namespace Model;

use Esports\Repository\RTRepository,
	Model\SimpleModel\IsSimpleRepository;

/**
 * @author Generator
 */
class MenuRepository extends RTRepository {

	use IsSimpleRepository;

	public function __construct($tableName, \Nette\Database\Context $db, \Esports\RelatedTable\RTFactory $rtFactory) {
		parent::__construct($db, $rtFactory);
		$this->setTableName($tableName);
	}

}
