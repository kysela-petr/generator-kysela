<?php

namespace Admin;

use Model\RecordNotFoundException;

abstract class DataFormPresenter extends DataPresenter
{

	protected $formFactory;

	public $onFormAddCreated = [];

	public $onFormEditCreated = [];

	public function actionEdit($id)
	{
		try {
			$this['formEdit']->load($id);
		} catch (RecordNotFoundException $e) {
			$this->flashError('Požadovaný záznam neexistuje!');
			$this->redirectDefault();
		}
	}


	protected function createForm()
	{
		return $this->formFactory->create();
	}

	protected function prepareForm($form)
	{
		$form['form']->onError[] = $this->formErrorHandle;
		$form->addBack($this->link('default'));
	}

	protected function createComponentFormEdit()
	{
		$form = $this->createForm();
		$this->onFormEditCreated($form);
		$form['form']->onSuccess[] = $form->edit;
		$this->prepareForm($form);
		$this->formSuccessHandle($form['form'], 'Záznam byl uložen!');

		return $form;
	}

	protected function createComponentFormAdd()
	{
		$form = $this->createForm();
		$this->onFormAddCreated($form);
		$form['form']->onSuccess[] = $form->add;
		$this->prepareForm($form);
		$this->formSuccessHandle($form['form'], 'Záznam byl vytvořen!');

		return $form;
	}

	protected function formSuccessHandle($form, $msg)
	{
		$form->onSuccess[] = function () use ($form, $msg) {
			$this->flashSuccess($msg);
		};

		$form->onSuccess['redirect'] = $this->checkFormRedirect;
	}

	public function checkFormRedirect($form)
	{
		$this->redirectDefault();
	}

	public function formErrorHandle($form)
	{
		foreach ($form->errors as $error) {
			$this->flashError($error);
		}
	}

	/**
	 * Nastavi vychozi sablonu s datagridem a nastavi text tlacitku novy zaznam
	 * @param string $labelNew
	 */
	public function simpleList($labelNew, $widgetTitle = 'Akce', $widgetIcon = 'fa-bolt')
	{
		$this->template->setFile(__DIR__ . '/default.latte');
		$this->template->labelNew = $labelNew;
		$this->template->widgetTitle = $widgetTitle;
		$this->template->widgetIcon = $widgetIcon;
	}

	public function renderAdd()
	{
		$this->template->setFile(__DIR__ . '/add.latte');
	}

	public function renderEdit($id)
	{
		$this->template->setFile(__DIR__ . '/edit.latte');
	}

}
