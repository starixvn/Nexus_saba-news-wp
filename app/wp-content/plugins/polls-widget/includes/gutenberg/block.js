(function( blocks,  element ) {
	var el = element.createElement;
	var icon_iamge = el( 'img', {
      width: 24,
      height: 24,
      src: window['wpda_polls_gutenberg']["other_data"]["icon_src"],
	  className: "wpdevart_polls_icon"
    } );
	blocks.registerBlockType( 'wpdevart-polls/polls', {
		title: 'WpDevArt Polls',
		icon: icon_iamge ,
		category: 'common',
		attributes: {			
			polls: {
				type: 'string',
				selector: 'select',
			},
			theme: {
				type: 'string',
				selector: 'select',
			}
		},
		edit: function( props ) {
			var attributes = props.attributes;
			var polls_options=new Array(),theme_options=new Array();
			var selected_option=false;
			console.log()
			for(var key in wpda_polls_gutenberg["polls"]) {
				selected_option=false;
				if(typeof(attributes.polls)=="undefined"){					
					props.setAttributes( { polls: key })
					attributes.polls=key;
				}else{
					if(props.attributes.polls==key){
						selected_option=true;
					}
				}
				polls_options.push(el('option',{value:''+key+'',selected:selected_option},wpda_polls_gutenberg["polls"][key]))
			}
			for(var key in wpda_polls_gutenberg["themes"]) {
				selected_option=false;
				console.log(typeof(props.attributes.theme)=="undefined")
				if(typeof(attributes.theme)=="undefined"){
					props.setAttributes( { theme: key})
					attributes.theme=key;
				}else{
					if(props.attributes.theme==key){
						selected_option=true;
					}
				}
				theme_options.push(el('option',{value:''+key+'',selected:selected_option},wpda_polls_gutenberg["themes"][key]))
			}
			
			return (
				el( 'div', { className: props.className },				   
				  el( 'div', { className: "wpdevart_gutenberg_polls_main_div"},
					el( 'span', { },"Wpdevart Polls"),
					el( 'br'),
				    el( 'label', {style:{"margin-right":"7px"} },"Select a Poll"),
					el( 'select', { className: "wpdevart_gutenberg_polls_css",onChange: function( value ) {var select=value.target; props.setAttributes( { polls: select.options[select.selectedIndex].value })}},polls_options),
					el( 'br'),
					el( 'label', { style:{"margin-right":"7px"}},"Select a Theme"),
					el( 'select', { className: "wpdevart_gutenberg_theme_css",onChange: function( value ) {var select=value.target; props.setAttributes( { theme: select.options[select.selectedIndex].value })}},theme_options),
				  )
				)
			);
			
		},
		save: function( props ) {			
			return "[wpdevart_poll id=\""+props.attributes.polls+"\"  theme=\""+props.attributes.theme+"\"]";
		}

	} )
} )(
	window.wp.blocks,
	window.wp.element
);

