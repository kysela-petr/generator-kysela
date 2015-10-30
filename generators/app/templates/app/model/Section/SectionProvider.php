<?php
/**
 * @author Martin Kovařčík.
 */

namespace Model;

use Nette\Object;

class SectionProvider extends Object
{

	/** @var \Model\SectionFacade */
	private $sectionFacade;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Model\SectionFilter $sectionFilter
	 */
	function __construct(SectionFacade $sectionFacade, SectionFilter $sectionFilter)
	{
		$this->sectionFacade = $sectionFacade;
		$this->sectionFilter = $sectionFilter;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	public function all()
	{
		return $this->sectionFilter->filterNotHomeSection($this->sectionFacade->all(), ModuleConst::FRONT);
	}

	/**
	 * @param $id
	 * @return \Nette\Database\Table\Selection
	 */
	public function section($id)
	{
		return $this->sectionFilter->filterId($this->all(), $id);
	}

	/**
	 * @param string $module
	 * @param int $notId
	 * @return \Nette\Database\Table\Selection
	 */
	public function moduleSection($module, $notId)
	{
		$selection = $this->all();
		$this->sectionFilter->filterIdIsNot($selection, $notId);
		$this->sectionFilter->filterModule($selection, $module);

		return $selection;
	}

	/**
	 * @param string $domain
	 * @return \Nette\Database\Table\Selection
	 */
	public function sectionDomain($domain)
	{
		$selection = $this->all();
		$this->sectionFilter->filterDomainSearch($selection, 'domain', $domain);

		return $selection;
	}

	/**
	 * @param $module
	 * @return \Nette\Database\Table\Selection
	 */
	public function sectionModule($module)
	{
		$selection = $this->all();
		$this->sectionFilter->filterModule($selection, $module);

		return $selection;
	}
}
