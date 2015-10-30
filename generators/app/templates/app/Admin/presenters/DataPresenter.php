<?php

namespace Admin;

use Admin\Remover\PermissionException;
use Esports\ConstraintViolationException;

abstract class DataPresenter extends BasePresenter
{

	protected $gridFactory;

	/** @var Callable[] */
	public $onGridCreated = [];

	/**
	 * Když se v gridu manipuluje z daty - zaregistrovat pro Kdyby\Events̈́
	 * @var Callable[]
	 */
	public $onDataChange = [];


	protected function createGrid()
	{
		return $this->gridFactory->create();
	}

	protected function createComponentGrid()
	{
		$grid = $this->createGrid();
		$this->onGridCreated($grid);

		$grid->onDelete[] = $this->doGridDelete;
		$grid->onDelete[] = $this->redirectThis;

		return $grid;
	}

	/**
	 * Vykona smazani jednoho zaznamu z gridu
	 * @param int|string $id
	 */
	public function doGridDelete($id)
	{
		try {
			$this['grid']->delete($id);
			$this->flashSuccess('Záznam byl smazán!');
		} catch (\PDOException $e) {
			$this->flashError('Záznam nelze smazat!');
		} catch (ConstraintViolationException $e) {
			$this->flashError('Záznam nelze smazat, jsou na něj navázány další záznamy!');
		} catch(PermissionException $e) {
			$this->flashError('Nepovolený přístup!');
		}
	}

}
