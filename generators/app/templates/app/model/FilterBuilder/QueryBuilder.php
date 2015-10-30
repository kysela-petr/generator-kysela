<?php

namespace Model\Filter;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class QueryBuilder extends Object {

	/** @var string */
	private $tableName;

	/** @var string */
	private $column;

	/** @var string */
	private $via = '';

	/** @var string */
	private $relation = '.';

	/** @var string */
	private $operator = '';

	function __construct($tableName, $column) {
		$this->tableName = $tableName;
		$this->column = $column;
	}

	/**
	 * @param string $via
	 * @return \Model\Filter\QueryBuilder
	 */
	function via($via) {
		$this->via = $via;
		return $this;
	}

	/**
	 * @param string $relation
	 * @return \Model\Filter\QueryBuilder
	 */
	function relation($relation) {
		$this->relation = $relation;
		return $this;
	}

	/**
	 * @param string $operator
	 * @return \Model\Filter\QueryBuilder
	 */
	function operator($operator) {
		$this->operator = $operator;
		return $this;
	}

	/**
	 * @return string
	 */
	public function create() {
		$query = $this->getColumnPath();

		if ($this->operator) {
			$query .= " $this->operator";
		}

		$query .= ' ?';

		return $query;
	}

	/**
	 * Plna cesta k atributu
	 * @return string
	 */
	protected function getColumnPath() {
		return "{$this->via}{$this->relation}{$this->tableName}.{$this->column}";
	}

}
