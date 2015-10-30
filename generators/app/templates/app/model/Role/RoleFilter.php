<?php

namespace Model;

use Nette\Database\Table\Selection;
use Nette\Object,
	Model\SimpleModel\IsSimpleFilter;

/**
 * @author Petr Hlavac
 */
class RoleFilter extends Object {

	use IsSimpleFilter;

	/** @var Filter\Factory */
	protected $filterFactory;

	function __construct($tableName) {
		$this->filterFactory = new Filter\Factory($tableName);
		$this->simpleFilterFactory = $this->filterFactory;
		$this->setPrimaryKey('code');
	}

	/**
	 * @param Selection $context
	 * @param $super
	 * @param string $via
	 * @return Selection
	 */
	public function filterSuper(Selection $context, $super, $via = '')
	{
		return $this->filterFactory->create($context, 'super', $super)
			->via($via)
			->build();
	}

}
