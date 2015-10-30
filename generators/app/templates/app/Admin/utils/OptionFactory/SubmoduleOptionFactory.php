<?php

namespace Admin;

use Model\SectionFilter as ModelSectionFilter;
use Model\SubmoduleFacade;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class SubmoduleOptionFactory extends Object
{

	/** @var SubmoduleFacade */
	protected $submoduleFacade;

	/** @var SectionFilter */
	protected $sectionFilter;

	/**
	 * @param \Model\SubmoduleFacade $submoduleFacade
	 * @param \Model\SectionFilter $sectionFilter
	 */
	function __construct(SubmoduleFacade $submoduleFacade, ModelSectionFilter $sectionFilter)
	{
		$this->submoduleFacade = $submoduleFacade;
		$this->sectionFilter = $sectionFilter;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->submoduleFacade->all()
			->order('name')
			->fetchPairs('code', 'name');
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function getOptionsBySection($id)
	{
		$context = $this->submoduleFacade->all();
		$this->sectionFilter->filterId($context, $id, ':module_has_submodule.module', ':');

		return $context->order('name')
			->fetchPairs('code', 'name');
	}

}
