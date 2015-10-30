<?php

namespace Admin;

use Nette\Utils\Callback;

/**
 * @author Petr Hlavac
 * @author Svata
 */
class LazyContainer extends \Nette\Forms\Container {

	protected $factory;
	private $initialized = false;

	public function __construct($factory) {
		parent::__construct();
		$this->factory = Callback::check($factory);
	}

	protected function initialize($force = false) {
		if (!$force && $this->initialized) {
			return;
		}

		$this->initialized = true;

		foreach (parent::getComponents() as $component) {
			$this->removeComponent($component);
		}

		call_user_func($this->factory, $this);
	}

	public function getComponents($deep = FALSE, $filterType = NULL) {
		$form = $this->getForm(false);

		if ($form && $form->isAnchored()) {
			$this->initialize();
		}

		return parent::getComponents($deep, $filterType);
	}

	public function setValues($values, $erase = FALSE) {
		$this->initialize(true);

		return parent::setValues($values, $erase);
	}

	public function getFormValues(\Nette\Forms\Container $container = null) {
		$container = $container ? : $this->form;

		if ($this->form->isSubmitted()) {
			if ($container instanceof \Nette\Forms\Form) {
				return $container->getHttpData();
			}
			return $this->extractContainerData($this->form->getHttpData(), $container->lookupPath('\Nette\Forms\Form'));
		}
		return $container->getValues();
	}

	private function extractContainerData($data, $path) {
		return $this->extractContainerDataArrayPath($data, explode('-', $path));
	}

	private function extractContainerDataArrayPath($data, $path) {
		if (count($path) == 1) {
			return $this->extractContainerDataEndPath($data, reset($path));
		}
		$key = array_shift($path);
		return $this->extractContainerDataArrayPath($data[$key], $path);
	}

	private function extractContainerDataEndPath($data, $path) {
		if ($path === "") {
			throw new \InvalidArgumentException();
		}
		return $data[$path];
	}

}
