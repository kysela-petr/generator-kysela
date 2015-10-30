<?php

namespace Admin;

interface IVerticalTreeModelAdapter {

	/**
	 * @param int $id
	 * @param int $priority
	 */
	public function setPriority($id, $priority);
}
