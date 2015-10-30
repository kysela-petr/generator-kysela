<?php

namespace Admin;

use Model\ModuleConst;
use Model\ModuleFilter;
use Model\SectionFacade;
use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class SectionOptionFactory extends Object
{

	/** @var SectionFacade */
	protected $sectionFacade;

	/** @var ModuleFilter */
	private $moduleFilter;

	function __construct(SectionFacade $sectionFacade, ModuleFilter $moduleFilter)
	{
		$this->sectionFacade = $sectionFacade;
		$this->moduleFilter = $moduleFilter;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->orderAndFetch($this->sectionFacade->all());
	}

	/**
	 * @return array
	 */
	public function getOptionsWithoutHome()
	{
		$context = $this->moduleFilter->filterNotModule($this->sectionFacade->all(), ModuleConst::FRONT);

		return $this->orderAndFetch($context);
	}

	/**
	 * @param \Nette\Database\Table\Selection $context
	 * @return array
	 */
	protected function orderAndFetch(Selection $context)
	{
		return $context
			->order('section.priority ASC, section.name ASC')
			->fetchPairs('id', 'name');
	}

}
