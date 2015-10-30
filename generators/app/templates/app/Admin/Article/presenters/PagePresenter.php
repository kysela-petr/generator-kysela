<?php

namespace Admin\Article;

use Admin\SectionFilterPresenter;

/**
 * @author Petr Hlavac
 */
class PagePresenter extends SectionFilterPresenter
{
	/** @var array */
	public $onDataChange = [];

	/**
	 * @param \Admin\Article\IPageGridFactory $gridFactory
	 * @param \Admin\Article\IPageFormFactory $formFactory
	 */
	function __construct(IPageGridFactory $gridFactory, IPageFormFactory $formFactory)
	{
		parent::__construct();
		$this->gridFactory = $gridFactory;
		$this->formFactory = $formFactory;
		$this->setWithHomeSection();
	}

	public function renderDefault()
	{
		$this->template->labelNew = 'Vytvořit novou stránku';
	}

	/**
	 * @return \Admin\Article\PageForm
	 */
	protected function createForm() {
		return $this->formFactory->create($this->getSectionId());
	}

	/**
	 * @return \Admin\Article\PageGrid
	 */
	protected function createGrid() {
		return $this->gridFactory->create($this->getSectionId());
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

}
