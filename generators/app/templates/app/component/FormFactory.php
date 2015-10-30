<?php

namespace App\Component;

use Admin\BaseFormRenderer;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Object;

class FormFactory extends Object
{
	/** @var ITranslator */
	protected $translator;

	/**
	 * @param \Nette\Localization\ITranslator $translator
	 */
	function __construct(ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * @return Form
	 */
	public function create()
	{
		$form = new Form;
		$form->setTranslator($this->translator);
		$form->setRenderer(new BaseFormRenderer);
		return $form;
	}
}
