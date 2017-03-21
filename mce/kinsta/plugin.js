(function() {

	tinymce.PluginManager.add( 'kinsta', function( editor ){

		editor.addButton( 'kinsta', {
			title: 'Kinsta buttons',
			text: 'Kinsta',
			icon: 'code',
			onclick: function(){
				editor.windowManager.open({
					title: 'Kinsta buttons',
					body: [{
						type: 'textbox',
						name: 'btn_url',
						label: 'URL'
					},
					{
						type: 'textbox',
						name: 'btn_label',
						label: 'Button label'
					},
					{
						type: 'textbox',
						name: 'btn_id',
						label: 'Button ID'
					},
					{
						type: 'listbox',
						name: 'btn_color',
						label: 'Button color',
						values: [
							{text: 'Green', value: 'green'}, 
							{text: 'Blue', value: 'blue'},
							{text: 'Red', value: 'red'},
							{text: 'Grey', value: 'grey'},
							{text: 'Black', value: 'black'}
						]
					},
					{
						type: 'checkbox',
						name: 'btn_corners',
						label: 'Button corners'
					}],
					onsubmit: function(e){
						var tag = 'kinsta_btn';
						var href = ' href= "' + e.data.btn_url + '"';
						var label = ' label="' + e.data.btn_label + '"';
						var id = ' id="' + e.data.btn_id + '"';
						var corners = e.data.btn_corners == true ? ' rounded' : '';
						var color = e.data.btn_color;
						var css_class = ' class="' + color + corners + '"';
						editor.insertContent('[' + tag + href + id + css_class + label + ']')
					}
				})
			}
		});
	});
})();
