<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use Admin\Helpers\Select2AllButton;
use Esports\Forms\Multiselect2Input;
use Model\SectionFacade;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\SelectBox;
use Nette\Object;

class SectionFormItemFactory extends Object
{

	/** @var \Model\SectionFacade */
	private $sectionFacade;

	/** @var \Admin\SectionOptionFactory */
	private $sectionOptionFactory;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Admin\SectionOptionFactory $sectionOptionFactory
	 */
	function __construct(SectionFacade $sectionFacade, SectionOptionFactory $sectionOptionFactory)
	{
		$this->sectionFacade = $sectionFacade;
		$this->sectionOptionFactory = $sectionOptionFactory;
	}

	/**
	 * @return \Esports\Forms\Multiselect2Input
	 */
	public function createMultiselect()
	{
		$return = new Multiselect2Input('Sekce');

		return $return->setOption('no-form-control', TRUE);
	}

	/**
	 * @return mixed
	 */
	public function createMultiselectBox()
	{
		$return = new MultiSelectBox("Sekce", $this->sectionOptionFactory->getOptionsWithoutHome());
		$return->setAttribute('class', 'select2');
		$return->setOption('description', Select2AllButton::createButton());

		return $return->setOption('no-form-control', TRUE);
	}

	/**
	 * @return \Nette\Forms\Controls\SelectBox
	 */
	public function create()
	{
		return new SelectBox('Sekce', $this->sectionOptionFactory->getOptionsWithoutHome());
	}


}
