/* global jQuery:false */
/* global FCUNITED_STORAGE:false */

jQuery( document ).ready(
	function() {
		"use strict";

		// Switch active skin
		jQuery( '#trx_addons_theme_panel_section_skins a.trx_addons_image_block_link_choose_skin' ).on(
			'click', function(e) {
				var link = jQuery( this );
				trx_addons_msgbox_confirm(
					FCUNITED_STORAGE['msg_switch_skin'],
					FCUNITED_STORAGE['msg_switch_skin_caption'],
					function(btn) {
						if ( btn != 1 ) return;
						jQuery.post(
							FCUNITED_STORAGE['ajax_url'], {
								'action': 'fcunited_switch_skin',
								'skin': link.data( 'skin' ),
								'nonce': FCUNITED_STORAGE['ajax_nonce']
							},
							function(response){
								var rez = {};
								if (response == '' || response == 0) {
									rez = { error: FCUNITED_STORAGE['msg_ajax_error'] };
								} else {
									try {
										rez = JSON.parse( response );
									} catch (e) {
										rez = { error: FCUNITED_STORAGE['msg_ajax_error'] };
										console.log( response );
									}
								}
								// Show result
								if ( rez.error ) {
									trx_addons_msgbox_warning( rez.error );
								} else {
									trx_addons_msgbox_success( FCUNITED_STORAGE['msg_switch_skin_success'], FCUNITED_STORAGE['msg_switch_skin_success_caption'] );
								}
								// Reload current page after the skin is switched (if success)
								if (rez.error == '') {
									if ( location.hash != 'trx_addons_theme_panel_section_skins' ) {
										fcunited_document_set_location( location.href.split('#')[0] + '#' + 'trx_addons_theme_panel_section_skins' );
									}
									location.reload( true );
								}
							}
						);
					}
				);
				e.preventDefault();
				return false;
			}
		);
	}
);
