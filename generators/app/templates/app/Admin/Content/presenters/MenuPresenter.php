<?php

namespace Admin\Content;

use Admin\SectionFilterPresenter;

class MenuPresenter extends SectionFilterPresenter
{
	/** @var array */
	public $onDataChange = [];

	/** @var \Admin\IMenuMoverFactory @inject */
	public $menuMoverFactory;

	/**
	 * @param \Admin\Content\IMenuGridFactory $gridFactory
	 * @param \Admin\Content\IMenuFormFactory $formFactory
	 */
	function __construct(IMenuGridFactory $gridFactory, IMenuFormFactory $formFactory)
	{
		parent::__construct();
		$this->gridFactory = $gridFactory;
		$this->formFactory = $formFactory;
	}

	public function actionAdd($id = NULL)
	{
		if ($id) {
			$this['formAdd']->setDefaultMenuParent($id);
		}
	}

	public function renderDefault()
	{
		$this->template->labelNew = 'Vytvořit novou stránku';
	}

	/**
	 * @param int|string $id
	 */
	public function doGridDelete($id)
	{
		$this->onDataChange($id);
		parent::doGridDelete($id);
	}


	protected function prepareForm($form)
	{
		$form['form']->onSuccess[] = function () use ($form) {
			$this->onDataChange($form->getId());
		};
		parent::prepareForm($form);
	}

	/**
	 * @return \Admin\Content\MenuGrid
	 */
	protected function createGrid()
	{
		$grid = $this->gridFactory->create($this->getSectionId());

		$grid->onClickLeft[] = function ($id, $sectionId) {
			$menuMover = $this->menuMoverFactory->create($sectionId);
			$menuMover->left($id);
			$this->processDirectionClick($id);
		};

		$grid->onClickRight[] = function ($id, $sectionId) {
			$menuMover = $this->menuMoverFactory->create($sectionId);
			$menuMover->right($id);
			$this->processDirectionClick($id);
		};

		$grid->onClickUp[] = function ($id, $sectionId) {
			$menuMover = $this->menuMoverFactory->create($sectionId);
			$menuMover->up($id);
			$this->processDirectionClick($id);
		};

		$grid->onClickDown[] = function ($id, $sectionId) {
			$menuMover = $this->menuMoverFactory->create($sectionId);
			$menuMover->down($id);
			$this->processDirectionClick($id);
		};

		return $grid;
	}

	/**
	 * @param int $id
	 */
	protected function processDirectionClick($id)
	{
		$this->onDataChange($id);
		$this->redirectThis();
	}

}
