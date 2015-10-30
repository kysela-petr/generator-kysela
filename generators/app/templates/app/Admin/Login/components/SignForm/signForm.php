<?php

namespace Admin\Login;

use App\Component\Component;
use Nette\Application\UI\Form;

class SignForm extends Component
{

	/**
	 * Vytvori prihlasovaci formular
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->getElementPrototype()->id = 'loginform';

		$form->addText('login', NULL)
			->setAttribute('placeholder', 'Login')
			->setRequired('Zadejte prosím login.');

		$form->addPassword('password', NULL)
			->setAttribute('placeholder', 'Heslo')
			->setRequired('Zadejte prosím heslo.');

		$form->addSubmit('makeLogin', 'Přihlásit');

		return $form;
	}

	protected function prepareTemplate(\Nette\Application\UI\ITemplate $template)
	{
		parent::prepareTemplate($template);
		$this->template->setFile(__DIR__ . '/signForm.latte');
	}

}

interface ISignFormFactory
{

	/**
	 * @return SignForm
	 */
	public function create();
}
