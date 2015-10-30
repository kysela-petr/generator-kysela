<?php

namespace App\Component;

use Esports\Helper\Loader;
use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;

class Component extends Control
{

	use \Esports\Helper\ISHelperLoader;

	/** @var \Nette\Localization\ITranslator */
	protected $translator;

	/** @var \Esports\Helper\Loader */
	protected $helperLoader;

	public function render()
	{
		$template = $this->template;
		$this->prepareTemplate($template);
		$template->render();
	}

	/**
	 * Pridani prekladace
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function setTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * Pridani loaderu pro nacitani helperu
	 * @param \Esports\Helper\Loader $helperLoader
	 */
	public function setHelperLoader(Loader $helperLoader)
	{
		$this->helperLoader = $helperLoader;
	}

	/**
	 * @param \Nette\Application\UI\ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		$template->setTranslator($this->translator);
		$this->registerHelperLoader($template, $this->helperLoader);
	}
}
