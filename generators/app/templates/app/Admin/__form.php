<?php

/**
 * TOTO JE UKAZKOVA SABLONA PRO FORM
 * 1. Zkontroluj namespace a dopln autora.
 * 2. Nahrad XXX za 'Nazev' a xxx za 'nazev'.
 * 3. Zaregistruj Grid do configu: app/config/form.services.neon
 * 4. Odstran tento a vytvor vlastni komentar.
 * 5. Sablonu klidne dopln nebo oprav.
 */

namespace Admin\Statistics;

use Admin\BaseForm;
use Model\XXXFacade;
use Model\RecordNotFoundException;
use Nette\Application\UI\Form;

/**
 * @author Generator
 */
class XXXForm extends BaseForm {

	/** @var XXXFacade */
	protected $xxxFacade;

	/** @var int */
	protected $xxxId;

	function __construct(XXXFacade $xxxFacade) {
		parent::__construct();
		$this->xxxFacade = $xxxFacade;
	}

	/**
	 * @return Form
	 */
	protected function createForm() {
		$form = new Form;
		$form->addGroup('Základní údaje');
		$basic = $form->addContainer('basic');
		$basic->addText('name', 'Name');
		$form->addSubmit('save', 'Uložit');
		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function edit(Form $form) {
		$values = $form['basic']->getValues();
		$this->xxxFacade->edit($this->xxxId, $values);
	}

	/**
	 * @param int $id
	 * @throws \Model\RecordNotFoundException
	 */
	public function load($id) {
		$record = $this->xxxFacade->all()->get($id);

		if (!$record) {
			throw new RecordNotFoundException;
		}

		$this['form']['basic']->setDefaults($record);
		$this->xxxId = $id;
	}

	/**
	 * @param Form $form
	 */
	public function add(Form $form) {
		$values = $form['basic']->getValues();
		$record = $this->xxxFacade->add($values);
		$this->xxxId = $record->id;
	}

}

interface IXXXFormFactory {

	/**
	 * @return XXXForm
	 */
	public function create();
}
