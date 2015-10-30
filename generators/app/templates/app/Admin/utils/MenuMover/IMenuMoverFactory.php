<?php

namespace Admin;

interface IMenuMoverFactory
{

	/**
	 * @param int $sectionId
	 * @return MenuTreeMoverAdapter
	 */
	public function create($sectionId);

	/**
	 * @param int $sectionId
	 * @return MenuTreeMoverAdapter
	 */
	public function createForcer($sectionId);
}
