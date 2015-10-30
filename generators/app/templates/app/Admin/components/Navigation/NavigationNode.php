<?php

namespace Admin;

use Nette\Utils\Strings;

class NavigationNode extends \Esports\Navigation\NavigationNode {

	/** @var array */
	protected $options = [];

	/** @var array */
	protected $alternatives = [];

	/** @var array */
	public $params = [];

	public function add($label, $url, $params = []) {
		$node = parent::add($label, $url);
		$node->params = $params;

		return $node;
	}

	public function setOption($key, $value) {
		$this->options[$key] = $value;

		return $this;
	}

	public function addAlternative($presenter) {
		$this->alternatives[] = $presenter;

		return $this;
	}

	public function getOption($key) {
		if (isset($this->options[$key])) {
			return $this->options[$key];
		}

		return null;
	}

	public function getNavigationPresenter() {
		$node = $this->lookup('Esports\Navigation\Navigation');

		if ($node) {
			return $node->getPresenterName();
		} else {
			return null;
		}
	}

	public function getNavigationParameters() {
		$node = $this->lookup('Esports\Navigation\Navigation');

		if ($node) {
			return $node->getParameters();
		} else {
			return null;
		}
	}

	protected function isActive($url) {
		$presenter = $this->getNavigationPresenter();
		$params = $this->getNavigationParameters();

		if (!$url) {
			return false;
		}

		$url = substr($url, 1);

		$active = (bool) Strings::match($presenter, "~^{$url}~");

		if ($active && count($this->params)) {
			foreach ($this->params as $key => $value) {
				if (!(isset($params[$key]) && $params[$key] == $value)) {
					return false;
				}
			}
		}

		return $active;
	}

	public function hasActive() {
		if ($this->isActive($this->url)) {
			return true;
		}

		foreach ($this->alternatives as $alternative) {
			if ($this->isActive($alternative)) {
				return true;
			}
		}

		foreach ($this->components as $component) {
			if ($component->hasActive()) {
				return true;
			}
		}

		return false;
	}

}
