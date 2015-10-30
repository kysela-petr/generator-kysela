<?php

namespace Admin;

use Esports\Forms\Multiselect2Input;
use Model\ModuleFacade;
use Nette\Forms\Controls\SelectBox;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class ModuleFormItemFactory extends Object
{

	/** @var ModuleFacade */
	protected $moduleFacade;

	/**
	 * @param \Model\ModuleFacade $moduleFacade
	 */
	function __construct(ModuleFacade $moduleFacade)
	{
		$this->moduleFacade = $moduleFacade;
	}

	/**
	 * @return SelectBox
	 */
	public function create()
	{
		$items = $this->moduleFacade->all()
			->order('name')
			->fetchPairs('code', 'name');

		return new SelectBox('Modul', $items);
	}

	/**
	 * @return \Esports\Forms\Multiselect2Input
	 */
	public function createMultiselect()
	{
		$return = new Multiselect2Input('Web');

		return $return->setOption('no-form-control', TRUE);
	}

}
