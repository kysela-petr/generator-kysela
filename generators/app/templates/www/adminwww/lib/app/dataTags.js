/**
 * Created by mk on 25.5.15.
 */


jQuery.fn.extend({
	// input element pro tagy - onLoad funkce
	dataTags: function () {
		var self = $(this);
		var data = self.attr('data-tags');
		var object = {tags: data.split(','), tokenSeparators: [","]};
		if (self.hasClass('onlyonevalue'))
			object['maximumSelectionSize'] = 1;
		self.select2(object);
	}
});