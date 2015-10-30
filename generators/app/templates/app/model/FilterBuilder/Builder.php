<?php

namespace Model\Filter;

use Nette\Object,
	Nette\Database\Table\Selection;

/**
 * @author Petr Hlavac
 */
class Builder extends Object {

	/** @var Selection */
	private $context;

	/** @var QueryBuilder */
	private $queryBuilder;

	/** @var mixed */
	private $value;

	function __construct(Selection $context, QueryBuilder $queryBuilder, $value) {
		$this->context = $context;
		$this->queryBuilder = $queryBuilder;
		$this->value = $value;
	}

	/**
	 * @param string $via
	 * @return \Model\Filter\Builder
	 */
	public function via($via) {
		$this->queryBuilder->via($via);
		return $this;
	}

	/**
	 * @param string $relation
	 * @return \Model\Filter\Builder
	 */
	public function relation($relation) {
		$this->queryBuilder->relation($relation);
		return $this;
	}

	/**
	 * @param string $operator
	 * @return \Model\Filter\Builder
	 */
	function operator($operator) {
		$this->queryBuilder->operator($operator);
		return $this;
	}

	/**
	 * @return Selection
	 */
	public function build() {
		return $this->context->where($this->queryBuilder->create(), $this->value);
	}

}
