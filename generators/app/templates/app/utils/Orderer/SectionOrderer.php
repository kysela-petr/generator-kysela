<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use Nette\Database\Table\Selection;
use Nette\Object;

class SectionOrderer extends Object
{
	/**
	 * @param Selection $context
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function order(Selection $context, $via = '')
	{
		$viaParam = $via ? "$via." : '';

		return $context->order("{$viaParam}priority ASC, {$viaParam}name ASC");
	}
}
