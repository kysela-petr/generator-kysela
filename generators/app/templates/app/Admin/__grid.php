<?php

/**
 * TOTO JE UKAZKOVA SABLONA PRO GRID
 * 1. Zkontroluj namespace a dopln autora.
 * 2. Nahrad XXX za 'Nazev' a xxx za 'nazev'.
 * 3. Zaregistruj Grid do configu: app/config/grid.services.neon
 * 4. Odstran tento a vytvor vlastni komentar.
 * 5. Sablonu klidne dopln nebo oprav.
 */

namespace Admin\Statistics;

use Admin\BaseGrid;
use Model\XXXFacade;
use Grido\Grid;

/**
 * @author Generator
 */
class XXXGrid extends BaseGrid {

	/** @var XXXFacade */
	protected $xxxFacade;

	function __construct(XXXFacade $xxxFacade) {
		parent::__construct();
		$this->xxxFacade = $xxxFacade;
	}

	/**
	 * @return Grid
	 */
	protected function createGrid() {

		$selection = $this->xxxFacade->all();

		$grid = $this->createPreparedGrid();
		$grid->setModel($selection);

		$grid->addColumnText('id', '#')
			->setSortable()
			->setFilterNumber();

		$grid->addActionHref('edit', 'Upravit')
				->setIcon('pencil')->getElementPrototype()->class = 'btn-dark-blue';

		return $grid;
	}

}

interface IXXXGridFactory {

	/**
	 * @return XXXGrid
	 */
	public function create();
}
