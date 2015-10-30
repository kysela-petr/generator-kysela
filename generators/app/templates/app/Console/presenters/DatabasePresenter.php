<?php

namespace Console;

/**
 * Automaticke operace s DB
 * @author Svaťa
 */
class DatabasePresenter extends BasePresenter {

	/**
	 * @var \Esports\DBUpdater\Updater
	 * @inject
	 */
	public $updater;

	public function actionRun() {
		$this->runAndScream(function() {
			$this->updater->run();
			echo "Database update OK\n";
		});
	}

	public function actionInitVersioning() {
		$this->runAndScream(function() {
			$this->updater->installIfNew();
			echo "Database versioning inited\n";
		});
	}

}
