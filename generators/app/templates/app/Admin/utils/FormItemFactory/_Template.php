<?php

/**
 * 1. Zkontroluj namespace a dopln autora.
 * 2. Nahrad XXX za 'Nazev' a xxx za 'nazev'.
 * 3. Vypln Label checkboxu
 * 4. Pridej form item factory do: app/config/form.services.neon
 * 5. Odstran tento a vytvor vlastni komentar.
 */

namespace Admin;

use Model\XXXFacade;
use Nette\Forms\Controls\SelectBox;
use Nette\Object;

/**
 * @author Generator
 */
class XXXFormItemFactory extends Object {

	/** @var XXXFacade */
	protected $xxxFacade;

	function __construct(XXXFacade $xxxFacade) {
		$this->xxxFacade = $xxxFacade;
	}

	/**
	 * @return SelectBox
	 */
	public function create() {
		$items = $this->xxxFacade->all()->fetchPairs('id', 'name');
		return new SelectBox('Label', $items);
	}

}
