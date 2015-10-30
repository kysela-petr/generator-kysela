<?php

namespace Model\Filter;

/**
 * Builder uplne cesty atributu tabulky s definici masky atributu.
 * Znak ? bude v masce nahrazen nazvem sloupecku
 * Priklad masky: TRIM(?)
 * @author Petr Hlavac
 */
class QueryMaskBuilder extends QueryBuilder {

	private $mask;

	public function __construct($tableName, $column, $mask) {
		parent::__construct($tableName, $column);
		$this->mask = $mask;
	}

	protected function getColumnPath() {
		$path = parent::getColumnPath();
		return str_replace('?', $path, $this->mask);
	}

}
