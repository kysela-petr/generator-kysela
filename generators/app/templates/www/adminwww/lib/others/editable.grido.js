/**
 * Nette ajax Grido extension.
 * @author Ondra Machala
 * @param {jQuery} $
 */
;
(function ($) {
	"use strict";

	$.fn.gridoEditable = function () {

		this.on("keyup keypress", function (e) {
			var code = e.keyCode || e.which;
			if (code == 13) {
				e.preventDefault();
				return false;
			}
		});

		this.on("click", function (e) {
				e.preventDefault();
				return false;
		});

		this.on("change", function (e) {
			
			var object = $(this);
			var link = object.attr('data-handle-link').replace('REPLACE_VALUE', object.val());
			object.addClass('edit-error');

			$.ajax({
				url: link,
				success: function (result) {
					object.removeClass('edit-error');
					object.addClass('edit-success');
				},
				error: function (result) {
					object.removeClass('edit-success');
					object.addClass('edit-error');
				}
			});
		});
	};

})(jQuery);

