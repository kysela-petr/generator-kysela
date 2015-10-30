<?php

namespace Admin\Article;

use Admin\SectionFormItemFactory;
use Admin\Utils\ISectionDetectorFactory;
use Model\PageFacade;
use Model\PageFilter;
use Admin\BaseForm;
use Model\SectionFacade;
use Model\SectionFilter;
use Nette\Application\UI\Form;
use Model\RecordNotFoundException;

/**
 * @author Petr Hlavac
 */
class PageForm extends BaseForm
{

	/** @var PageFacade */
	protected $pageFacade;

	/** @var PageFilter */
	protected $pageFilter;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	/** @var int */
	protected $id;

	/** @var int */
	protected $sectionId;

	/** @var \Admin\Utils\ISectionDetectorFactory */
	private $sectionDetector;

	/** @var \Model\SectionFacade */
	private $sectionFacade;

	/** @var \Admin\SectionFormItemFactory */
	private $sectionFormItemFactory;

	/**
	 * @param int $sectionId
	 * @param \Model\PageFacade $pageFacade
	 * @param \Model\PageFilter $pageFilter
	 * @param \Model\SectionFilter $sectionFilter
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Admin\SectionFormItemFactory $sectionFormItemFactory
	 * @param \Admin\Utils\ISectionDetectorFactory $sectionDetectorFactory
	 */
	function __construct($sectionId, PageFacade $pageFacade, PageFilter $pageFilter, SectionFilter $sectionFilter, SectionFacade $sectionFacade, SectionFormItemFactory $sectionFormItemFactory, ISectionDetectorFactory $sectionDetectorFactory)
	{
		parent::__construct();
		$this->sectionId = (int) $sectionId;
		$this->pageFacade = $pageFacade;
		$this->pageFilter = $pageFilter;
		$this->sectionFilter = $sectionFilter;
		$this->sectionId = $sectionId;
		$this->sectionDetector = $sectionDetectorFactory->create();
		$this->sectionFacade = $sectionFacade;
		$this->sectionFormItemFactory = $sectionFormItemFactory;
	}

	protected function createForm()
	{
		$form = new Form;

		if ($this->isFrontSection()) {
			$form->addContainer('section')['section'] = $this->sectionFormItemFactory->createMultiselectBox();
		}
		$base = $form->addContainer('base');
		$base->addTextNull('name', 'Název');
		$base->addCheckbox('show', 'Zobrazit')
			->setDefaultValue(TRUE);
		$base->addTextAreaNull('content', 'Obsah stránky')
			->setAttribute('class', 'mceEditorSimple');
		$form->addSubmit('save', 'Uložit');

		return $form;
	}

	public function add(Form $form)
	{
		$data = $form['base']->getValues(TRUE);
		$data['section_id'] = $this->sectionId;
		$page = $this->pageFacade->add($data);
		$this->id = $page->id;
		$this->reassignData($form);
	}

	public function edit(Form $form)
	{
		$data = $form['base']->getValues(TRUE);
		$this->pageFacade->edit($this->id, $data);
		$this->reassignData($form);
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	private function reassignData(Form $form)
	{
		if ($this->isFrontSection()) {
			$this->pageFacade->setSection($this->id, $form['section']['section']->getValue());
		}
	}

	public function load($id)
	{
		$page = $this->getPage($id)
			->select('page.*')
			->fetch();

		if (!$page) {
			throw new RecordNotFoundException;
		}

		$this->id = $id;
		$this['form']['base']->setDefaults($page->toArray());
		if ($this->isFrontSection()) {
			$this['form']['section']['section']->setDefaultValue($this->getPageSection($this->id));
		}
	}

	/**
	 * @param int $id
	 * @return \Nette\Database\Table\Selection
	 */
	private function getPage($id) {
		$context = $this->pageFacade->all();
		$this->sectionFilter->filterId($context, $this->sectionId);
		return $this->pageFilter->filterId($context, $id);
	}

	/**
	 * @param int $pageId
	 * @return array
	 */
	function getPageSection($pageId)
	{
		$context = $this->sectionFacade->all();
		$this->pageFilter->filterId($context, $pageId, ':page_has_section');

		return $context->fetchPairs('id', 'id');
	}

	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return bool
	 */
	private function isFrontSection()
	{
		return $this->sectionDetector->isFrontSection($this->sectionId);
	}

}

interface IPageFormFactory
{
	/**
	 * @var int $sectionId
	 * @return PageForm
	 */
	public function create($sectionId);
}
