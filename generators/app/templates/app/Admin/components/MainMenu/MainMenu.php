<?php

namespace Admin;

use Esports\Privileges\RightChecker;
use Nette\Application\UI\ITemplate;
use Nette\Utils\Strings;

/*
 * Hlavni menu aplikace
 */

class MainMenu extends \App\Component\Component
{

	/** @var RightChecker */
	protected $rightChecker;

	/** @var string */
	protected $presenter;

	/** @var string */
	protected $action;

	/** @var array */
	protected $parameters = [];

	/**
	 * @param \Esports\Privileges\RightChecker $rightChecker
	 */
	function __construct(RightChecker $rightChecker)
	{
		parent::__construct();

		$this->rightChecker = $rightChecker;
	}

	/**
	 * @return \Admin\Navigation
	 */
	protected function createComponentMainMenu()
	{
		$menu = new Navigation;
		$menu->setPresenterName($this->getPresenterAndAction());
		$menu->setParameters($this->parameters);

		$menu->setMenuTemplate(__DIR__ . '/openedMenu.latte');
		$menu->setupHomepage('Homepage', ':Admin:Dashboard:Homepage:');

		$main = $menu->add('Homepage', ':Admin:Dashboard:Homepage:');
		$main->setOption('icon', 'home');

		return $menu;
	}

	/**
	 * @return \Admin\Navigation
	 */
	protected function createComponentMenu()
	{
		$menu = new Navigation;
		$menu->setTranslator($this->translator);
		$menu->setPresenterName($this->getPresenterAndAction());
		$menu->setParameters($this->parameters);

		$menu->setMenuTemplate(__DIR__ . '/menu.latte');
		$menu->setupHomepage('Homepage', ':Admin:Dashboard:Homepage:');

		$main = $menu->add('Nástěnka', ':Admin:Dashboard:Homepage:');
		$main->setOption('icon', 'home');

		$sectionModule = ':Admin:Content';
		$articleModule = ':Admin:Article';

		$general = $menu->add('Obecné', NULL);
		$general->setOption('icon', 'pencil');
		$this->add($general, 'Menu sekce', "$sectionModule:Menu:");
		$this->add($general, 'Statické texty', "$articleModule:Page:");

		$settingsModule = ':Admin:Settings';
		$settings = $menu->add('Nastavení', NULL);
		$settings->setOption('icon', 'cogs');

		$userModule = ":Admin:User";
		$this->add($settings, 'Uživatelé', "$userModule:User:");
		$this->add($settings, 'Role', "$userModule:Role:");

		$matches = $menu->add('Správa systému', NULL);
		$matches->setOption('icon', 'file-text-o');
		$this->add($matches, 'Sekce', "$sectionModule:Section:");
		$this->add($matches, 'Typ položky menu', "$sectionModule:MenuType:");
		$this->add($matches, 'Moduly', "$sectionModule:Module:");
		$this->add($matches, 'Presentery', "$sectionModule:Presenter:");
		$this->add($matches, 'Verzování DB', "$settingsModule:DBUpdater:");

		return $menu;
	}

	/**
	 * VLoží položku menu, pouze pokud má práva na danou sekci
	 * @param \Esports\Navigation\NavigationNode $node
	 * @param $label
	 * @param $url
	 */
	private function add(\Esports\Navigation\NavigationNode $node, $label, $url)
	{
		$action = 'default';

		if ($this->isUrlWithAction($url)) {
			$array = array_filter(explode(':', $url), 'strlen');
			$action = end($array);
			$checkAbleUrl = join(':', array_slice($array, 0, count($array) - 1));
		} else {
			$checkAbleUrl = join(':', array_filter(explode(':', $url), 'strlen'));
		}

		if ($this->rightChecker->isAllowed($checkAbleUrl, $action)) {
			$node->add($label, $url);
		}
	}

	/**
	 * @param $url
	 * @return bool
	 */
	private function isUrlWithAction($url)
	{
		return Strings::endsWith($url, ':') ? FALSE : TRUE;
	}

	/**
	 * @param \Nette\Application\UI\ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		parent::prepareTemplate($template);
		$template->setFile(__DIR__ . '/mainMenu.latte');
	}

	protected function getPresenterAndAction()
	{
		$presenter = $this->presenter;

		if ($this->action) {
			$presenter .= ':' . $this->action;
		}

		return $presenter;
	}

	public function setPresenter($presenter)
	{
		$this->presenter = $presenter;
	}

	public function setAction($action)
	{
		$this->action = $action;
	}

	public function setParameters($params)
	{
		$this->parameters = $params;
	}

}

interface IMainMenuFactory
{
	/**
	 * @return MainMenu
	 */
	function create();
}
