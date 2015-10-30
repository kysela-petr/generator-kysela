/**
 * Sdružuje vlastnosti a metody nutné pro fungování javascriptové mechaniky stránky s editací Postu pro TinyMCE.
 * @type {Object}
 */

var embedManager = {
	style: " .custom-embed-container {position: relative; overflow: hidden;} .custom-embed-container iframe, .custom-embed-container object, .custom-embed-container embed {position: absolute; top: 0; left: 0; width: 100%; height: 100%;} ",
	style_instagram: " .embed-container-instagram {padding-bottom: 120%; padding-top: 30px; height: 0;} ",
	style_youtube: " .embed-container-youtube {padding-bottom: 56.25%; padding-top: 30px; height: 0; max-width: 100%; height: auto;} ",
	style_vine: " .embed-container-vine {padding-bottom: 90%; padding-top: 30px; height: 0;} ",
	style_gfycat: " .embed-container-gfycat {width: 100%;} ",
	obalStart: '<div class="highlight"><div class="highlight__content">',
	obalEnd: '</div></div>',
	/**
	 * Vrati embedovany kod pro Instagram
	 * @param {String} data
	 * @returns {String}
	 */
	createInstagramEmbed: function(data) {
		var output = "";
		var url = data.replace("?modal=true", "");
		url = (url.slice(-1) === "/") ? url + "embed/" : url + "/embed/";

		output += this.obalStart;
		output += "<div class='custom-embed-container embed-container-instagram'>";
		output += "<iframe src='" + url + "' frameborder='0' scrolling='no' allowtransparency='true'></iframe>";
		output += "</div>";
		output += this.obalEnd;
		output += "<style>" + this.style + this.style_instagram + "</style>";

		return output;
	},
	/**
	 * Vrati embedovany kod pro Youtube
	 * @param {String} data
	 * @returns {String}
	 */
	createYoutubeEmbed: function(data) {
		var output = "";
		var id = data.match(/v=([A-Za-z0-9_\-]*)/);

		if (id !== null) {
			var vid = id[1];

			output += this.obalStart;
			output += "<div class='custom-embed-container embed-container-youtube'>";
			output += "<iframe src='http://www.youtube.com/embed/" + vid + "' frameborder='0' allowfullscreen></iframe>";
			output += "</div>";
			output += this.obalEnd;
			output += "<style>" + this.style + this.style_youtube + "</style>";
		} else {
			alert("Špatné Youtube URL !");
		}

		return output;
	},
	/**
	 * Vrati embedovany kod pro Gfycat
	 * @param {String} data
	 * @returns {String}
	 */
	createGfycatEmbed: function(data) {
		var id = data.match(/\/([^/]*)$/);
		
		if (id === null) {
			alert("Špatný Gfycat link !");
			
			return "";
		}
		
		return "<img src=\"/images/icons/icon-play-130.png\" class=\"gfyitem\" data-id=\"" + id[1].trim() + "\" />";
	},
	/**
	 * Vrati embedovany kod pro Twitter
	 * @param {String} data
	 * @returns {String}
	 */
	createTwitterEmbed: function(data) {
		var output = "";

		output += this.obalStart;
		output += data;
		output += this.obalEnd;

		return output;
	},
	/**
	 * Vrati embedovany kod pro Vine
	 * @param {String} data
	 * @returns {String}
	 */
	createVineEmbed: function(data) {
		var output = "";
		var url = (data.slice(-1) === "/") ? data + "embed/simple" : data + "/embed/simple";

		output += this.obalStart;
		output += "<div class='custom-embed-container embed-container-vine'>";
		output += "<iframe width='100%' src='" + url + "' frameborder='0' scrolling='no' allowtransparency='true'></iframe>";
		output += "<script async src='//platform.vine.co/static/scripts/embed.js' charset='utf-8'></script>";
		output += "</div>";
		output += this.obalEnd;
		output += "<style>" + this.style + this.style_vine + "</style>";

		return output;
	}
}