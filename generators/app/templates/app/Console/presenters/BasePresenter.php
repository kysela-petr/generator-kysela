<?php

namespace Console;

/**
 * Vychozi konzolovy presenter
 * @author Svaťa
 */
class BasePresenter extends \Nette\Application\UI\Presenter {

	/**
	 * Zkontroluje, zda jsme v konzoli
	 * @throws \Nette\Security\AuthenticationException
	 */
	protected function checkConsole() {
		$isConsole = $this->context->parameters['consoleMode'];
		if (!$isConsole) {
			throw new \Nette\Security\AuthenticationException('Konzolove akce jdou spoustet pouze z konzole');
		}
	}

	protected function startup() {
		parent::startup();
		$this->checkConsole();
	}

	public function runAndScream($function) {
		try {
			$function();
			exit(0);
		} catch (\Exception $e) {
			echo $e->getMessage() . "\n";
			\Tracy\Debugger::log($e);
		}
		exit(1);
	}

}
