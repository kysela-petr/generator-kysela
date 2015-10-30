<?php

namespace App\Helper;

use Nette\Object;

/**
 * Helper pro praci s rolemi uzivatelu adminu
 * @author Petr Hlavac
 */
class Role extends Object {

	/**
	 * Prevadi Selection na pole kde klic je zdroj a hodnota pole akci
	 * Funkce ocekava, ze v Selection budou atributy:
	 * - resource
	 * - action
	 *
	 * @param \Nette\Database\Table\Selection $context
	 * @return string
	 * @throws \Nette\InvalidArgumentException
	 */
	public function toArray(\Nette\Database\Table\Selection $context) {
		$roles = [];

		$resourceKey = 'resource';
		$actionKey = 'action';

		foreach ($context as $row) {
			if (!(isset($row[$resourceKey]) && isset($row[$actionKey]))) {
				throw new \Nette\InvalidArgumentException;
			}

			if (!isset($roles[$row[$resourceKey]])) {
				$roles[$row[$resourceKey]] = [];
			}

			$roles[$row[$resourceKey]][] = $row[$actionKey];
		}

		return $roles;
	}

}
