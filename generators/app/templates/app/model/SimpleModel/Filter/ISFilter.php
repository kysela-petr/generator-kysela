<?php

namespace Model\SimpleModel\Filter;

use Nette\Database\Table\Selection;

trait IsFilter {

	/** @var \Model\Filter\Factory */
	private $simpleFilterFactory;

	/** @var string Nazev primarniho klice */
	private $primaryKey = 'id';

	/**
	 * Filtruje zaznam dle ID
	 * @param Selection $context
	 * @param int $id
	 * @param string $via [optional]
	 * @param string $relation [optional]
	 * @return Selection
	 */
	public function filterId(Selection $context, $id, $via = '', $relation = '.') {
		return $this->simpleFilterFactory->create($context, $this->primaryKey, $id)
				->via($via)
				->relation($relation)
				->build();
	}

	/**
	 * Hleda vyraz ve sloupci.
	 * @param \Nette\Database\Table\Selection $selection
	 * @param string $column
	 * @param string $word
	 * @param string $via
	 * @return \Nette\Database\Table\Selection
	 */
	public function filterSearch(Selection $selection, $column, $word, $via = '') {
		return $selection->where("? LIKE ?", new \Nette\Database\SqlLiteral($column), "%$word%");
	}

	/**
	 * Nastavi nazev primarniho klice (default = id)
	 * @param string $primaryKey
	 */
	function setPrimaryKey($primaryKey) {
		$this->primaryKey = $primaryKey;
	}

}
