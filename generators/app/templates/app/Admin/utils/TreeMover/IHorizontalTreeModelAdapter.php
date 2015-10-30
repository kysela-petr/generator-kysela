<?php

namespace Admin;

interface IHorizontalTreeModelAdapter {

	/**
	 * @param int $id
	 * @param int $parentId
	 */
	public function setParent($id, $parentId);
}
