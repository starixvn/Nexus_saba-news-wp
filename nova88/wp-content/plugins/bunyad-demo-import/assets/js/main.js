"use strict";

jQuery(function($) {
	var ajaxRun = 0;

	function ajaxCall(data) {
		
		ajaxRun++;
		
		// Been almost 15 minutes, that's enough
		if (ajaxRun > 30) {
			$('.bunyad-import .ajax-response').append('<div class="error  below-h2"><p>Could not complete the import. Please contact support.</p></div>');
			$('.bunyad-import .ajax-loader').remove();
			
			return;
		}
		
		$.ajax({
			method:     'POST',
			url:        Bunyad_Import.ajax_url,
			data:       data,
			beforeSend: function() {
				$('.bunyad-import .ajax-response').append(
					'<div class="notice ajax-loader"><p><span class="spinner"></span> Importing...</p></div>'
				);
			}
		})
		.done(function(response) {
			if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
				ajaxCall(data);
			}
			else if ( 'undefined' !== typeof response.message ) {
				$('.bunyad-import .ajax-response').append( '<p>' + response.message + '</p>' );
				$('.bunyad-import .ajax-loader').remove();
			}
			else {
				$('.bunyad-import .ajax-response').append('<div class="error  below-h2"><p>' + response + '</p></div>');
				$('.bunyad-import .ajax-loader').remove();
			}

		})
		.fail(function(error) {
			$('.bunyad-import .ajax-response').append('<div class="error  below-h2"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '. You may try the process again a few times. Refresh the page, select your demo and click Import.</p></div>');
			$('.bunyad-import .ajax-loader').remove();

			// Server has possibly hit the execution time, restart
			// ajaxCall(data);
		});
	}

	function handleDependencies(depends, srcBtn) {
		if (!depends || !Object.keys(depends).length) {
			return true;
		}

		const modal = $('#bunyad-missing-plugins');
		const form  = modal.find('form');
		const required = [];
		Object.keys(depends).forEach(key => {
			const plugin = depends[key];
			required.push(`<li>${plugin}</li>`);
			form.append(`<input type="hidden" name="plugin[]" value="${key}" />`);
		});

		modal.find('.plugin-names').html(required.join(''));

		// Handle plugin activation.
		form.find('input[type=submit]').on('click', function(e) {
			$(this).attr('disabled', true);
			$(this).val('Installing...');

			e.preventDefault();
			form.submit();
		});

		// Show modal.
		modal.dialog({
			title: 'Required Plugins',
			dialogClass: 'wp-dialog',
			autoOpen: false,
			draggable: false,
			width: 'auto',
			modal: true,
			resizable: false,
			closeOnEscape: true,
			position: {
				my: "center",
				at: "center",
				of: window
			},
			open: function () {
				// Close dialog by clicking the overlay behind it
				$('.ui-widget-overlay').bind('click', function() {
					modal.dialog('close');
				})
			},
			create: function () {
				$('.ui-dialog-titlebar-close').addClass('ui-button');
			},
		});

		modal.dialog('open');

		// Close the dialog, and restart import once plugins installed.
		setTimeout(() => {
			const iframe = modal.find('iframe');
			iframe.on('load', function() {
				modal.dialog('close');
				srcBtn.data('depends', []);
				srcBtn.click();
			});
		}, 50);

		return false;
	}

	
	// Run the importer
	$('.button.import').click(function() {

		// Ensure dependencies are met.
		// .data() will parse it.
		var data = $(this).data('depends');
		if (!handleDependencies(data, $(this))) {
			return;
		}
		
		ajaxCall({
			action: 'bunyad_import_demo',
			demo_id: $(this).data('id'),
			import_type: $(this).parent().find('[name=import_type]').val(),
			security: Bunyad_Import.ajax_nonce
		});
		
		// Scroll to response area if needed
		var scrollTo = $(".bunyad-import .ajax-response").offset().top - 75
		if ($(window).scrollTop() > scrollTo) {
			$('html, body').animate({
				scrollTop: scrollTo
			}, 100);
		}
		
		// disable all
		$('.button.import').attr('disabled', true).unbind('click');
	});
	
	// Remove
	$('.bunyad-import').on('click', 'a.cleanup', function() {
		var links = ($(this).data('remove')).split(','),
			current;
		
		var recursive = function() {
			current = links.shift();
			
			if (!current) {
				return;
			}
			
			$.get(current, function() {
				recursive();
			});
		};
		
		recursive();
		
	});
	
});
