<?php
/*
Plugin Name: Reve Chat
Description: REVE Chat is a powerful and intuitive real-time customer engagement software. As a customer support software, REVE Chat puts a live person on your website to personally guide and help your visitors, while they go through the various sections of your digital display. This live chat service helps them to get the most out of your web presence, while allowing you to understand their diverse needs on a one-to-one basis. REVE Chat is easy to install and use.
Version: 1.2.2
Author: ReveChat
Author URI: www.revechat.com
License: GPL2
*/
if(!class_exists('WP_Plugin_Revechat'))
{
    class WP_Plugin_Revechat
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // Plugin Details
            $this->plugin = new stdClass;
            $this->plugin->name = 'revechat'; // Plugin Folder
            $this->plugin->displayName = 'ReveChat'; // Plugin Name
            $this->plugin->version = '1.1.1';
            
            // Hooks
            add_action('admin_init', array(&$this, 'registerSettings'));
            add_action('admin_menu', array(&$this, 'adminPanels'));
            
            add_action('wp_head', array(&$this, 'frontendHeader'));
        } // END public function __construct

        /**
         * Activate the frontendHeader
         */
        public static function frontendHeader()
        {
            $aid = get_option('revechat_accountid' , '');
    		$trackingID = get_option('revechat_trackingid' , '');
			
			if( (isset($aid) && !empty($aid))  && (isset($trackingID) && !empty($trackingID)) ) {
				
			  $script = "<script type='text/javascript'>";
    		  $script .= '  var url = "https://static.revechat.com/client/scripts/configuration.js";
                         var trackingID ="'.$trackingID.'"
                         function initializeWidget(){
                            var aid="'.$aid.'";
                            new ReveChatWidget(aid);
                            var trackingID ="'.$trackingID.'";}
                          (function loadSrc(src_url, onload_callback){
                               var script = document.createElement("script");
                               if(script.readyState){
                                  script.onreadystatechange = function (){
                                     if(script.readyState === "loaded" || script.readyState === "complete"){
                                        script.onreadystatechange = null
                                        onload_callback();}}
                                } else {
                                    script.onload = function(){
                                       onload_callback();
                                   } }
                                script.src = src_url;
                                var first_script = document.getElementsByTagName("script")[0];
                                first_script.parentNode.insertBefore(script, first_script);
                            })(url, initializeWidget);
                        <!-- Add textArea code at every page in your website -->';
    			
    		$script .='</script>';
    		
    		echo $script ; 
			
			}			
    		
        } // END public static function activate
        /*
         * show parameter section
         */
        public function registerSettings(){
            register_setting($this->plugin->name, 'revechat_accountid', 'trim');
            register_setting($this->plugin->name, 'revechat_trackingid', 'trim');
        }
        /*
         * admin panel 
         */
        public function adminPanels(){
            //add_options_page("ReveChat Dashboard" , "ReveChat" , "read" , "reveChatOptions");
            // Add a new submenu under Settings:
            add_options_page(__('ReveChat Dashboard','revechat-settings'), __('ReveChat Settings','menu-revechat'), 'manage_options', 'revechatsettings', array($this , 'reveChatOptions') );
        }
        /*
         * revechat options
         */
        public function reveChatOptions(){
            if ( !current_user_can( 'manage_options' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }
            // variables for the field and option names
            $accountId = 'revechat_accountid';
            $trackingId = 'revechat_trackingid';
            
            // Read in existing option value from database
            $val_accountId = get_option( $accountId );
            $val_trackingId = get_option( $trackingId );
            
            if( isset($_POST[ $accountId ]) && isset($_POST[ $trackingId ] ) ){
                
                // Read in existing option value from POST
                $val_accountId = $_POST[ $accountId ];
                $val_trackingId = $_POST[ $trackingId ];
                update_option( $accountId , $val_accountId );
                update_option( $trackingId , $val_trackingId );
                ?>
                <div class="updated"><p><strong><?php _e('Settings saved.', 'revechat-menu' ); ?></strong></p></div>
                <?php
            }
            ?>
            <div class="wrap">
            <?php echo "<h2>" . __( 'ReveChat Plugin Settings', 'revechat-menu' ) . "</h2>"; ?>
                <form name="form1" method="post" action="">
                    
                    
                    <p><?php _e("Account ID", 'revechat-menu' ); ?> 
                        <input type="text" name="<?php echo $accountId; ?>" value="<?php echo $val_accountId; ?>" size="20">
                    </p><hr />
                    <p><?php _e("Tracking ID", 'revechat-menu' ); ?> 
                        <input type="text" name="<?php echo $trackingId; ?>" value="<?php echo $val_trackingId; ?>" size="20">
                    </p><hr />
                    
                    <p class="submit">
                        <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
                    </p>
                
                </form>
            </div>
            
            <?php 
        }

        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate
    } // END class WP_Plugin_Revechat
} // END if(!class_exists('WP_Plugin_Revechat'))
$revechat = new WP_Plugin_Revechat ; 