function PhotoPicker(searchFieldId, alignFieldId, pickerJsObjectName) {
	this.timeoutTimer = null;

	this.refreshLink = refreshLinkPhoto; // z PhotoPicker.latte

	this.searchFieldId = searchFieldId;
	this.alignFieldId = alignFieldId;

	this.pickerJsObjectName = pickerJsObjectName;

	this.searchTextTmp = '';

	this.Init = function() {
		$('#' + this.searchFieldId).keyup($.proxy(function(event) {
			return this.OnKeyup(event);
		}, this));

		$('#photo-picker-wrapper').on("click", '#' + this.pickerJsObjectName + '-listWrapper ul li a img', $.proxy(function(event) {
			var photo_id = parseInt($(event.target).attr('rel'));
			var align_id = this.GetAlignText();
			var self = $(event.target);
			this.SelectPhotoAndClose(photo_id, align_id, self);
			return false;
		}, this));
	};

	this.SelectPhotoAndClose = function(photo_id, align_id, self) {
		var modal = $('#photo-picker-wrapper');

		if (modal.hasClass('fromTiny')){
			postEdit.InsertPhotoTag(photo_id, align_id);
			modal.removeClass('fromTiny');
		} else {
			var mainPhotoId = '#' + $('button.activeButton').data('photo-hidden');
			var thumbnailId = '#' + $('button.activeButton').data('thumbnail');

			$(mainPhotoId).val(photo_id);
			var img = $(thumbnailId);
			img.attr('src', self.attr('src'));"q::"
			img.attr('title', self.attr('title'));
			img.data('big', self.data('big'));
			img.data('text', self.data('text'));
			img.data('size', self.data('size'));
			//$('#deleteMainPhoto').show();
			$(thumbnailId).closest('.form-group').show();
		}
		modal.modal('hide');
	};

	this.OnKeyup = function() {
		if (this.searchTextTmp == this.GetSearchText())
			return;
		this.OnTextChange();
	};

	this.OnTextChange = function() {
		clearTimeout(this.timeoutTimer);
		this.timeoutTimer = setTimeout($.proxy(function() {
			this.UpdatePhotoList()
		}, this), 150);
	};

	this.UpdatePhotoList = function() {
		var val = this.GetSearchText();

		if (val.length > 2) {
			var link = this.refreshLink.replace('-REPLACE-', val);
			$('#' + this.pickerJsObjectName + '-listWrapper').empty();
			$('#' + this.pickerJsObjectName + '-listWrapper').append('<p>Loading...</p>');
			$.nette.ajax(link);
		} else {
			$('#' + this.pickerJsObjectName + '-listWrapper').empty();
		}
	};

	this.GetSearchText = function() {
		return $('#' + this.searchFieldId).val();
	};

	this.GetAlignText = function() {
		return $('#' + this.alignFieldId).val();
	};

	this.FocusSearchField = function() {
		$('#' + this.searchFieldId).focus();
	}

}
	//
	//var photoPickerObject = new PhotoPicker(searchFieldIdPhoto, alignFieldIdPhoto, 'photoPickerObject'); // z PhotoPicker.latte
	//
	//$(function() {
	//	photoPickerObject.Init();
	//});
