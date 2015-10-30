<?php

namespace Model;

use Nette\Database\Table\Selection;
use Nette\Object,
	Model\SimpleModel\IsSimpleFilter;

/**
 * @author Petr Hlavac
 */
class MenuFilter extends Object {

	use IsSimpleFilter;

	/** @var Filter\Factory */
	protected $filterFactory;

	function __construct($tableName) {
		$this->filterFactory = new Filter\Factory($tableName);
		$this->simpleFilterFactory = $this->filterFactory;
	}

	/**
	 * @param Selection $context
	 * @param boolean $show
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function filterShow(Selection $context, $show, $via = '') {
		return $this->filterFactory->create($context, 'show', $show)
			->via($via)
			->build();
	}
}
