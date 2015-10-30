<?php

namespace Admin;

use Model\ModuleConst;
use Model\ModuleFilter;
use Model\SectionFacade;
use Model\SectionFilter as ModelSectionFilter;
use Nette\Application\UI\ITemplate;
use Nette\Database\Table\Selection;
use Nette\Security\User;

/**
 * @author Petr Hlavac
 */
class SectionFilter extends Component
{

	/** @var int */
	protected $sectionId;

	/** @var SectionFacade */
	protected $sectionFacade;

	/** @var callback[] */
	public $onSetFilter = [];

	/** @var \Model\ModuleFilter */
	private $moduleFilter;

	/** @var \Admin\SectionOrderer */
	private $sectionOrderer;

	/** @var bool */
	private $withoutHome;

	/** @var bool */
	private $moduleGroup;

	/** @var \Nette\Security\User */
	private $user;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	/** @var [] id=>name sections */
	private $sections;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Model\ModuleFilter $moduleFilter
	 * @param \Admin\SectionOrderer $sectionOrderer
	 * @param \Model\SectionFilter $sectionFilter
	 * @param \Nette\Security\User $user
	 */
	function __construct(SectionFacade $sectionFacade, ModuleFilter $moduleFilter, SectionOrderer $sectionOrderer, ModelSectionFilter $sectionFilter, User $user)
	{
		parent::__construct();
		$this->sectionFacade = $sectionFacade;
		$this->moduleFilter = $moduleFilter;
		$this->sectionOrderer = $sectionOrderer;
		$this->sectionFilter = $sectionFilter;
		$this->user = $user;
		$this->withoutHome = TRUE;
		$this->moduleGroup = FALSE;
	}

	/**
	 * @param int|NULL $sectionId
	 * @return int
	 */
	protected function fixSectionParam($sectionId)
	{
		$sections = $this->getSections();

		if (!isset($sections[$sectionId])) {
			return key($sections);
		}

		return $sectionId;
	}

	/**
	 * @param $sectionId
	 * @param Callback $onNotAllowedSectionCallback
	 * @return bool
	 */
	public function isAllowedSection($sectionId, $onNotAllowedSectionCallback)
	{
		$allowedSectionId = $this->fixSectionParam($sectionId);

		if ($allowedSectionId !== $sectionId) {
			$onNotAllowedSectionCallback($allowedSectionId);

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @return array
	 */
	protected function getSections()
	{
		if (!$this->sections) {
			$selection = $this->filterSections($this->sectionFacade->all());
			if (!$this->user->getIdentity()->super) {
				$this->sectionFilter->filterId($selection, $this->user->getIdentity()->sections);
			}
			$this->sectionOrderer->order($selection, 'section');

			if ($this->moduleGroup) {
				$selection->select('section.id, module.name');
				$selection->group('section.module');
			} else {
				$selection->select('section.id,section.name');
			}

			$this->sections = $selection->fetchPairs('id', 'name');
		}

		return $this->sections;
	}

	/**
	 * @param \Nette\Database\Table\Selection $selection
	 * @return \Nette\Database\Table\Selection
	 */
	private function filterSections(Selection $selection)
	{
		if ($this->withoutHome) {
			$this->moduleFilter->filterNotModule($selection, ModuleConst::FRONT);
		}

		return $selection;
	}

	/**
	 * @param \Nette\Application\UI\ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		parent::prepareTemplate($template);

		$template->setFile(__DIR__ . '/sectionFilter.latte');
		$template->sections = $this->getSections();
		$template->sectionId = $this->getSectionId();
	}

	/**
	 * @param $sectionId
	 */
	public function handleSetFilter($sectionId)
	{
		$this->onSetFilter($sectionId);
	}

	/**
	 * @return int
	 */
	function getSectionId()
	{
		return $this->sectionId;
	}

	/**
	 * @param $sectionId
	 */
	function setSectionId($sectionId)
	{
		$this->sectionId = $this->fixSectionParam($sectionId);
	}

	/**
	 * @param boolean $withoutHome
	 */
	public function setWithoutHome($withoutHome)
	{
		$this->withoutHome = (bool)$withoutHome;
	}

	/**
	 * @param boolean $moduleGroup
	 */
	public function setModuleGroup($moduleGroup)
	{
		$this->moduleGroup = (bool)$moduleGroup;
	}

}

interface ISectionFilterFactory
{

	/**
	 * @return SectionFilter
	 */
	public function create();
}
