<?php

namespace Model\SimpleModel\Repository;

use Nette\Database\Table\Selection;

/**
 * @author Petr Hlavac
 */
trait HasTable {

	/** @var string */
	private $tableName = '';

	/**
	 * @return Selection
	 */
	protected function table() {
		return $this->db->table($this->tableName);
	}

	/**
	 * Nazev tabulky
	 * @param string $tableName
	 */
	public function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	/**
	 * Nazev tabulky
	 * @return string
	 */
	public function getTableName() {
		return $this->tableName;
	}

}
