/**
 * Created by mk on 26.5.15.
 */
$(function () {

	$('#photo-picker-wrapper')
		.on('shown.bs.modal', function (e) {
			$(e.relatedTarget).addClass('activeButton');
			$(this).attr('data-photo-hidden', $(e.relatedTarget).data('photo-hidden'));//kam se vlozi id fotky - nadřazený formulář
			$(this).attr('data-thumbnail', $(e.relatedTarget).data('thumbnail'));//náhladový obrázek wrapper
		}).on('hide.bs.modal', function () {
			$('button').removeClass('activeButton');
		});

});