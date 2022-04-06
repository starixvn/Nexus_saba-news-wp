<?php
class poll_uninstall{
	
	private $menu_name;	
	private $databese_names;
	
    /*############  Construct parameters function  ################*/	
	
	function __construct($params){
		// Set the plugin URL
		if(isset($params['plugin_url']))
			$this->plugin_url=$params['plugin_url'];
		else
			$this->plugin_url=trailingslashit(dirname(plugins_url('',__FILE__)));
		// Set the plugin path
		if(isset($params['plugin_path']))
			$this->plugin_path=$params['plugin_path'];
		else
			$this->plugin_path=trailingslashit(dirname(plugin_dir_path('',__FILE__)));
		
	}
	
	/*#################### CONTROLERRR ########################*/
	/*#################### CONTROLERRR ########################*/
	/*#################### CONTROLERRR ########################*/
	public function controller_page(){
		if(isset( $_POST['uninstall_polls_bad'] )   && wp_verify_nonce( $_POST['uninstall_polls_bad'], 'uninstall_polls')){
			$this->remove_databese_and_deactivete();
			return;
		}
		$this->display_uninstall_main();
	}
	
	/*#################### Table List ########################*/
	/*#################### Table List ########################*/
	/*#################### Table List ########################*/
	private function display_uninstall_main(){
		global $wpdb;
		?>
        <form method="post" action="admin.php?page=Polls-uninstall" style="width:99%;">
     <?php wp_nonce_field('uninstall_polls','uninstall_polls_bad'); ?>
      <div class="wrap">
        <div class="wpdevart_plugins_header div-for-clear" style="width:100%;margin-right:0px;">
				<div class="wpdevart_plugins_get_pro div-for-clear">
					<div class="wpdevart_plugins_get_pro_info">
						<h3>WpDevArt Polls Premium</h3>
						<p>Powerful and Customizable Polls</p>
					</div>
						<a target="blank" href="https://wpdevart.com/wordpress-polls-plugin/" class="wpdevart_upgrade">Upgrade</a>
				</div>
				<a target="blank" href="<?php echo wpdevart_polls_support_url; ?>" class="wpdevart_support">Have any Questions? Get a quick support!</a>
			</div>
        <h2>Uninstall Polls</h2>
        <p>
          Deactivating Polls plugin doesn't remove any data that have been created by this plugin in your website database. To completely remove this plugin(with the plugin database tables), you can uninstall it here.
        </p>
        <p style="color: #7052fb;">
          <strong>WARNING:</strong>
          Once uninstalled, this can't be undone. You should use a Database Backup plugin of WordPress to back up all the data first.
        </p>
        <p style="color: #7052fb;">
          <strong>The following Database Tables will be deleted:</strong>
        </p>
        <table class="widefat">
          <thead>
            <tr>
              <th>Database Tables</th>
            </tr>
          </thead>
          <tr>
            <td valign="top">
              <ol>
                  <li><?php echo $wpdb->prefix; ?>polls_users</li>
                  <li><?php echo $wpdb->prefix; ?>polls_question</li>
                  <li><?php echo $wpdb->prefix; ?>polls</li>
                  <li><?php echo $wpdb->prefix; ?>polls_templates</li>
              </ol>
            </td>
          </tr>
        </table>
        <p style="text-align: center;">
          Do you really want to delete all data?
        </p>
        <p style="text-align: center;">
          <input type="checkbox" id="check_yes" value="yes" />&nbsp;<label for="check_yes">Yes</label>
        </p>
        <p style="text-align: center;">
          <input type="submit" value="UNINSTALL" class="button-primary" onclick="if (check_yes.checked) { 
                                                                                    if (confirm('You are About to Uninstall poll.\nThis Action Is Not Reversible.')) {
                                                                                       
                                                                                    } else {
                                                                                        return false;
                                                                                    }
                                                                                  }
                                                                                  else {
                                                                                    return false;
                                                                                  }" />
        </p>
      </div>
    </form>
  <?php
    
		
		
	}
	
    /*############  Remove the database function  ################*/		
	
	private function remove_databese_and_deactivete(){
		global $wpdb;
		$wpdb->query("DROP TABLE " . $wpdb->prefix . "polls_users");
		$wpdb->query("DROP TABLE " . $wpdb->prefix . "polls_question");
		$wpdb->query("DROP TABLE " . $wpdb->prefix . "polls");
		$wpdb->query("DROP TABLE " . $wpdb->prefix . "polls_templates");
		
		?>
		<div id="message" class="updated fade">
		  <p>The following Database Tables successfully deleted:</p>
		  <p><?php echo $wpdb->prefix; ?>polls_users,</p>
		  <p><?php echo $wpdb->prefix; ?>polls_question,</p>
		  <p><?php echo $wpdb->prefix; ?>polls,</p>
		  <p><?php echo $wpdb->prefix; ?>polls_templates,</p>

		</div>
		<div class="wrap">
		  <h2>Uninstall Polls</h2>
		  <p><strong><a href="<?php echo wp_nonce_url('plugins.php?action=deactivate&amp;plugin=polls-widget/polls.php', 'deactivate-plugin_polls-widget/polls.php'); ?>">Click Here</a> To Finish the Polls Uninstallation</strong></p>
		  <input id="task" name="task" type="hidden" value="" />
		</div>
	  <?php	
	}
}


 ?>