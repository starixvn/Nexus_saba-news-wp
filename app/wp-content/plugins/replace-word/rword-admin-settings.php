<?php
/**
 * 
 * @access      public
 * @since       1.2
 * @return      $admin-setting
*/
/* Setting Page option */
add_action('admin_menu', 'rw_add_setting_pages');
function rw_add_setting_pages() { // Add a  submenu under Tools
	$page = add_options_page('RW Options', 'Replace Word', 'manage_options', 'replace-word', 'replace_word_page');
	add_action( "admin_print_scripts-$page", 'rw_admin_set_scripts' );
}
/* All admin Settings options */
function replace_word_page()
{
	if (isset($_POST['setup-update'])) 
	{
		$_POST = stripslashes_deep($_POST);
		
			if (is_array($_POST['rwsearch'])){ // If atleast one find has been submitted
				foreach ($_POST['rwsearch'] as $key => $search){
					if (empty($search)){ 
						// if empty ones have been submitted we get rid of the extra data submitted if any.
						unset($_POST['rwsearch'][$key]);
						unset($_POST['rwlink'][$key]);
						unset($_POST['rwchange'][$key]);
						unset($_POST['rwtagdata'][$key]);
						}
					if(isset($_POST['rwsearch'][$key]))
					{
						$search = sanitize_text_field($_POST['rwsearch'][$key]);
						$_POST['rwchange'][$key]= sanitize_text_field($_POST['rwchange'][$key]);
						$_POST['rwlink'][$key]= sanitize_text_field($_POST['rwlink'][$key]);
						$_POST['rwtagdata'][$key]= sanitize_text_field($_POST['rwtagdata'][$key]);
					}
					if (!isset($_POST['rwlink'][$key])) // convert line feeds on non-regex only
						$_POST['rwsearch'][$key] = str_replace("\r\n", "\n", $search);
				}
			}
			unset($_POST['setup-update']);
			
			if(empty($_POST['rwsearch']))
				// delete the option if there are no settings.  
				delete_option('rw_plugin_settings'); 
			else
				update_option('rw_plugin_settings', $_POST);
			?>  
			<div id="message" class="updated fade">
				<p><strong>Options Updated</strong></p>
			</div>
	<?php } 
	require_once (dirname(__FILE__) . '/template/rword-admin-html.php'); 
}