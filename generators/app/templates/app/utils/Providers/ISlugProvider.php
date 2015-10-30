<?php
/**
 * @author Martin Kovařčík.
 */

namespace App\Provider;

interface ISlugProvider
{

	/**
	 * @param int $id
	 * @return string|NULL
	 */
	public function getSlug($id);

}
