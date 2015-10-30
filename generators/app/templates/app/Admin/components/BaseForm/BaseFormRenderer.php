<?php

namespace Admin;

use Nette,
	Nette\Forms\Controls;

class BaseFormRenderer extends \Nette\Forms\Rendering\DefaultFormRenderer {

	public function __construct() {
		$this->wrappers['controls']['container'] = NULL;
		$this->wrappers['pair']['container'] = 'div class=form-group';
		$this->wrappers['pair']['.error'] = 'has-error';
		$this->wrappers['control']['container'] = 'div class=col-sm-9';
		$this->wrappers['label']['container'] = 'div class="col-sm-2 control-label"';
		$this->wrappers['control']['.odd'] = '';
		$this->wrappers['control']['.buttons'] = 'col-sm-offset-2';
		$this->wrappers['control']['.submit'] = '';
		$this->wrappers['control']['.text'] = '';
		$this->wrappers['control']['.password'] = '';
		$this->wrappers['control']['.file'] = '';
		$this->wrappers['control']['description'] = 'span class=help-block';
		$this->wrappers['control']['errorcontainer'] = 'span class=help-block';
	}

	/**
	 * Prida tridy vsem tlacitkum
	 * @param array|\Traversable $iterator
	 */
	public function classButtons($iterator) {
		$usedPrimary = false;

		if ($iterator instanceof Nette\Application\UI\Form) {
			$iterator = $iterator->getComponents(true, '\Nette\Forms\Controls\Button');
		}

		foreach ($iterator as $button) {
			$button->setAttribute('class', empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-inverse');

			if (!$button->getOption('notPrimary')) {
				$usedPrimary = TRUE;
			}
		}
	}

	public function renderPairMulti(array $controls) {
		$rendered = parent::renderPairMulti($controls);

		$class = $this->getValue('control .buttons');

		$firstReplace = Nette\Utils\Strings::replace($rendered, '~(form-group">\s*<div) class="~', "\\0$class ");
		return Nette\Utils\Strings::replace($firstReplace, '~(form-group">\s*<div)>~', "\\1 class=\"$class\">");
	}

	/**
	 * Nastavi jednoduche tridy
	 * Pouze kvuli vlastnimu vykreslovani
	 * @param Form $form
	 */
	public function setupSimpleClasses($form) {
		$this->classButtons($form);
		foreach ($form->getControls() as $control) {
			if ($control instanceof \Nette\Forms\Controls\Button) {
				continue;
			} else {
				$controlClass = $control->getControlPrototype()->class;
				if (strpos($controlClass, 'select2') === false) {
					$control->getControlPrototype()->class("form-control $controlClass");
				}
				$control->getLabelPrototype()->class('control-label ');
			}
		}
	}

	/**
	 * Nastavi vychozi tridy vsem elementum
	 * @param Form $form
	 */
	public function setupDefaultClasses($form) {
		if ($form->getElementPrototype()->class)
			$form->getElementPrototype()->class = [$form->getElementPrototype()->class];

		$form->getElementPrototype()->class[] = 'form-horizontal';

		$this->classButtons($form);

		foreach ($form->getControls() as $control) {
			if ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox || $control instanceof \Vodacek\Forms\Controls\DateInput || $control instanceof Controls\UploadControl) {
				if ($control instanceof Controls\UploadControl) {
					continue;
				}

				if ($control->getControlPrototype()->class) {
					$control->getControlPrototype()->class = [$control->getControlPrototype()->class];
				}

				if (!$control->getOption('no-form-control')) {
					$control->getControlPrototype()->class[] = 'form-control';
				}

				if ($control->isRequired()) {
					$control->getControlPrototype()->class[] = 'required';
				}
			} elseif ($control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
				$control->getSeparatorPrototype()->setName('div')->class($control->getControlPrototype()->type);
			} elseif ($control instanceof Controls\Checkbox) {
				$control->getLabelPrototype()->class[] = 'checkbox';
			}
		}
	}

	public function render(Nette\Forms\Form $form, $mode = NULL) {
		$this->setupDefaultClasses($form);
		return parent::render($form, $mode);
	}


	public function setForm($form) {
		$this->form = $form;
		return $this;
	}

}
