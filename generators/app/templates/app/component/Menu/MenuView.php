<?php
/**
 * @author Martin Kovařčík.
 */

namespace App\Widget;

use App\Component\Component;
use App\CurrentMenuItemFinder;
use App\Helper\MenuItem;
use App\MenuLinkComparator;
use Esports\Helper\Loader;
use Esports\Utils\PresenterBridge;
use Esports\Utils\Tree;
use Nette\Application\UI\ITemplate;

class MenuView extends Component
{

	/** @var MenuItem */
	protected $menuItem;

	/** @var Tree */
	private $tree;

	/** @var null */
	private $module;

	/** @var */
	private $sectionId;

	/** @var \Esports\Utils\PresenterBridge */
	private $presenterBridge;

	/** @var \App\MenuLinkComparator */
	private $menuLinkComparator;

	/**
	 * @param int $sectionId
	 * @param string $module
	 * @param \Esports\Utils\Tree $tree
	 * @param \App\Helper\MenuItem $menuItem
	 * @param \Esports\Utils\PresenterBridge $presenterBridge
	 * @param \App\MenuLinkComparator $menuLinkComparator
	 */
	function __construct($sectionId, $module, Tree $tree, MenuItem $menuItem, PresenterBridge $presenterBridge, MenuLinkComparator $menuLinkComparator)
	{
		parent::__construct();
		$this->tree = $tree;
		$this->menuItem = $menuItem;
		$this->module = $module;
		$this->sectionId = $sectionId;
		$this->presenterBridge = $presenterBridge;
		$this->menuLinkComparator = $menuLinkComparator;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/mainMenu.latte');
		parent::render();
	}

	public function renderMobile()
	{
		$this->template->setFile(__DIR__ . '/mainMenuMobile.latte');
		parent::render();
	}

	/**
	 * @param \Nette\Application\UI\ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		parent::prepareTemplate($template);
		$this->registerHelper($template);
		$template->menu = $this->tree->getTree();
		$template->currentPosition = $this->getCurrentPositionMap();
	}

	/**
	 * @param \Nette\Application\UI\ITemplate $template
	 */
	protected function registerHelper(ITemplate $template)
	{
		$helperLoader = new Loader;
		$helperLoader->register($this->menuItem);
		$this->registerHelperLoader($template, $helperLoader);
	}

	/**
	 * @return array
	 */
	protected function getCurrentPositionMap()
	{
		$presenter = $this->presenterBridge->getPresenter();
		$finder = new CurrentMenuItemFinder($this->tree, $presenter->getAction(TRUE), $presenter->getParameters(), $this->menuLinkComparator);

		return $finder->buildCurrentPositionMap();
	}

}

interface IMenuViewFactory
{

	/**
	 * @param $sectionId
	 * @param $module
	 * @param Tree $tree
	 * @return \App\Widget\MenuView
	 */
	public function create($sectionId, $module, Tree $tree);
}
