<?php

namespace Model\SimpleModel\Facade;

use Nette\Database\Table\Selection,
	Nette\Database\Table\ActiveRow;

/**
 * @author Petr Hlavac
 */
trait IsCRUD {

	private $simpleService;

	/**
	 * @return Selection
	 */
	public function all() {
		return $this->simpleService->all();
	}

	/**
	 * @param array $data
	 * @return ActiveRow
	 */
	public function add($data) {
		return $this->simpleService->add($data);
	}

	/**
	 * @param int|array $id
	 * @param array $data
	 * @return int
	 */
	public function edit($id, $data) {
		return $this->simpleService->edit($id, $data);
	}

	/**
	 * @param int|array $id
	 */
	public function delete($id) {
		$this->simpleService->delete($id);
	}

}
