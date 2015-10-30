<?php

namespace Admin\Content;

use Admin\ILazyItem;
use Admin\LazyContainer;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuEmptyLazyItem extends Object implements ILazyItem
{

	public function load($id, LazyContainer $container)
	{

	}

	public function save($id, LazyContainer $container)
	{

	}

	public function setup(LazyContainer $container, $sectionId)
	{

	}

	public function validate(LazyContainer $container)
	{

	}

}
