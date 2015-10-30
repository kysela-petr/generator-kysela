<?php

namespace Admin;

/**
 * Rozhrani pro objekty vyuzite pri sprave LazyContaineru
 */
interface ILazyItem {

	/**
	 * Vytvori formularove prvky v containeru
	 * @param \Admin\LazyContainer $container
	 * @param int $sectionId
	 */
	public function setup(LazyContainer $container, $sectionId);

	/**
	 * Nacte obsah containeru
	 * @param int $id
	 * @param \Admin\LazyContainer $container
	 */
	public function load($id, LazyContainer $container);

	/**
	 * Ulozi obsah containeru
	 * @param int $id
	 * @param \Admin\LazyContainer $container
	 */
	public function save($id, LazyContainer $container);

	/**
	 * Validuje data v kontejneru
	 * @param \Admin\LazyContainer $container
	 */
	public function validate(LazyContainer $container);

}
