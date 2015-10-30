<?php

namespace Admin;

use Model\PresenterFacade;
use Nette\Forms\Controls\SelectBox;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class PresenterOptionFactory extends Object {

	/** @var PresenterFacade */
	protected $presenterFacade;

	/** @var \Model\SectionFilter */
	protected $sectionFilter;

	function __construct(PresenterFacade $presenterFacade, \Model\SectionFilter $sectionFilter) {
		$this->presenterFacade = $presenterFacade;
		$this->sectionFilter = $sectionFilter;
	}

	/**
	 * @return SelectBox
	 */
	public function getOptions() {
		return $this->presenterFacade->all()
				->order('name')
				->fetchPairs('code', 'name');
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function getOptionsBySection($id) {
		$context = $this->presenterFacade->all();
		$this->sectionFilter->filterId($context, $id, ':module_has_presenter.module', ':');
		return $context->order('name')
						->fetchPairs('code', 'name');
	}

}
