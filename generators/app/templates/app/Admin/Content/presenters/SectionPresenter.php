<?php

namespace Admin\Content;

use Admin\DataFormPresenter;

class SectionPresenter extends DataFormPresenter
{
	/**
	 * @param \Admin\Content\ISectionGridFactory $gridFactory
	 * @param \Admin\Content\ISectionFormFactory $formFactory
	 */
	public function __construct(ISectionGridFactory $gridFactory, ISectionFormFactory $formFactory)
	{
		parent::__construct();
		$this->gridFactory = $gridFactory;
		$this->formFactory = $formFactory;
	}

	public function renderDefault()
	{
		$this->simpleList('Přidat novou sekci');
	}
}
