<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\Utils;

use Model\ModuleConst;
use Model\ModuleFilter;
use Model\SectionFacade;
use Nette\Object;

class SectionDetector extends Object
{

	/** @var  int */
	private $frontSectionId;

	/** @var \Model\SectionFacade */
	private $sectionFacade;

	/** @var \Model\ModuleFilter */
	private $moduleFilter;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Model\ModuleFilter $moduleFilter
	 */
	function __construct(SectionFacade $sectionFacade, ModuleFilter $moduleFilter)
	{
		$this->sectionFacade = $sectionFacade;
		$this->moduleFilter = $moduleFilter;
	}

	/**
	 * @param int $sectionId
	 * @return bool
	 */
	public function isFrontSection($sectionId)
	{
		return ((int)$sectionId === $this->getFrontSectionId());
	}

	/**
	 * @return int
	 */
	private function getFrontSectionId()
	{
		if (!$this->frontSectionId) {
			$this->loadFrontSection();
		}

		return $this->frontSectionId;
	}

	private function loadFrontSection()
	{

		$selection = $this->moduleFilter->filterCode($this->sectionFacade->all(), ModuleConst::FRONT);

		$record = $selection->fetch();

		if (!$record) {
			return;
		}

		$this->frontSectionId = (int)$record->id;
	}
}

interface ISectionDetectorFactory
{
	/** @return \Admin\Utils\SectionDetector */
	public function create();
}
