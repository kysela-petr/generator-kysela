<?php
/**
 * @author Martin Kovařčík.
 */
namespace Admin\Content;

use Admin\BaseForm;
use Model\MenuFacade;
use Model\MenuFilter;
use Model\SectionFilter;
use Admin\MenuFormItemFactory;
use Admin\MenuTypeOptionFactory;
use Admin\MenuTreeFactory;
use Admin\IMenuMoverFactory;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Admin\LazyContainer;
use Model\RecordNotFoundException;
use Admin\MenuTreeMoverAdapter;

class MenuForm extends BaseForm
{

	/** @var int */
	protected $sectionId;

	/** @var \Admin\LazyItemMap */
	protected $lazyItemMap;

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var MenuFilter */
	protected $menuFilter;

	/** @var SectionFilter */
	protected $sectionFilter;

	/** @var MenuFormItemFactory */
	protected $menuFormItemFactory;

	/** @var MenuTypeOptionFactory */
	protected $menuTypeOptionFactory;

	/** @var MenuTreeFactory */
	protected $menuTreeFactory;

	/** @var IMenuMoverFactory */
	protected $menuMoverFactory;

	/** @var int */
	protected $id;

	function __construct($sectionId, $lazyItemMap, MenuFacade $menuFacade, MenuFilter $menuFilter, SectionFilter $sectionFilter, MenuFormItemFactory $menuFormItemFactory, MenuTypeOptionFactory $menuTypeFormItemFactory, MenuTreeFactory $menuTreeFactory, IMenuMoverFactory $menuMoverFactory)
	{
		parent::__construct();
		$this->sectionId = $sectionId;
		$this->lazyItemMap = $lazyItemMap;
		$this->menuFacade = $menuFacade;
		$this->menuFilter = $menuFilter;
		$this->sectionFilter = $sectionFilter;
		$this->menuFormItemFactory = $menuFormItemFactory;
		$this->menuTypeOptionFactory = $menuTypeFormItemFactory;
		$this->menuTreeFactory = $menuTreeFactory;
		$this->menuMoverFactory = $menuMoverFactory;
	}

	protected function createForm()
	{
		$form = new Form;

		Container::extensionMethod('addLazyContainer', function ($container, $name, $factory) {
			return $container[$name] = new LazyContainer($factory);
		});

		$base = $form->addContainer('base');
		$base->addText('name', 'Název');
		$type = $base->addSelect('type', 'Typ položky', $this->menuTypeOptionFactory->getOptionsBySection($this->sectionId));
		$type->setDefaultValue(key($type->getItems()));
		$type->setAttribute('class', 'change-ajax-submit');

		$base->addCheckbox('show', 'Zobrazit')
			->setDefaultValue(TRUE);

		$parent = $form->addContainer('parent');
		$menu = $parent['id_menu'] = $this->menuFormItemFactory->create($this->sectionId);
		$menu->setPrompt('---');
		$menu->caption = 'Rodičovská kategorie';

		$form->addLazyContainer('content', function (LazyContainer $self) use ($form, $type) {
			$values = $self->getFormValues();
			$itemType = $values['base']['type'];
			$this->lazyItemMap->get($itemType)
				->setup($self, $this->sectionId);
		});

		$form->addSubmit('save', 'Uložit');

		$form->onValidate[] = $this->validateNonCircular;
		$form->onValidate[] = $this->validateAjax;
		$form->onValidate[] = $this->validateLazyItem;

		return $form;
	}

	public function validateLazyItem(Form $form)
	{
		$this->lazyItemMap->get($form['base']['type']->getValue())
			->validate($form['content']);
	}

	public function add(Form $form)
	{
		$baseData = $form['base']->getValues();
		$baseData['section_id'] = $this->sectionId;
		$menu = $this->menuFacade->add($baseData);
		$this->id = $menu->id;
		$menuMover = $this->menuMoverFactory->createForcer($this->sectionId);
		$this->saveCommon($menu->id, $form, $menuMover);
	}

	public function edit(Form $form)
	{
		$this->menuFacade->edit($this->id, $form['base']->getValues());
		$menuMover = $this->menuMoverFactory->create($this->sectionId);
		$this->saveCommon($this->id, $form, $menuMover);
	}

	public function load($id)
	{
		$context = $this->menuFacade->all()
			->select('menu.*, :menu_has_menu.parent.id AS id_menu');

		$this->sectionFilter->filterId($context, $this->sectionId);
		$this->menuFilter->filterId($context, $id);
		$section = $context->fetch();

		if (!$section) {
			throw new RecordNotFoundException("Menu with ID $id does not exists!");
		}

		$this->id = $id;
		$sectionData = $section->toArray();
		$form = $this['form'];

		$form['base']->setDefaults($sectionData);
		$form['parent']->setDefaults($sectionData);
		$this->lazyItemMap->get($section['type'])
			->load($id, $form['content']);
	}

	public function validateNonCircular(Form $form)
	{
		$parentId = $this->getParentIdFromForm($form);

		if ($this->menuTreeFactory->create($this->sectionId)->isParent($this->id, $parentId)) {
			$form['parent']['id_menu']->addError('Detekce cyklické závislosti');
		}
	}

	protected function saveCommon($id, Form $form, MenuTreeMoverAdapter $menuMover)
	{
		$parentId = $this->getParentIdFromForm($form);
		$menuMover->moveTo($id, $parentId);

		$this->lazyItemMap->get($form['base']['type']->getValue())
			->save($id, $form['content']);
	}

	protected function getParentIdFromForm(Form $form)
	{
		return $form['parent']['id_menu']->getValue();
	}

	/**
	 * Validuje formular zaslany ajaxem
	 * @param Form $form
	 */
	public function validateAjax(Form $form)
	{
		if ($this->presenter->isAjax()) {
			$form->cleanErrors();

			foreach ($form->getControls() as $control) {
				$control->cleanErrors();
			}

			$form->onSuccess = [];

			$this->redrawControl();
		}
	}

	/**
	 * @param int $id
	 */
	public function setDefaultMenuParent($id)
	{
		$this['form']['parent']['id_menu']->setDefaultValue($id);
	}

	/**
	 * @return int
	 */
	function getId()
	{
		return $this->id;
	}

}

interface IMenuFormFactory
{

	/**
	 * @param int $sectionId
	 * @return MenuForm
	 */
	public function create($sectionId);
}
