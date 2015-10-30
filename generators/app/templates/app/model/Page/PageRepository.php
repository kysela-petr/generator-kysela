<?php

namespace Model;

use Esports\Repository\RTRepository;
use Model\SimpleModel\IsSimpleRepository;

/**
 * @author Generator
 */
class PageRepository extends RTRepository
{

	use IsSimpleRepository;

	public function __construct($tableName, \Nette\Database\Context $db, \Esports\RelatedTable\RTFactory $rtFactory)
	{
		parent::__construct($db, $rtFactory);
		$this->setTableName($tableName);
	}

}
