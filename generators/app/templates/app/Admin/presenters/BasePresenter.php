<?php

namespace Admin;

use Nette\Application\UI\Presenter;
use Nette\Application\BadRequestException;

/**
 * Base presenter for Admin Module
 */
abstract class BasePresenter extends Presenter {

	use \Esports\Helper\ISHelperLoader;

	/** @var \Esports\Utils\PresenterBridge @inject */
	public $presenterBridge;

	/** @var \WebLoader\Nette\LoaderFactory @inject */
	public $webLoader;

	/** @var \Esports\Helper\Loader @inject */
	public $helperLoader;

	/** @var \Esports\Bakery\BakeryFactory @inject */
	public $bakeryFactory;

	/** @var \Nette\Localization\ITranslator @inject */
	public $translator;

	/** @var \Model\Authorizator @inject */
	public $authorizator;

	/** @var \Esports\Privileges\RightChecker @inject */
	public $rightChecker;

	/** @var \Admin\IUserSectionViewFactory @inject */
	public $userSectionViewFactory;

	/** @var IMainMenuFactory @inject */
	public $mainMenuFactory;

	/** @var \Admin\IUserSectionProviderFactory @inject */
	public $userSectionProviderFactory;

	protected function startup() {
		parent::startup();
		$this->presenterBridge->setPresenter($this->getPresenter());
		$this->setupUser();
		$this->checkLogin();
		$this->checkRights();
	}

	/**
	 * Zajisti nastaveni autentifikace a autorizace
	 * pro uzivatele Nette\Security\User
	 */
	protected function setupUser() {
		$user = $this->getUser();
		$user->getStorage()->setNamespace("Admin");
		$user->setAuthenticator($this->context->getService('adminAuthenticator'));
		$user->setAuthorizator($this->authorizator);
	}

	protected function beforeRender() {
		parent::beforeRender();

		$template = $this->template;
		$template->setTranslator($this->translator);
		$this->registerHelperLoader($template, $this->helperLoader);
	}

	protected function checkLogin() {
		if (!$this->user->loggedIn) {
			$this->redirectSign();
		}
	}

	protected function checkRights() {
		$resource = $this->name;
		$action = $this->getAction();
		$signal = $this->getSignal();

		try {
			$this->rightChecker->check($resource, $action, $signal);
		} catch (\Esports\Privileges\PermissionException $e) {
			$this->flashError('Nemáte oprávnění k provedení akce');
			$this->redirectHomepage();
		}
	}

	/**
	 * Vytvori flash zpravu typu success
	 * @param string $msg
	 */
	public function flashSuccess($msg) {
		$this->flashMessage($msg, 'success');
	}

	/**
	 * Vytvori flash zpravu typu info
	 * @param string $msg
	 */
	public function flashInfo($msg) {
		$this->flashMessage($msg, 'info');
	}

	/**
	 * Vytvori flash zpravu typu warning
	 * @param string $msg
	 */
	public function flashWarning($msg) {
		$this->flashMessage($msg, 'warning');
	}

	/**
	 * Vytvori flash zpravu typu error
	 * @param string $msg
	 */
	public function flashError($msg) {
		$this->flashMessage($msg, 'danger');
	}

	/**
	 * Vyhazuje vyjimku BadRequestException
	 * @param string $msg Chybova zprava
	 * @throws \Nette\Application\BadRequestException
	 */
	public function pageNotFound($msg = '') {
		throw new BadRequestException($msg);
	}

	public function redirectDefault() {
		$this->redirect('default');
	}

	public function redirectThis() {
		$this->redirect('this');
	}

	public function redirectEdit($id) {
		$this->redirect('edit', $id);
	}

	public function redirectSign() {
		$restoreKey = $this->storeRequest();
		$this->redirect(':Admin:Login:Sign:in', ['restoreKey' => $restoreKey]);
	}

	public function redirectHomepage() {
		$this->redirect(':Admin:Dashboard:Homepage:');
	}

	/** @return CssLoader */
	protected function createComponentCssAdmin() {
		return $this->webLoader->createCssLoader('admin');
	}

	/** @return JavaScriptLoader */
	protected function createComponentJsAdmin() {
		return $this->webLoader->createJavaScriptLoader('admin');
	}

	protected function createComponentNavigation() {
		$bakery = $this->bakeryFactory->create(':Admin:Dashboard:Homepage:', 'Úvod');
		$bakery->setLinkCallback($this->link);
		$bakery->setTranslator($this->translator);
		$bakery->setTemplate(__DIR__ . '/../templates/breadcrumbs.latte');

		$bakery->setPresenter($this->name);
		$bakery->setAction($this->action);
		$bakery->setParameters($this->getParameters());

		return $bakery->create();
	}

	/**
	 * @return MainMenu
	 */
	protected function createComponentMainMenu() {
		$menu = $this->mainMenuFactory->create();
		$menu->setPresenter($this->name);
		$menu->setAction($this->action);
		$menu->setParameters($this->getParameters());

		return $menu;
	}

	/**
	 * @return \Admin\UserSectionView
	 */
	protected function createComponentUserSectionView()
	{
		return $this->userSectionViewFactory->create();
	}

	/**
	 * @return \Admin\PhotoUploadForm
	 */
	public function createComponentPhotoUploadModal()
	{
		$component = $this->photoUploadFormFactory->create();
		return $component;
	}

}
