<?php

namespace App\Component;

/**
 * Fake
 * v config.neon neni potreba neustale setupovat Translator, HelperLoader atp. u Gridu, Formu
 * @author Ondra Machala
 */
interface IStandardComponentFactory {

	/**
	 * @return Component
	 */
	public function create();
}
