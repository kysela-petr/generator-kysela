<?php

namespace Admin\Settings;

/**
 * Presenter s DB updaterem
 * @author Svaťa
 */
class DBUpdaterPresenter extends \Admin\BasePresenter {

	/** @var \Esports\DBUpdater\IScriptTableFactory @inject */
	public $scriptTableFactory;

	public function createComponentScriptTable() {
		return $this->scriptTableFactory->create();
	}

}
