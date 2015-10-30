/**
 * Sdružuje vlastnosti a metody nutné pro fungování javascriptové mechaniky TineMCE.
 * @type {Object}
 */
var labelCounter = $('#content label.counter');
var labelText = labelCounter.text();
var maxLabelCounter = 300;
var simpleMceEdit =
		{
			/**
			 * Nastaví instanci TinyMCE Editoru
			 */
			SetupTinyMCE: function() {
				tinyMCE.init({
					paste_auto_cleanup_on_paste: true,
					paste_remove_styles: true,
					paste_remove_styles_if_webkit: true,
					paste_strip_class_attributes: "all",
					paste_remove_spans: true,
					add_form_submit_trigger: true,
					mode: "specific_textareas",
					language: "cs",
					editor_selector: "mceEditorSimple",
					entity_encoding: "raw",
					height: 250,
					theme: "advanced",
					plugins: "spellchecker, paste, table",
					content_css: "/adminwww/css/customTinyMceContent.css",
					theme_advanced_toolbar_align: "left",
					theme_advanced_toolbar_location: "top",
					theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifycenter,justifyright,formatselect,styleselect,tablecontrols",
					theme_advanced_buttons2: "spellchecker,code,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor|,|,forecolor,insertdate,inserttime,|,spellchecker,advhr,removeformat,|,sub,sup,|,charmap",
					theme_advanced_buttons3: "",
					theme_simple_toolbar_location: "top",
					tools: "inserttable",
					style_formats : [
						{title : 'Table styles'},
						{title : 'Krajský orgán', selector : 'table', classes : 'table table-striped table-condensed'}
					],
					setup: function(ed) {
						ed.onKeyUp.add(function(ed) {
							var editor = ed.getContent();
							editor = editor.replace(/(<([^>]+)>)/ig, "");
							var tinylen = editor.length;
							labelCounter.text(labelText + ': ' + tinylen + ' - doporučená délka je ' + maxLabelCounter + ' znaků');

							if (tinylen > maxLabelCounter) {
								labelCounter.css('color', 'red');
							} else
								labelCounter.css('color', '#555');

						});
					}

				});
			}

		};