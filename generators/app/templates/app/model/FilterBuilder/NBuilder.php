<?php

namespace Model\Filter;

use Nette\Database\Table\Selection;
use Nette\Object;
use Nette\Utils\Callback;

/**
 * @author Petr Hlavac
 */
class NBuilder extends Object {

	/** @var Selection */
	private $context;

	/** @var string */
	private $tableName;

	/** @var QueryBuilder[] */
	private $builders = [];

	/** @var array */
	private $values = [];

	function __construct(Selection $context, $tableName) {
		$this->context = $context;
		$this->tableName = $tableName;
	}

	/**
	 * @param string $column
	 * @param mixed $value
	 * @param string $alias [optional]
	 * @return QueryBuilder
	 */
	public function setup($column, $value, $alias = '') {
		$queryBuilder = new QueryBuilder($alias ? $alias : $this->tableName, $column);

		$this->builders[] = $queryBuilder;
		$this->values[] = $value;

		return $queryBuilder;
	}

	/**
	 * @param \Model\Filter\QueryBuilder $builder
	 * @param mixed $value
	 * @return NBuilder
	 */
	public function add(QueryBuilder $builder, $value) {
		$this->builders[] = $builder;
		$this->values[] = $value;
		return $this;
	}

	/**
	 * @return Selection
	 */
	public function build() {
		$values = $this->values;
		array_unshift($values, $this->createQuery());

		return Callback::invokeArgs($this->context->where, $values);
	}

	/**
	 * @return string
	 */
	protected function createQuery() {
		$query = [];

		foreach ($this->builders as $builder) {
			$query[] = $builder->create();
		}

		return implode(' OR ', $query);
	}

}
