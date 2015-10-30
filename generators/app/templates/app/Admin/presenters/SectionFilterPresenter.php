<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

class SectionFilterPresenter extends DataFormPresenter
{

	/** @var int @persistent */
	public $sectionId;

	/** @var \Admin\ISectionFilterFactory @inject */
	public $sectionFilterFactory;

	/** @var bool */
	protected $sectionWithoutHome = TRUE;

	/** @var bool */
	protected $sectionGroupByModule = FALSE;

	protected function startup()
	{
		parent::startup();

		//kontrola jestli nechce editovat sekci na kterou nemá právo - kvůli reditektu
		if ($this->sectionId) {
			$this['sectionFilter']->isAllowedSection($this->sectionId, function ($allowedSectionId) {
				$this->flashMessage("Nepovolený přístup do sekce", 'danger');
				$this->redirect('this', ['sectionId' => $allowedSectionId]);
			});
		}

	}

	/**
	 * @return \Admin\SectionFilter
	 */
	protected function createComponentSectionFilter()
	{
		$filter = $this->sectionFilterFactory->create();
		$filter->onSetFilter[] = function ($sectionId) {
			$this->redirect('this', ['sectionId' => $sectionId]);
		};
		$filter->setWithoutHome($this->sectionWithoutHome);
		$filter->setModuleGroup($this->sectionGroupByModule);
		$filter->setSectionId($this->sectionId);

		return $filter;
	}

	/**
	 * Nastaví filtr sekcí tak, aby se zobrazovaly i sekce z modulu Home
	 */
	protected function setWithHomeSection()
	{
		$this->sectionWithoutHome = FALSE;
	}

	/**
	 * @return int
	 */
	protected function getSectionId()
	{
		return $this['sectionFilter']->sectionId;
	}

	/**
	 * @return mixed
	 */
	protected function createForm()
	{
		return $this->formFactory->create($this->getSectionId());
	}

	/**
	 * @return mixed
	 */
	protected function createGrid()
	{
		return $this->gridFactory->create($this->getSectionId());
	}
}
