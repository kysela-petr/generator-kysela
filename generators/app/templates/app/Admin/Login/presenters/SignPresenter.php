<?php

namespace Admin\Login;

use Admin\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Security as NS;

class SignPresenter extends BasePresenter
{

	/** @var \Admin\Login\ISignFormFactory @inject */
	public $signFormFactory;

	/** @var \Admin\Login\ILostPasswordFormFactory @inject */
	public $lostPasswordFormFactory;

	/** @var \Admin\Login\IRenewPasswordFormFactory @inject */
	public $renewPasswordFormFactory;

	/** @var \Model\TokenProvider @inject */
	public $tokenProvider;

	/** @var string @persistent */
	public $restoreKey;

	/** @var bool|mixed|\Nette\Database\Table\IRow */
	private $tokenRow;

	public function checkLogin()
	{
	}

	public function checkRights()
	{
	}

	public function actionIn()
	{
		if ($this->user->isLoggedIn()) {
			$this->redirectHomepage();
		}
	}

	public function actionLost()
	{
		if ($this->user->isLoggedIn()) {
			$this->redirectHomepage();
		}
	}

	public function actionLogout()
	{
		$this->getUser()->logout();
		$this->flashSuccess('Odhlášení bylo úspěšné');
		$this->redirect('in');
	}

	public function actionRenew($token)
	{
		$row = $this->tokenProvider->valid($token)
			->limit(1)
			->fetch();

		if ($row !== FALSE) {
			$this->tokenRow = $row;
		} else {
			$this->flashMessage('Link pre obnovu hesla nie je platný.', 'error');
			$this->redirect('lost');
		}
	}


	/**
	 * @return \Admin\Login\SignForm
	 */
	public function createComponentSignForm()
	{
		$signForm = $this->signFormFactory->create();
		$signForm['form']->onError[] = $this->formError;
		$signForm['form']->onSuccess[] = $this->formSuccess;

		return $signForm;
	}

	/**
	 * @return \Admin\Login\LostPasswordForm
	 */
	protected function createComponentLostForm()
	{
		$component = $this->lostPasswordFormFactory->create();
		$component['form']->onError[] = function (Form $form) {
			$this->formError($form);
		};
		$component->onEmailSend[] = function ($message) {
			$this->flashMessage($message);
			$this->redirect('lost');
		};
		$component->onRecordNotFound[] = function ($message) {
			$this->flashMessage($message);
			$this->redirect('lost');
		};

		return $component;
	}

	/**
	 * @return \Admin\Login\RenewPasswordForm
	 */
	protected function createComponentRenewForm()
	{
		$component = $this->renewPasswordFormFactory->create($this->tokenRow->email, $this->tokenRow->token);

		$component['form']->onError[] = function (Form $form) {
			$this->formError($form);
		};
		$component->onPasswordChanged[] = function ($message) {
			$this->flashMessage($message);
			$this->redirect('in');
		};
		$component->onRecordNotFound[] = function ($message) {
			$this->flashMessage($message);
			$this->redirect('lost');
		};

		return $component;
	}

	/**
	 * @param $form
	 */
	public function formError(Form $form)
	{
		foreach ($form->errors as $error) {
			$this->flashMessage($error, 'error');
		}

		$this->redirectThis();
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function formSuccess(Form $form)
	{
		try {
			$values = $form->getValues();
			$this->user->login($values->login, $values->password);
			$this->flashMessage('Přihlášení bylo úspěšné.');
			$this->restoreRequest($this->restoreKey);
			$this->redirectHomepage();
		} catch (NS\AuthenticationException $e) {
			$this->flashMessage('Přihlášení se nezdařilo.');
			$this->redirectThis();
		}
	}

}
