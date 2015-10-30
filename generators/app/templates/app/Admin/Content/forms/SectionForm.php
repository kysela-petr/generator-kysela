<?php

namespace Admin\Content;

use Admin\BaseForm;
use Model\RecordNotFoundException;
use Model\SectionFacade;
use Model\SectionFilter;
use Admin\ModuleFormItemFactory;
use Admin\MenuTypeOptionFactory;
use Nette\Application\UI\Form;

/**
 * Formular pro spravu sekci webu
 * @author Petr Hlavac
 */
class SectionForm extends BaseForm
{

	/** @var SectionFacade */
	protected $sectionFacade;

	/** @var SectionFilter */
	protected $sectionFilter;

	/** @var ModuleFormItemFactory */
	protected $moduleFormItemFactory;

	/** @var MenuTypeOptionFactory */
	protected $menuTypeOptionFactory;

	/** @var int */
	protected $id;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Model\SectionFilter $sectionFilter
	 * @param \Admin\ModuleFormItemFactory $moduleFormItemFactory
	 * @param \Admin\MenuTypeOptionFactory $menuTypeFormItemFactory
	 */
	function __construct(SectionFacade $sectionFacade, SectionFilter $sectionFilter, ModuleFormItemFactory $moduleFormItemFactory, MenuTypeOptionFactory $menuTypeFormItemFactory)
	{
		parent::__construct();
		$this->sectionFacade = $sectionFacade;
		$this->sectionFilter = $sectionFilter;
		$this->moduleFormItemFactory = $moduleFormItemFactory;
		$this->menuTypeOptionFactory = $menuTypeFormItemFactory;
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createForm()
	{
		$form = new Form;
		$base = $form->addContainer('base');
		$type = $form->addContainer('type');

		$base->addText('name', 'Název sekce');
		$base->addText('domain', 'Doména třetího řádu');
		$base->addText('priority', 'Priorita')
			->setDefaultValue(0);
		$base['module'] = $this->moduleFormItemFactory->create();
		$type->addMultiSelect('menu_type_code', 'Povolené typy položek menu', $this->menuTypeOptionFactory->getOptions())
			->setAttribute('class', 'select2');
		$form->addSubmit('save', 'Uložit');

		return $form;
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function add(Form $form)
	{
		$section = $this->sectionFacade->add($form['base']->getValues());
		$this->id = $section->id;
		$this->saveData($this->id, $form);
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function edit(Form $form)
	{
		$this->sectionFacade->edit($this->id, $form['base']->getValues());
		$this->saveData($this->id, $form);
	}

	/**
	 * @param $id
	 * @throws \Model\RecordNotFoundException
	 */
	public function load($id)
	{
		$section = $this->sectionFilter->filterId($this->sectionFacade->all(), $id)
			->fetch();

		if (!$section) {
			throw new RecordNotFoundException("Section with ID $id does not exists!");
		}

		$this->id = $id;
		$typeCodes = array_keys($this->sectionFacade->getMenuType($id)->fetchPairs('code'));
		$form = $this['form'];
		$form['base']->setDefaults($section->toArray());
		$form['type']['menu_type_code']->setDefaultValue($typeCodes);
	}

	/**
	 * @return int
	 */
	function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @param Form $form
	 */
	protected function saveData($id, Form $form)
	{
		$this->sectionFacade->setMenuType($id, $form['type']['menu_type_code']->getValue());
	}

}

interface ISectionFormFactory
{

	/**
	 * @return SectionForm
	 */
	public function create();
}
