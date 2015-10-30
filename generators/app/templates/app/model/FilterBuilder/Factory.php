<?php

namespace Model\Filter;

use Nette\Object,
	Nette\Database\Table\Selection;

/**
 * @author Petr Hlavac
 */
class Factory extends Object {

	/** @var string */
	private $tableName;

	function __construct($tableName) {
		$this->tableName = $tableName;
	}

	/**
	 * @param Selection $context
	 * @param string $column
	 * @param mixed $value
	 * @return Builder
	 */
	public function create(Selection $context, $column, $value) {
		$builder = new QueryBuilder($this->tableName, $column);
		return $this->createByQueryBuilder($context, $builder, $value);
	}

	/**
	 * @param Selection $context
	 * @return \Model\Filter\NBuilder
	 */
	public function createN(Selection $context) {
		return new NBuilder($context, $this->tableName);
	}

	/**
	 * @param Selection $context
	 * @param \Model\Filter\QueryBuilder $queryBuilder
	 * @param mixed $value
	 * @return \Model\Filter\Builder
	 */
	public function createByQueryBuilder(Selection $context, QueryBuilder $queryBuilder, $value) {
		return new Builder($context, $queryBuilder, $value);
	}

}
