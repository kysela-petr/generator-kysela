<?php

namespace App\Helper;

use Nette\Object;
use Nette\Http\Url;

/**
 * Helper urceny pro minimalizaci obrazku na webu
 */
class Minimalize extends Object {

	/** @var string */
	protected $scriptUrl;

	function __construct($scriptUrl) {
		$this->scriptUrl = $scriptUrl;
	}

	/**
	 * Pro zadane URL vytvori URL na minimalizacni skript
	 * @param string $url
	 * @param int $width [optional]
	 * @param int $height [optional]
	 * @return string
	 */
	public function min($url, $width = NULL, $height = NULL, $topcut = FALSE) {
		$min = new Url($this->scriptUrl);
		$min->setQueryParameter('file', $url);

		if ($width) {
			$min->setQueryParameter('w', $width);
		}

		if ($height) {
			$min->setQueryParameter('h', $height);
		}

		if ($width && $height) {
			$min->setQueryParameter('exact', TRUE);

			if ($topcut) {
				$min->setQueryParameter('topcut', TRUE);
			}
		}

		$minUrl = "/" . $min->getRelativeUrl();

		return $minUrl;
	}

}
