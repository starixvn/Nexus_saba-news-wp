/**
 * 
 * @access      public
 * @since       1.2
 * @return      $admin-action-JS
*/
function addNewField() {
	var id = jQuery('#id').val();
	jQuery("#rw_itemlist").append('<tr id="row' + id + '" class="iedit author-self level-0 post-' + id + ' type-post">'
	+ '<th scope="row" class="check-column">'
	+ '<label class="screen-reader-text" for="cb-select-1">Select Hello world!</label>'
	+ '<input id="cb-select-1" type="checkbox" name="post[]" value="1">'
	+ '<div class="locked-indicator">'
	+ '<span class="locked-indicator-icon" aria-hidden="true"></span>'
	+ '<span class="screen-reader-text"></span>'
	+ '</div>'
	+ '</th>'
	+ '<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">'
	+ '<textarea class="left" name="rwsearch[' + id + ']" id="rwsearch' + id + '"></textarea>'
	+ '</td>'
	+ '<td class="author column-author" data-colname="Author">'
	+ '<textarea class="left" name="rwchange[' + id + ']" id="rwchange' + id + '"></textarea>'
	+ '</td>'
	+ '<td class="categories column-link" data-colname="Link">'
	+ '<input type="text" class="left" name="rwlink[' + id + ']" id="rwlink' + id + '" value=""/>'
	+ '</td>'
	+ '<td class="tags column-tags" data-colname="Tags">'
	+ '<select class="left" name="rwtagdata[' + id + ']" id="rwtagdata' + id + '">'
	+ '<option value=""/>Select</option>'
	+ '<option value="h1">h1</option>'
	+ '<option value="h2">h2</option>'
	+ '<option value="h3">h3</option>'
	+ '<option value="h4">h4</option>'
	+ '<option value="h5">h5</option>'
	+ '<option value="h6">h6</option>'
	+ '<option value="span">span</option>'
	+ '</select>'
	+ '</td>'
	+ '</tr>');
	id = (id - 1) + 2;
	document.getElementById("id").value = id;
	var rawid = jQuery("#row"+(id-1));
	if (rawid.length) {
	   	jQuery('html, body').animate({
	        scrollTop: jQuery("#row"+(id-1)).offset().top
	    }, 1000);
	}
}
function removeFormField(id) {
	jQuery(id).remove();
}
jQuery(function() {
	jQuery( "#rw_itemlist" ).sortable();
});