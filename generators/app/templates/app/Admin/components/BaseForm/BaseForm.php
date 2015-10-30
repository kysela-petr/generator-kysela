<?php

namespace Admin;

use Nette\Application\UI\ITemplate;
use Nette\Utils\Html;

abstract class BaseForm extends Component
{

	/** @var bool */
	protected $hasWidgetBox = TRUE;

	/**
	 * Vytvari formular
	 * @return \Nette\Application\UI\Form
	 */
	abstract protected function createForm();

	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentForm()
	{
		$form = $this->createForm();

		return $form;
	}

	/**
	 * Nastavi helpery a translator pro form i vlastni sablonu
	 * @param \Nette\Application\UI\ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		parent::prepareTemplate($template);
		$template->setFile(__DIR__ . '/BaseForm.latte');
		$this['form']->setTranslator($this->translator);
		$this['form']->setRenderer(new BaseFormRenderer());
	}

	public function addBack($link, $name = 'Zpět')
	{
		$this['form']->addSubmit('back')
			->setAttribute('class', 'btn btn-inverse')
			->getControlPrototype()
			->setName('a')
			->href($link)
			->add(Html::el('i')->class('fa fa-reply'))
			->add(Html::el('span', ' '))
			->add(Html::el('span', $this->translator->translate($name)));
	}

	/**
	 * Ma se vykreslit widget-box?
	 * @return bool
	 */
	public function hasWidgetBox()
	{
		return $this->hasWidgetBox;
	}

	/**
	 * Nastaveni vykresleni widget-box
	 * @param bool $allow [optional]
	 * @return $this
	 */
	public function setWidgetBox($allow = TRUE)
	{
		$this->hasWidgetBox = (bool)$allow;

		return $this;
	}

	/**
	 * Vrati slozku s helpery
	 * @return string
	 */
	public function getHelpersDir()
	{
		return __DIR__;
	}

	/**
	 * Vrati cestu k helperum
	 * @return string
	 */
	public function getHelpersLatte()
	{
		return $this->getHelpersDir() . '/formHelpers.latte';
	}

}
