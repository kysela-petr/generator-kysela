<?php

namespace App\Component\Widget;

use Nette\Application\UI\Control;

/**
 * @author Petr Hlavac
 */
class EmptyComponent extends Control
{
	/**
	 * @return string
	 */
	public function render()
	{
		return '';
	}
}

interface IEmptyComponentFactory
{
	/**
	 * @return EmptyComponent
	 */
	public function create();
}
