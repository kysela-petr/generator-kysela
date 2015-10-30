<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;


class UploadFormPresenter extends DataFormPresenter
{
	/** @return CssLoader */
	protected function createComponentCssAdminUpload() {
		return $this->webLoader->createCssLoader('upload');
	}
}
