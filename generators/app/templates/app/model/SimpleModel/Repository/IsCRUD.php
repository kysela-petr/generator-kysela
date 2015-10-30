<?php

namespace Model\SimpleModel\Repository;

use Nette\Database\Table\Selection,
	Nette\Database\Table\ActiveRow;

/**
 * @author Petr Hlavac
 */
trait IsCRUD {

	/**
	 * @return Selection
	 */
	public function all() {
		return $this->table();
	}

	/**
	 * @param array $data
	 * @return ActiveRow
	 */
	public function add($data) {
		return $this->table()->insert($data);
	}

	/**
	 * @param int|array $id
	 * @param array $data
	 * @return int
	 */
	public function edit($id, $data) {
		return $this->table()
				->wherePrimary($id)
				->update($data);
	}

	/**
	 * @param int|array $id
	 */
	public function delete($id) {
		$this->table()
				->wherePrimary($id)
				->delete();
	}
}
