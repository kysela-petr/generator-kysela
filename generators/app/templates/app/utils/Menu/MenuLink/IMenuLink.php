<?php

namespace App;

/**
 * Rozhrani pro objekty generujici link polozky menu
 * @author Petr Hlavac
 */
interface IMenuLink
{

	/**
	 * @param type $id
	 * @return ILinkSettings
	 */
	public function create($id);
}
