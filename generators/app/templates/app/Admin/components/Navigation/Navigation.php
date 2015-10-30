<?php

namespace Admin;

use Nette\Localization\ITranslator;

class Navigation extends \Esports\Navigation\Navigation {

	/** @var string */
	protected $presenterName;

	/** @var array */
	protected $parameters = [];

	/** @var  ITranslator */
	protected $translator;

	protected function createComponentHomepage($name) {
		new NavigationNode($this, $name);
	}

	public function add($label, $url, $params = []) {
		$node = parent::add($label, $url);
		$node->params = $params;

		return $node;
	}

	public function getPresenterName() {
		return $this->presenterName;
	}

	public function getParameters() {
		return $this->parameters;
	}

	public function setPresenterName($presenterName) {
		$this->presenterName = $presenterName;
	}

	public function setParameters($parameters) {
		$this->parameters = $parameters;
	}

	/**
	 * @param ITranslator $translator
	 */
	public function setTranslator($translator)
	{
		$this->translator = $translator;
	}

	protected function createTemplate()
	{
		$template = parent::createTemplate();
		if($this->translator) {
			$template->setTranslator($this->translator);
		}
		return $template;
	}


}
