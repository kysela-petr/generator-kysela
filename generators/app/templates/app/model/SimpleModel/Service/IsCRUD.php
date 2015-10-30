<?php

namespace Model\SimpleModel\Service;

use Nette\Database\Table\Selection,
	Nette\Database\Table\ActiveRow;

/**
 * @author Petr Hlavac
 */
trait IsCRUD {

	private $simpleRepository;

	/**
	 * @return Selection
	 */
	public function all() {
		return $this->simpleRepository->all();
	}

	/**
	 * @param array $data
	 * @return ActiveRow
	 */
	public function add($data) {
		return $this->simpleRepository->add($data);
	}

	/**
	 * @param int|array $id
	 * @param array $data
	 * @return int
	 */
	public function edit($id, $data) {
		return $this->simpleRepository->edit($id, $data);
	}

	/**
	 * @param int|array $id
	 */
	public function delete($id) {
		$this->simpleRepository->delete($id);
	}

}
