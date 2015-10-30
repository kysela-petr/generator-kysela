/**
 * Funkcionalita pro photoUploadForm
 * Created by mk on 25.5.15.
 */

$.nette.ext('photoUploadAjax', {
	load: function () {
		$('.photoUploadAjax').off('submit.photoUploadAjax').on('submit.photoUploadAjax', function (e) {
			var modal = $('#photoUploadModal');
			var mainPhotoId = '';
			var thumbnailId = '';

			$(this).netteAjax(e, {
				beforeSend: function () {
					mainPhotoId = '#' + $('button.activeButton').data('photo-hidden');
					thumbnailId = '#' + $('button.activeButton').data('thumbnail');
				}
			}).done(function (payload) {
				if (payload.id) {
					$(mainPhotoId).attr('value', payload.id);
					var img = $(thumbnailId);
					img.attr('src', payload.img.src);
					img.data('big', payload.img.srcBig);
					$(thumbnailId).closest('.form-group').show();
					modal.modal('hide');
				}
			});
		});
	}
});

$(function () {


	$('#photoUploadModal')
		.on('hide.bs.modal', function () {
			$('button').removeClass('activeButton');
		}).on('show.bs.modal', function (e) {
			var modal = $(this);
			$(e.relatedTarget).addClass('activeButton');

			modal.attr('data-photo-hidden', $(e.relatedTarget).data('photo-hidden'));//kam se vlozi id fotky - nadřazený formulář
			modal.attr('data-thumbnail', $(e.relatedTarget).data('thumbnail'));//náhladový obrázek
		}).on('shown.bs.modal', function (e) {
			$('#photoUploadModal input[data-tags]').each(function () {
				$(this).dataTags();
			});

			var imageStringId = $(e.relatedTarget).data('image');//kam se dá base64 image string
			var imageNameId = $(e.relatedTarget).data('name');//kam se dá jmého obrázku
			var photoId = $(e.relatedTarget).data('upload');//upload button ID

			var fillFormFields = function (imageString, imageName) {
				document.getElementById(imageStringId).value = imageString;
				document.getElementById(imageNameId).value = imageName;
			};

			var control = document.getElementById(photoId);

			control.addEventListener("change", function (event) {
				var file = control.files[0];
				var imageType = /image.*/;

				if (file.type.match(imageType)) {
					var reader = new FileReader();

					reader.onload = function (event) {
						fillFormFields(event.target.result, control.files[0].name);
					};

					reader.onerror = function (event) {
						console.error("File could not be read! Code " + event.target.error.code);
					};

					reader.readAsDataURL(control.files[0]);
				} else {
					fillFormFields('', '');
				}
			}, false);
		});
});
