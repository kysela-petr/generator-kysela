/**
 * Created by mk on 10.8.15.
 */

/*jslint browser: true*/
/*global $, jQuery*/
var randomString = function (length) {
	'use strict';
	var text = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for (var i = 0; i < length; i++) {
		text += possible.charAt(Math.floor(Math.random() * possible.length));
	}

	return text;
};

var createReplaceFunction = function (templateObj, appendObj, callback) {
	'use strict';
	return function () {
		var text = templateObj.html();
		text = text.replace(/replacelabel/gi, 'label');
		text = text.replace(/replaceinput/gi, 'input');
		text = text.replace(/replaceid/gi, 'new' + randomString(8));
		var html = $(text);
		if (callback !== undefined) {
			html = callback(html);
		}
		appendObj.append(html);
		return false;
	};
};
