<?php

namespace Admin;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuOrderer extends Object
{

	/**
	 * @param \Nette\Database\Table\Selection $context
	 * @param string $via [optional]
	 * @return \Nette\Database\Table\Selection
	 */
	public function order(\Nette\Database\Table\Selection $context, $via = '')
	{
		$viaParam = $via ? "$via." : '';

		return $context->order("{$viaParam}priority ASC, {$viaParam}name ASC");
	}

}
