<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFilter;

/**
 * @author Petr Hlavac
 */
class RoleResourceFilter extends Object {

	use IsSimpleFilter;

	/** @var Filter\Factory */
	protected $filterFactory;

	function __construct($tableName) {
		$this->filterFactory = new Filter\Factory($tableName);
		$this->simpleFilterFactory = $this->filterFactory;
		$this->setPrimaryKey('code');
	}

}
