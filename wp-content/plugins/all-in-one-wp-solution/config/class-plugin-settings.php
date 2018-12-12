<?php

/**
 * Runs on Admin area of the plugin.
 *
 * @package    All In One WP Solution
 * @subpackage Config
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

if (!class_exists('aiows_plugin_all_config')) {

    class aiows_plugin_all_config {

    public $options_global;
   
    public function __construct() {

        $this->options_global = get_option('aiows_plugin_global_options');
        $this->aiows_register_settings();
    }

    public function aiows_add_menu_option() {
        add_menu_page('All In One WP Solution', 'WP Solution', 'manage_options', 'all-in-one-wp-solution', array('aiows_plugin_all_config','aiows_settings_page'), 'dashicons-admin-tools', 100);
        add_submenu_page('all-in-one-wp-solution', 'All In One WP Solution', 'Dashboard', 'manage_options', 'all-in-one-wp-solution', array('aiows_plugin_all_config','aiows_settings_page'));    
        add_submenu_page('all-in-one-wp-solution', 'Tools - All In One WP Solution', 'Tools', 'manage_options', 'aiows-import-export', array('aiows_plugin_all_config','aiows_import_export_page'));
    }

    static function aiows_import_export_page() {
        ?>
    <div class="wrap">
    <h1>Import & Export</h1>
    <div class="metabox-holder">
			<div class="postbox">
				<h3><span><?php _e( 'Export Settings' ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.' ); ?></p>
					<form method="post">
						<p><input type="hidden" name="aiows_export_action" value="aiows_export_settings" /></p>
						<p>
							<?php wp_nonce_field( 'aiows_export_nonce', 'aiows_export_nonce' ); ?>
							<?php submit_button( __( 'Export Settings' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->

			<div class="postbox">
				<h3><span><?php _e( 'Import Settings' ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></p>
					<form method="post" enctype="multipart/form-data">
						<p>
							<input type="file" name="import_file"/>
						</p>
						<p>
							<input type="hidden" name="aiows_import_action" value="aiows_import_settings" />
							<?php wp_nonce_field( 'aiows_import_nonce', 'aiows_import_nonce' ); ?>
							<?php submit_button( __( 'Import Settings' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->

			<div class="postbox">
				<h3><span><?php _e( 'Reset Settings' ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Reset the plugin settings to default settings for this site.' ); ?></p>
					<form method="post">
						<p><input type="hidden" name="aiows_reset_action" value="aiows_reset_settings" /></p>
						<p>
							<?php wp_nonce_field( 'aiows_reset_nonce', 'aiows_reset_nonce' ); ?>
							<?php submit_button( __( 'Reset Settings' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->
        </div>    
	</div><!--end .wrap-->
	<?php
    add_filter('admin_footer_text', 'aiows_remove_footer_admin');
    }

    static function aiows_settings_page() {
        ?>
            <div class="wrap">
            <h1>All In One WP Solution Dashboard</h1>
			<div class="about-text">
			Ultimate Solution for All WordPress Websites for highly customization of WordPress.
		    </div><hr>

            <h2 id="nav-container" class="nav-tab-wrapper">
            <a href="#head" class="nav-tab active" id="btn1">WP Head</a>
            <a href="#privacy" class="nav-tab" id="btn2">Privacy</a>
            <a href="#login" class="nav-tab" id="btn3">Login</a>
            <a href="#security" class="nav-tab" id="btn4">Security</a>
            <a href="#update" class="nav-tab" id="btn5">Update</a>
            <a href="#mail" class="nav-tab" id="btn6">Mailer</a>
            <a href="#header-footer" class="nav-tab" id="btn7">Header &amp; Footer</a>
            <a href="#maintenance" class="nav-tab" id="btn8">Maintenance</a>
            <a href="#posts" class="nav-tab" id="btn9">Posts &amp; Pages</a>
            <a href="#comments" class="nav-tab" id="btn10">Comments</a>
            <a href="#minify" class="nav-tab" id="btn11">Minify</a>
            <a href="#dashboard" class="nav-tab" id="btn12">Dashboard</a>
            </h2>
            <script>
                var header = document.getElementById("nav-container");
                var btns = header.getElementsByClassName("nav-tab");
                for (var i = 0; i < btns.length; i++) {
                    btns[i].addEventListener("click", function() {
                    var current = document.getElementsByClassName("active");
                    current[0].className = current[0].className.replace(" active", "");
                    this.className += " active";
                    });
                }
            </script>

            <div id="form_area">

            <form id="form" method="post" action="options.php">

                <?php settings_fields('aiows_plugin_global_section'); ?>

                <div id="plugin-head"> <?php

                    do_settings_sections('aiows_meta_tag_section');
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-privacy"> <?php

                    do_settings_sections('aiows_plugin_privacy_section');
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-login"> <?php

                    do_settings_sections('aiows_plugin_login_page_section');
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-security"> <?php
    
                    do_settings_sections('aiows_plugin_security_section');
                    ?> <br><b>Important Note:</b> <i>Use 'Enable HSTS Support' option if you have a valid SSL for the website. If you remove HTTPS before disabling HSTS your website will become inaccessible to visitors for up to the max-age you have set or until you support HTTPS again.</i><?php
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-update"> <?php
    
                    do_settings_sections('aiows_plugin_update_section');
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-mail"> <?php
    
                    do_settings_sections('aiows_plugin_mail_section');
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-hf"> <?php
    
                    do_settings_sections('aiows_plugin_header_footer_section');
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-maintenance"> <?php

                    do_settings_sections('aiows_plugin_maintenance_section');
                    ?> <br><b>Important Note:</b> <i>If you are using any cache plugin like W3 Total Cache or WP Super Cache or WP Rocket then please purge cache after saving changes to view the effect.</i><?php
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-post-page"> <?php
    
                    do_settings_sections('aiows_plugin_post_page_section');
                    ?> <br><b>Note:</b> <i>Do you want to show last updated info on your posts or pages? Try this plugin</i> - <a href="https://wordpress.org/plugin/wp-last-modified-info" target="_blank"> Wp Last Modified Info: </a>Untimate Last Modified Plugin for WordPress.
                    <?php
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-comments"> <?php
    
                    do_settings_sections('aiows_plugin_comments_section');
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-minify"> <?php
    
                    do_settings_sections('aiows_plugin_minify_section');
                    submit_button();

                ?> </div>

                <div style="display:none" id="plugin-dashborad"> <?php
    
                    do_settings_sections('aiows_plugin_dashboard_section');
                    submit_button();

                ?> </div>

            </form>

            <form id="form" method="post" action="">

            <div style="display:none" id="plugin-email-test">
            <h2>Email Test</h2><hr>
            <table class="form-table">
            <tr><th scope="row"><label for="send-to-field">Send Test Email to Email Address:</label></th>
                <td><input name="email_send_to" id="send-to-field" type="email" size="50" style="width:50%;" placeholder="info@yourdomain.com" required value="<?php echo esc_attr( aiows_plugin_issetor($email_send_to) ); ?>" />
                &nbsp;&nbsp;<span class="tooltip" title="Enter the email address where you want to sent test email."><span title="" class="dashicons dashicons-editor-help"></span></span>
            </td></tr>
            </table>

            <?php submit_button('Send Test Email', 'primary', 'aiows-submitted'); ?>
            
            </div> 
            </form>
            <form id="form" method="post">
            <div style="display:none" id="plugin-update-lock">
            <h2>Wordpress Update Lock</h2><hr>
            <table class="form-table">
            <tr><th scope="row"><label for="update-lock">WordPress Update Lock Status:</label></th>
                <td>
                <?php
                $checkcoreupdate = get_option('core_updater.lock', null); 
                if( $checkcoreupdate != null ) {
                    echo '<span class="fix-status" style="color:red">WordPress Update is locked. You need to fix it.</span>';
                } else {
                    echo '<span class="fix-status" style="color:green">There is no issue. You can continue with your <a href="update-core.php">WordPress Update</a></span>';
                }
                ?>
                &nbsp;&nbsp;<span class="tooltip" title="If you are seeing 'Another update currently in process' error on your WordPress site, but if it doesn’t automatically go away, then here is an easy fix for that. Just hit Fix WordPress Update Lock and it will fix this error."><span title="" class="dashicons dashicons-editor-help"></span></span>
            </td></tr>
            </table>
            <?php if( $checkcoreupdate != null ) { ?>
            <?php submit_button('Fix WordPress Update Lock', 'primary', 'fix-update-lock'); ?>
            <?php } ?>
            </div> 
        </form>
        </div>
        </div>
        <?php
        add_filter('admin_footer_text', 'aiows_remove_footer_admin');
    }

	/**
     * Register plugin settings
     */
    public function aiows_register_settings() {

        register_setting('aiows_plugin_global_section', 'aiows_plugin_global_options');

    /**
     * Register fields and sections
     */
        add_settings_section('aiows_meta_tag', 'Remove & Disable<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_meta_tag_section');

            add_settings_field('aiows_meta_generator_checkbox', '<label for="gen">Remove Generator Meta Tag</label>', array($this, 'aiows_meta_generator_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
		    add_settings_field('aiows_meta_wpmanifest_checkbox', '<label for="manifest">Remove WP Manifest Meta</label>', array($this, 'aiows_meta_wpmanifest_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
		    add_settings_field('aiows_meta_rsd_checkbox', '<label for="rsd">Remove All RSD Links Meta</label>', array($this, 'aiows_meta_rsd_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_meta_short_links_checkbox', '<label for="short">Remove Shortlinks Meta</label>', array($this, 'aiows_meta_short_links_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_posts_rel_link_wp_head_checkbox', '<label for="rel">Remove Adjacent Links Meta</label>', array($this, 'aiows_posts_rel_link_wp_head_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_meta_feed_remove_checkbox', '<label for="feedr">Remove Feed Meta Output</label>', array($this, 'aiows_meta_feed_remove_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_meta_jq_remove_checkbox', '<label for="jqr">Remove jQuery Migrate Output</label>', array($this, 'aiows_meta_jq_remove_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_remove_html_comments_checkbox', '<label for="hcom">Remove HTML Comments</label>', array($this, 'aiows_remove_html_comments_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_meta_xml_rpc_checkbox', '<label for="xml-rpc">Disable XML-RPC Fuctionality</label>', array($this, 'aiows_meta_xml_rpc_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_disable_wpjson_restapi_checkbox', '<label for="json-rest">Disable WP JSON and Rest API</label>', array($this, 'aiows_disable_wpjson_restapi_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');        
		    add_settings_field('aiows_disable_dns_prefetch_checkbox', '<label for="dns">Disable Auto DNS Prefetch</label>', array($this, 'aiows_disable_dns_prefetch_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_meta_feed_disable_checkbox', '<label for="feed">Disable WP Feed Fuctionality</label>', array($this, 'aiows_meta_feed_disable_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            add_settings_field('aiows_disable_yoast_schema_checkbox', '<label for="schema">Disable Yoast Schema Output</label>', array($this, 'aiows_disable_yoast_schema_checkbox_setting'), 'aiows_meta_tag_section' , 'aiows_meta_tag');
            
        add_settings_section('aiows_plugin_privacy', 'Privacy Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_privacy_section');
            add_settings_field('aiows_ver_remove_style_checkbox', '<label for="stylesheet-css">Remove Version from Stylesheet</label>', array($this, 'aiows_ver_remove_style_checkbox_setting'), 'aiows_plugin_privacy_section' , 'aiows_plugin_privacy');
            add_settings_field('aiows_ver_remove_script_checkbox', '<label for="script-js">Remove Version from Script</label>', array($this, 'aiows_ver_remove_script_checkbox_setting'), 'aiows_plugin_privacy_section' , 'aiows_plugin_privacy');
            add_settings_field('aiows_ver_remove_script_exclude_css', '<label for="exclude-css-js">Enter Stylesheet/Script file names to exclude from version removal (comma separated list)</label>', array($this, 'aiows_ver_remove_script_exclude_css'), 'aiows_plugin_privacy_section' , 'aiows_plugin_privacy');

        add_settings_section('aiows_plugin_login_page', 'Login Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_login_page_section');
        
            add_settings_field('aiows_login_with_username_checkbox', '<label for="username">Login to Dashboard with Username only</label>', array($this, 'aiows_login_with_username_checkbox_setting'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_disable_login_error_hint_checkbox', '<label for="log-err">Disable Error Hints on Login Page</label>', array($this, 'aiows_disable_login_error_hint_checkbox_setting'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_disable_login_page_shake_checkbox', '<label for="shake">Disable Shake Effect on Login Page</label>', array($this, 'aiows_disable_login_page_shake_checkbox_setting'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_send_email_on_login_checkbox', '<label for="nemail">Receive Notification Email on Login</label>', array($this, 'aiows_send_email_on_login_checkbox_setting'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_login_duration_time', '<label for="login-period">Set Stay Logged In Period</label>', array($this, 'aiows_login_duration_time_setting'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_admin_bar_login_checkbox', '<label for="ab-lr">Admin Bar Login / Register</label>', array($this, 'aiows_admin_bar_login_checkbox_setting'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_custom_login_title', '<label for="clt">Custom Text for Admin Bar Login</label>', array($this, 'aiows_custom_login_title_display'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_custom_register_title', '<label for="crt">Custom Text for Admin Bar Register</label>', array($this, 'aiows_custom_register_title_display'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_remove_default_login_logo_checkbox', '<label for="log-logo">Remove WP Logo from Login Page</label>', array($this, 'aiows_remove_default_login_logo_checkbox_setting'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_custom_login_logo_upload', '<label for="custom-logo-btn">Login Page Custom Logo</label>', array($this, 'aiows_custom_login_logo_upload_display'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_custom_bg_img_upload', '<label for="custom-bg-btn">Login Page Background Image</label>', array($this, 'aiows_custom_bg_img_upload_display'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_login_page_notice', '<label for="login-notice">Login Page Notice Message</label>', array($this, 'aiows_login_page_notice_display'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            add_settings_field('aiows_login_page_css', '<label for="login-css">Login Page Custom CSS</label>', array($this, 'aiows_login_page_css_display'), 'aiows_plugin_login_page_section' , 'aiows_plugin_login_page');
            
        add_settings_section('aiows_plugin_security', 'Security Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_security_section');

            add_settings_field('aiows_disable_user_enu_checkbox', '<label for="user-enu">Disable User Enumeration</label>', array($this, 'aiows_disable_user_enu_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            add_settings_field('aiows_enable_iframe_protection_checkbox', '<label for="iframe">Enable iFrame Protection</label>', array($this, 'aiows_enable_iframe_protection_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            add_settings_field('aiows_enable_no_sniff_header_checkbox', '<label for="nosniff">Enable No-Sniff Header</label>', array($this, 'aiows_enable_no_sniff_header_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            add_settings_field('aiows_enable_xss_header_checkbox', '<label for="xss">Enable XSS-Protection Header</label>', array($this, 'aiows_enable_xss_header_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            add_settings_field('aiows_remove_xpoweredby_checkbox', '<label for="xpw">Remove "X-Powered-by" Header</label>', array($this, 'aiows_remove_xpoweredby_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            add_settings_field('aiows_replace_mixed_checkbox', '<label for="mixed">Auto Replace Mixed Content (remove http/https)</label>', array($this, 'aiows_replace_mixed_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            //add_settings_field('aiows_cf_flex_ssl_checkbox', '<label for="cfssl">Enable CloudFlare Flexible SSL Support</label>', array($this, 'aiows_cf_flex_ssl_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            add_settings_field('aiows_enable_hsts_checkbox', '<label for="hsts">HTTP Strict Transport Security (HSTS) Support</label>', array($this, 'aiows_enable_hsts_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            add_settings_field('aiows_hsts_expire_time', '<label for="ex-time">HSTS Expire Period/Duration</label>', array($this, 'aiows_hsts_expire_time_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');  
            add_settings_field('aiows_enable_preload_cb', '<label for="preload">Enable HSTS Preloading</label>', array($this, 'aiows_enable_preload_cb_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');  
            add_settings_field('aiows_include_subdomains_cb', '<label for="subdomain">Include Subdomains in HSTS Header</label>', array($this, 'aiows_include_subdomains_cb_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');  
            add_settings_field('aiows_disable_copy_checkbox', '<label for="copy">Enable Copy Protection</label>', array($this, 'aiows_disable_copy_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            add_settings_field('aiows_force_ssl_detect_checkbox', '<label for="fssl">Force SSL Detection (for advanced user only)</label>', array($this, 'aiows_force_ssl_detect_checkbox_setting'), 'aiows_plugin_security_section' , 'aiows_plugin_security');
            
        add_settings_section('aiows_plugin_update', 'Update Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_update_section');

            add_settings_field('aiows_auto_plugin_update_checkbox', '<label for="auto-update">Enable Automatic Plugin Updates</label>', array($this, 'aiows_auto_plugin_update_checkbox_setting'), 'aiows_plugin_update_section' , 'aiows_plugin_update');
            add_settings_field('aiows_auto_theme_update_checkbox', '<label for="auto-theme">Enable Automatic Theme Updates</label>', array($this, 'aiows_auto_theme_update_checkbox_setting'), 'aiows_plugin_update_section' , 'aiows_plugin_update');
            add_settings_field('aiows_auto_tran_update_checkbox', '<label for="auto-tran">Disable Automatic Translation Updates (enabled by default)</label>', array($this, 'aiows_auto_tran_update_checkbox_setting'), 'aiows_plugin_update_section' , 'aiows_plugin_update');
            add_settings_field('aiows_wp_update_core_checkbox', '<label for="auto-core">Select WordPress Auto Core Update Level</label>', array($this, 'aiows_wp_update_core_checkbox_setting'), 'aiows_plugin_update_section' , 'aiows_plugin_update');
            add_settings_field('aiows_enable_update_vcs_checkbox', '<label for="vcs">Enable updates for VCS Installations</label>', array($this, 'aiows_enable_update_vcs_checkbox_setting'), 'aiows_plugin_update_section' , 'aiows_plugin_update');
            
        add_settings_section('aiows_plugin_mail', 'Mailer Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_mail_section');
       
            add_settings_field('aiows_custom_mail_sender_name', '<label for="cus-sname">Custom Mail Sender Name</label>', array($this, 'aiows_custom_mail_sender_name_display'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');
            add_settings_field('aiows_custom_mail_sender_email', '<label for="cus-semail">Custom Mail Sender Email</label>', array($this, 'aiows_custom_mail_sender_email_display'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');
            add_settings_field('aiows_enable_smtp_checkbox', '<label for="smtp">Enable SMTP Mailer</label>', array($this, 'aiows_enable_smtp_checkbox_setting'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');
            add_settings_field('aiows_custom_smtp_host', '<label for="host">Enter SMTP Host</label>', array($this, 'aiows_custom_smtp_host_display'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');
            add_settings_field('aiows_set_smtp_port', '<label for="port">Enter SMTP Port</label>', array($this, 'aiows_set_smtp_port_display'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');
            add_settings_field('aiows_enable_smtp_secure', '<label for="auth-secure">SMTP Encryption</label>', array($this, 'aiows_enable_smtp_secure_display'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');
            add_settings_field('aiows_enable_smtp_auth_checkbox', '<label for="auth">Enable Authentication</label>', array($this, 'aiows_enable_smtp_auth_checkbox_display'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');
            add_settings_field('aiows_smtp_auth_username', '<label for="auth-user">SMTP Username</label>', array($this, 'aiows_smtp_auth_username_display'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');
            add_settings_field('aiows_smtp_auth_password', '<label for="auth-pass">SMTP Password</label>', array($this, 'aiows_smtp_auth_password_display'), 'aiows_plugin_mail_section' , 'aiows_plugin_mail');            
            
        add_settings_section('aiows_plugin_header_footer', 'Header & Footer<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_header_footer_section');

            add_settings_field('aiows_plugin_header_area', '<label for="header">Custom Code to the beginning of Header</label>', array($this, 'aiows_plugin_header_area_display'), 'aiows_plugin_header_footer_section' , 'aiows_plugin_header_footer');
            add_settings_field('aiows_plugin_header_area_end', '<label for="header-end">Custom Code to the end of Header</label>', array($this, 'aiows_plugin_header_area_end_display'), 'aiows_plugin_header_footer_section' , 'aiows_plugin_header_footer');
            add_settings_field('aiows_plugin_footer_area', '<label for="footer">Custom Code to the beginning of Footer</label>', array($this, 'aiows_plugin_footer_area_display'), 'aiows_plugin_header_footer_section' , 'aiows_plugin_header_footer');
            add_settings_field('aiows_plugin_footer_area_end', '<label for="footer-end">Custom Code to the end of Footer</label>', array($this, 'aiows_plugin_footer_area_end_display'), 'aiows_plugin_header_footer_section' , 'aiows_plugin_header_footer');

        add_settings_section('aiows_plugin_maintenance', 'Maintenance Mode<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_maintenance_section');

            add_settings_field('aiows_enable_maintenance_mode_checkbox', '<label for="maintenance-mode">Maintenance Mode (Front-end Lockout)</label>', array($this, 'aiows_enable_maintenance_mode_checkbox_setting'), 'aiows_plugin_maintenance_section' , 'aiows_plugin_maintenance');
            add_settings_field('aiows_plugin_maintenance_custom_title', '<label for="mtitle">Maintenance Page Title</label>', array($this, 'aiows_plugin_maintenance_custom_title_field'), 'aiows_plugin_maintenance_section' , 'aiows_plugin_maintenance');
            add_settings_field('aiows_plugin_maintenance_header', '<label for="mheading">Maintenance Page Heading</label>', array($this, 'aiows_plugin_maintenance_header_field'), 'aiows_plugin_maintenance_section' , 'aiows_plugin_maintenance');
            add_settings_field('aiows_plugin_maintenance_body', 'Maintenance Page Body Element', array($this, 'aiows_plugin_maintenance_body_field'), 'aiows_plugin_maintenance_section' , 'aiows_plugin_maintenance');

        add_settings_section('aiows_plugin_post_page', 'Post & Pages Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_post_page_section');
        
            add_settings_field('aiows_add_publish_confirm_popup_checkbox', '<label for="cpopup">Show Confirmation Dialog before Publish</label>', array($this, 'aiows_add_publish_confirm_popup_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_feature_image_required_checkbox', '<label for="fimg">Make Featured Image Require For Posts</label>', array($this, 'aiows_feature_image_required_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_enable_category_count_checkbox', '<label for="category-count">Enable Category Count of Posts</label>', array($this, 'aiows_enable_category_count_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_enable_excerpt_on_pages_checkbox', '<label for="page-excerpt">Enable Excerpt on Pages</label>', array($this, 'aiows_enable_excerpt_on_pages_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_disable_post_autosave_checkbox', '<label for="autosave-disable">Disable Autosave of Posts</label>', array($this, 'aiows_disable_post_autosave_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_disable_post_revision_checkbox', '<label for="revision">Disable or Set Custom Post Revision</label>', array($this, 'aiows_disable_post_revision_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_revision_num', '<label for="rev-num">Number of Revisions to Keep</label></label>', array($this, 'aiows_revision_num_display'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_hide_pages_search_result_checkbox', '<label for="page-search">Hide Pages from Frontend Search Results</label>', array($this, 'aiows_hide_pages_search_result_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_disable_selection_patent_checkbox', '<label for="patent">Disable Selection of Parent Category</label>', array($this, 'aiows_disable_selection_patent_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_disable_texturization_checkbox', '<label for="textu">Disable Texturization - Smart Quotes</label>', array($this, 'aiows_disable_texturization_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_disable_auto_correct_checkbox', '<label for="wpauto">Disable WP Capitalization Auto Correction</label>', array($this, 'aiows_disable_auto_correct_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            add_settings_field('aiows_disable_auto_paragraph_checkbox', '<label for="autop">Disable Auto Inserted Paragraphs (i.e. &lt;p&gt;  tags)</label>', array($this, 'aiows_disable_auto_paragraph_checkbox_setting'), 'aiows_plugin_post_page_section' , 'aiows_plugin_post_page');
            
        add_settings_section('aiows_plugin_comments', 'Comments Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_comments_section');
        
            add_settings_field('aiows_remove_url_field_checkbox', '<label for="remove-url">Remove URL Field from Comment Form</label>', array($this, 'aiows_remove_url_field_checkbox_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_make_comment_link_nonclickable_checkbox', '<label for="cl-non">Make Comment Links Non Clickable</label>', array($this, 'aiows_make_comment_link_nonclickable_checkbox_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_remove_comment_link_checkbox', '<label for="cl-remove">Remove HTML Link Tags from comments</label>', array($this, 'aiows_remove_comment_link_checkbox_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_prevent_comment_spam_checkbox', '<label for="comment-spam">Prevent Comments From Spamming</label>', array($this, 'aiows_prevent_comment_spam_checkbox_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_disable_comments_checkbox', '<label for="comment-disable">Disable Wordpress Comments</label>', array($this, 'aiows_disable_comments_checkbox_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_enable_fb_comments_checkbox', '<label for="fb-comments">Enable Facebook Comments</label>', array($this, 'aiows_enable_fb_comments_checkbox_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_fb_app_id', '<label for="fb-app-id">Enter Facebook App ID</label>', array($this, 'aiows_fb_app_id_display'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_no_of_comments_to_display', '<label for="fb-comments-no">No. of FB Comments to Display</label>', array($this, 'aiows_no_of_comments_to_display_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_fb_comment_sorting', '<label for="fb-comments-sort">Sort Facebook Comments by</label>', array($this, 'aiows_fb_comment_sorting_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_fb_comment_language', '<label for="fb-comments-lang">Facebook Comments Language</label>', array($this, 'aiows_fb_comment_language_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_fb_comments_theme', '<label for="fb-comments-theme">Facebook Comments Box Theme</label>', array($this, 'aiows_fb_comments_theme_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            add_settings_field('aiows_fb_comment_msg', '<label for="fb-comments-msg">Message before FB Comment Box</label>', array($this, 'aiows_fb_comment_msg_setting'), 'aiows_plugin_comments_section' , 'aiows_plugin_comments');
            
        add_settings_section('aiows_plugin_minify', 'Minify Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_minify_section');
        
            add_settings_field('aiows_enable_html_minify_cb', '<label for="ehtml">Enable HTML Minification</label>', array($this, 'aiows_enable_html_minify_cb_display'), 'aiows_plugin_minify_section' , 'aiows_plugin_minify');
            add_settings_field('aiows_enable_css_minify_cb', '<label for="ecss">Also Enable CSS Minification</label>', array($this, 'aiows_enable_css_minify_cb_display'), 'aiows_plugin_minify_section' , 'aiows_plugin_minify');
            add_settings_field('aiows_enable_js_minify_cb', '<label for="ejs">Also Enable JS Minification</label>', array($this, 'aiows_enable_js_minify_cb_display'), 'aiows_plugin_minify_section' , 'aiows_plugin_minify');
            add_settings_field('aiows_minify_allow_override_cb', '<label for="rovr">Allow Minification Override</label>', array($this, 'aiows_minify_allow_override_cb_display'), 'aiows_plugin_minify_section' , 'aiows_plugin_minify');
            add_settings_field('aiows_minify_allow_raw_tag_cb', '<label for="rraw">Exclude Raw Tags from Minification</label>', array($this, 'aiows_minify_allow_raw_tag_cb_display'), 'aiows_plugin_minify_section' , 'aiows_plugin_minify');
            add_settings_field('aiows_enable_minify_liu_cb', '<label for="mliu">Enable Minification for Logged in Users</label>', array($this, 'aiows_enable_minify_liu_cb_display'), 'aiows_plugin_minify_section' , 'aiows_plugin_minify');
            
        add_settings_section('aiows_plugin_dashboard', 'Dashboard Options<p><hr></p>', array($this, 'aiows_plugin_option_callback'), 'aiows_plugin_dashboard_section');

            add_settings_field('aiows_custom_admin_footer_text', '<label for="adminf">Enter Custom Left Footer Text (HTML tags supported)</label>', array($this, 'aiows_custom_admin_footer_text'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_custom_right_footer_text', '<label for="adminrf">Enter Custom Right Footer Text (HTML tags supported)</label>', array($this, 'aiows_custom_right_footer_text'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_change_pg_perma', '<label for="page-perma">Set Custom Extension for Pages (applicable site-wide)</label>', array($this, 'aiows_change_pg_perma_display'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_disable_admin_bar_all_checkbox', '<label for="admin-tbar">Disable Admin Top Bar</label>', array($this, 'aiows_disable_admin_bar_all_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_disable_admin_logo_checkbox', '<label for="wplogo">Remove Admin Bar WP Logo</label>', array($this, 'aiows_disable_admin_logo_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_disable_file_editor_checkbox', '<label for="tp-editor">Disable Themes & Plugins File Editor</label>', array($this, 'aiows_disable_file_editor_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_disable_help_tab_checkbox', '<label for="help-tab">Remove Help Tabs from Dashboard</label>', array($this, 'aiows_disable_help_tab_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_disable_screen_tab_checkbox', '<label for="screen-tab">Remove Screen Options Tabs</label>', array($this, 'aiows_disable_screen_tab_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_enable_wp_link_manager_checkbox', '<label for="link-manager">Enable WordPress Link Manager</label>', array($this, 'aiows_enable_wp_link_manager_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_enable_single_column_dashboard_checkbox', '<label for="scolumn">Enable Single Column Dashboard</label>', array($this, 'aiows_enable_single_column_dashboard_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_disable_emoji_checkbox', '<label for="wpemoji">Disable WP Default Emojis</label>', array($this, 'aiows_disable_emoji_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_set_auto_alt_checkbox', '<label for="img-alt">Set "alt" Attribute for Images</label>', array($this, 'aiows_set_auto_alt_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_disable_self_ping_checkbox', '<label for="self-ping">Disable Self Pingbacks & Trackbacks</label>', array($this, 'aiows_disable_self_ping_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_disable_wp_search_checkbox', '<label for="wp-search">Disable WordPress Search Feature</label>', array($this, 'aiows_disable_wp_search_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_enable_shortcodes_checkbox', '<label for="shortcode-everywhere">Enable Shortcodes in WordPress Backend</label>', array($this, 'aiows_enable_shortcodes_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_remove_page_title_checkbox', '<label for="wp-title">Remove "WordPress" text from Dashboard Page Title</label>', array($this, 'aiows_remove_page_title_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_remove_orphan_shortcodes_checkbox', '<label for="orphan-sc">Remove Orphan Shortcodes from Posts & Pages Automatically</label>', array($this, 'aiows_remove_orphan_shortcodes_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_enable_plugin_last_update_checkbox', '<label for="plug-last">Show Plugins Last Update Details</label>', array($this, 'aiows_enable_plugin_last_update_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_enable_sanitize_checkbox', '<label for="sanitize">Enable Sanitization of WordPress</label>', array($this, 'aiows_enable_sanitize_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_replace_howdy_welcome_checkbox', '<label for="howdy">Replace &#39;Howdy&#39; text with &#39;Welcome&#39; or Custom Text</label>', array($this, 'aiows_replace_howdy_welcome_checkbox_setting'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            add_settings_field('aiows_custom_welcome_text', '<label for="custom-howdy">Custom Welcome/Howdy Text</label></label>', array($this, 'aiows_custom_welcome_text_display'), 'aiows_plugin_dashboard_section' , 'aiows_plugin_dashboard');
            
}

    public function aiows_plugin_option_callback() {
        // no callback as of now
    }

/* ==================================================================

                                 Header

===================================================================== */

    public function aiows_meta_generator_checkbox_setting() {
        ?> <label class="switch">
        <input id="gen" name="aiows_plugin_global_options[aiows_meta_generator_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_meta_generator_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove generator meta tag (e.g. wp, layerslider, visual composer, wpml) from WP head."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }


	public function aiows_meta_wpmanifest_checkbox_setting() {
        ?> <label class="switch">
        <input id="manifest" name="aiows_plugin_global_options[aiows_meta_wpmanifest_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_meta_wpmanifest_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove manifest (windows live writer) link from WP head."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_meta_rsd_checkbox_setting() {
        ?> <label class="switch">
        <input id="rsd" name="aiows_plugin_global_options[aiows_meta_rsd_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_meta_rsd_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove rsd links from WP head."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_meta_short_links_checkbox_setting() {
        ?> <label class="switch">
        <input id="short" name="aiows_plugin_global_options[aiows_meta_short_links_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_meta_short_links_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove shortlinks from WP head."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_posts_rel_link_wp_head_checkbox_setting() {
        ?> <label class="switch">
        <input id="rel" name="aiows_plugin_global_options[aiows_posts_rel_link_wp_head_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_posts_rel_link_wp_head_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove next and previous post links from WP head."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_remove_html_comments_checkbox_setting() {
        ?> <label class="switch">
        <input id="hcom" name="aiows_plugin_global_options[aiows_remove_html_comments_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_remove_html_comments_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove unwanted html comments of yoast seo or other plugins from wp head."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_meta_feed_remove_checkbox_setting() {
        ?> <label class="switch">
        <input id="feedr" name="aiows_plugin_global_options[aiows_meta_feed_remove_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_meta_feed_remove_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove feed link metas from wp head."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_meta_jq_remove_checkbox_setting() {
        ?> <label class="switch">
        <input id="jqr" name="aiows_plugin_global_options[aiows_meta_jq_remove_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_meta_jq_remove_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove feed link metas from wp head."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

	public function aiows_meta_xml_rpc_checkbox_setting() {
        ?> <label class="switch">
        <input id="xml-rpc" name="aiows_plugin_global_options[aiows_meta_xml_rpc_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_meta_xml_rpc_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable wordpress xml-rpc fuctionality (not recommended as it is required for other plugins like Jetpack)."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_disable_wpjson_restapi_checkbox_setting() {
        ?> <label class="switch">
        <input id="json-rest" name="aiows_plugin_global_options[aiows_disable_wpjson_restapi_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_wpjson_restapi_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable wordpress json and rest api."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_disable_dns_prefetch_checkbox_setting() {
        ?> <label class="switch">
        <input id="dns" name="aiows_plugin_global_options[aiows_disable_dns_prefetch_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_dns_prefetch_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable dns prefetch metas."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_meta_feed_disable_checkbox_setting() {
        ?> <label class="switch">
        <input id="feed" name="aiows_plugin_global_options[aiows_meta_feed_disable_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_meta_feed_disable_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable wordpress feed functionality completely."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_disable_yoast_schema_checkbox_setting() {
        ?> <label class="switch">
        <input id="schema" name="aiows_plugin_global_options[aiows_disable_yoast_schema_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_yoast_schema_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable yoast seo json-tld schema output."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

/* ==================================================================

                               Privacy

===================================================================== */

    public function aiows_ver_remove_style_checkbox_setting() {
        ?> <label class="switch">
        <input id="stylesheet-css" name="aiows_plugin_global_options[aiows_ver_remove_style_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_ver_remove_style_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove wordpress version number from stylesheets (not applicable for not logged in users)."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_ver_remove_script_checkbox_setting() {
        ?> <label class="switch">
        <input id="script-js" name="aiows_plugin_global_options[aiows_ver_remove_script_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_ver_remove_script_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove wordpress version number from scripts (not applicable for not logged in users)."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_ver_remove_script_exclude_css() {
        ?>
        <textarea id="exclude-css-js" placeholder="Enter comma separated list of file names (Stylesheet/Script files) to exclude them from version removal process. Version info will be kept for these files." name="aiows_plugin_global_options[aiows_ver_remove_script_exclude_css]" rows="7" cols="95" style="width:95%;"><?php if (isset($this->options_global['aiows_ver_remove_script_exclude_css'])) { echo $this->options_global['aiows_ver_remove_script_exclude_css']; } ?></textarea>
        <?php
    }

/* ==================================================================

                                 Login

===================================================================== */

    public function aiows_login_with_username_checkbox_setting() {
        ?> <label class="switch">
        <input id="username" name="aiows_plugin_global_options[aiows_login_with_username_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_login_with_username_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to login to dashboard with username only."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }


    public function aiows_disable_login_error_hint_checkbox_setting() {
        ?> <label class="switch">
        <input id="log-err" name="aiows_plugin_global_options[aiows_disable_login_error_hint_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_login_error_hint_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable wordpress login error hints."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }


    public function aiows_disable_login_page_shake_checkbox_setting() {
        ?> <label class="switch">
        <input id="shake" name="aiows_plugin_global_options[aiows_disable_login_page_shake_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_login_page_shake_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove shaking effect from login page."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php

    }

    public function aiows_send_email_on_login_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="nemail" name="aiows_plugin_global_options[aiows_send_email_on_login_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_send_email_on_login_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to receive notification when a user logs in to dashboard."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_login_duration_time_setting() {
        ?> <input id="login-period" name="aiows_plugin_global_options[aiows_login_duration_time]" type="number" size="20" style="width:20%;" placeholder="31556926" value="<?php if (isset($this->options_global['aiows_login_duration_time'])) { echo $this->options_global['aiows_login_duration_time']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="By default, WordPress keep you logged in for 2 weeks if you check the &#39;remember me&#39; option while logging in. You can convert any time to seconds and set it accordingly if you want to be longer or shorter. We would suggest going for a month: 2629746 seconds."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_admin_bar_login_checkbox_setting() {

        if(!isset($this->options_global['aiows_admin_bar_login_checkbox'])){
            $this->options_global['aiows_admin_bar_login_checkbox'] = 'Not show';
        }

        $items = array("Not show", "Show Login", "Show Register", "Show Both");
    echo "<select id='ab-lr' name='aiows_plugin_global_options[aiows_admin_bar_login_checkbox]'>";
    foreach($items as $item) {
        $selected = ($this->options_global['aiows_admin_bar_login_checkbox'] == $item) ? 'selected="selected"' : '';
        echo "<option value='$item' $selected>$item</option>";
    }
    echo "</select>";

        ?>&nbsp;&nbsp;<span class="tooltip" title="Select option to show login and register option on admin bar in front end of your site."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_custom_login_title_display() {
        ?> <input id="clt" name="aiows_plugin_global_options[aiows_custom_login_title]" type="text" size="30" style="width:30%;" placeholder="Login" value="<?php if (isset($this->options_global['aiows_custom_login_title'])) { echo $this->options_global['aiows_custom_login_title']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Set custom login text for admin bar login option in frontend."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }
    
    public function aiows_custom_register_title_display() {
        ?> <input id="crt" name="aiows_plugin_global_options[aiows_custom_register_title]" type="text" size="30" style="width:30%;" placeholder="Register" value="<?php if (isset($this->options_global['aiows_custom_register_title'])) { echo $this->options_global['aiows_custom_register_title']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Set custom login text for admin bar register option in frontend."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_remove_default_login_logo_checkbox_setting() {
        ?> <label class="switch">
        <input id="log-logo" name="aiows_plugin_global_options[aiows_remove_default_login_logo_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_remove_default_login_logo_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove or set custom logo on wordpress login page. Keep this option enabled, if you have set custom login logo."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php

    }

    public function aiows_custom_login_logo_upload_display() {
        ?> <input id="custom-logo" class="header_logo_url" name="aiows_plugin_global_options[aiows_custom_login_logo_upload]" type="url" size="40" style="width:40%;" placeholder="<?php echo get_site_url() . '/wp-content/uploads/logo.png' ?>" value="<?php if (isset($this->options_global['aiows_custom_login_logo_upload'])) { echo $this->options_global['aiows_custom_login_logo_upload']; } ?>" />
        <input id="custom-logo-btn" type="button" class="button header_logo_upload" value="<?php _e( 'Select Logo', 'aiows' ); ?>" />
        <input type="button" class="button" onclick="document.getElementById('custom-logo').value = ''" value="<?php _e( 'Remove', 'aiows' ); ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="Select custom logo for wordpress login page."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_custom_bg_img_upload_display() {
        ?> <input id="custom-bg-img" class="login_bg_url" name="aiows_plugin_global_options[aiows_custom_bg_img_upload]" type="url" size="40" style="width:40%;" placeholder="<?php echo get_site_url() . '/wp-content/uploads/bg.jpg' ?>" value="<?php if (isset($this->options_global['aiows_custom_bg_img_upload'])) { echo $this->options_global['aiows_custom_bg_img_upload']; } ?>" />
        <input id="custom-bg-btn" type="button" class="button login_bg_upload" value="<?php _e( 'Select Background Image', 'aiows' ); ?>" />
        <input type="button" class="button" onclick="document.getElementById('custom-bg-img').value = ''" value="<?php _e( 'Remove', 'aiows' ); ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="Select custom background image for wordpress login page."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_login_page_notice_display() {
        ?>
        <textarea id="login-notice" placeholder="Enter custom message which you want to display on login page as admin notice." name="aiows_plugin_global_options[aiows_login_page_notice]" rows="4" cols="95" style="width:95%;"><?php if (isset($this->options_global['aiows_login_page_notice'])) { echo $this->options_global['aiows_login_page_notice']; } ?></textarea>
        <?php
    }

    public function aiows_login_page_css_display() {
        ?>
        <textarea id="login-css" placeholder="Write custom css styles for wp login page." name="aiows_plugin_global_options[aiows_login_page_css]" rows="7" cols="95" style="width:95%;"><?php if (isset($this->options_global['aiows_login_page_css'])) { echo $this->options_global['aiows_login_page_css']; } ?></textarea>
        <?php
    }

/* ==================================================================

                                 Security

===================================================================== */

    public function aiows_disable_user_enu_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="user-enu" name="aiows_plugin_global_options[aiows_disable_user_enu_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_user_enu_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to stop users enumeration."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }


    public function aiows_enable_iframe_protection_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="iframe" name="aiows_plugin_global_options[aiows_enable_iframe_protection_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_iframe_protection_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to stop other sites from displaying your content in a frame or iframe."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_enable_no_sniff_header_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="nosniff" name="aiows_plugin_global_options[aiows_enable_no_sniff_header_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_no_sniff_header_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to send the 'X-Content-Type-Options: nosniff' header to prevent Internet Explorer and Google Chrome from MIME-sniffing away from the declared Content-Type."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_enable_xss_header_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="xss" name="aiows_plugin_global_options[aiows_enable_xss_header_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_xss_header_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to send the 'X-XSS-Protection' header to prevent Internet Explorer and Google Chrome from page loading when they detect reflected cross-site scripting (XSS) attacks."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_remove_xpoweredby_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="xpw" name="aiows_plugin_global_options[aiows_remove_xpoweredby_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_remove_xpoweredby_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="'X-Powered-By' is a common non-standard HTTP response header (most headers prefixed with an 'X-' are non-standard). It's often included by default in responses constructed via a particular scripting technology. Enable this if you want to hide 'X-Powered-by' Header."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_replace_mixed_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="mixed" name="aiows_plugin_global_options[aiows_replace_mixed_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_replace_mixed_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to fix mixed content error on a SSL site (appllicable for not logged in users only)."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }


   /* public function aiows_cf_flex_ssl_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="cfssl" name="aiows_plugin_global_options[aiows_cf_flex_ssl_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_cf_flex_ssl_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to fix loop of CloudFlare flexible SSL."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }*/


    public function aiows_disable_copy_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="copy" name="aiows_plugin_global_options[aiows_disable_copy_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_copy_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable the 'Right Click', 'Text Selection' and 'Copy' option on the front end of your site (not applicable for logged in users)."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_enable_hsts_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="hsts" name="aiows_plugin_global_options[aiows_enable_hsts_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_hsts_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="HTTP Strict Transport Security (HSTS, RFC 6797) is a header which allows a website to specify and enforce security policy in client web browsers. This policy enforcement protects secure websites from downgrade attacks, SSL stripping, and cookie hijacking. It allows a web server to declare a policy that browsers will only connect using secure HTTPS connections, and ensures end users do not “click through” critical security warnings. HSTS is an important security mechanism for high security websites. HSTS headers are only respected when served over HTTPS connections, not HTTP."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_hsts_expire_time_setting() {

        if(!isset($this->options_global['aiows_hsts_expire_time'])){
            $this->options_global['aiows_hsts_expire_time'] = 'Not set';
        }

        $items = array("Not set", "1 month", "2 months", "3 months", "4 months", "5 months", "6 months", "12 months");
    echo "<select id='ex-time' name='aiows_plugin_global_options[aiows_hsts_expire_time]'>";
    foreach($items as $item) {
        $selected = ($this->options_global['aiows_hsts_expire_time'] == $item) ? 'selected="selected"' : '';
        echo "<option value='$item' $selected>$item</option>";
    }
    echo "</select>";

        ?>&nbsp;&nbsp;<span class="tooltip" title="HSTS includes a “max-age” parameter which specifies the duration HSTS will continue to be cached and enforced by the web browser. This parameter generally is set at 6 months by default, however you must use a minimum of 12 months if you wish to be included in the HSTS Preload list (see below). The special value of “0” means HSTS is disabled and will no longer be cached by the client web browser. For the amount of time specified in the max-age header after a website is successfully accessed over HTTPS, the browser will enforce this HSTS policy, requiring HTTPS with correctly-configured certificates. Recommended 6 months."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_enable_preload_cb_setting() {
        ?>
        <label class="switch">
        <input id="preload" name="aiows_plugin_global_options[aiows_enable_preload_cb]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_preload_cb'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to preload your website over HTTPS. This flag signals to web browsers that a website’s HSTS configuration is eligible for preloading, that is, inclusion into the browser’s core configuration. Without preload, HSTS is only set after an initial successful HTTPS request, and thus if an attacker can intercept and downgrade that first request, HSTS can be bypassed. With preload, this attack is prevented."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_include_subdomains_cb_setting() {
        ?>
        <label class="switch">
        <input id="subdomain" name="aiows_plugin_global_options[aiows_include_subdomains_cb]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_include_subdomains_cb'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable HSTS on your website as well as all subdomains. This parameter applies the HSTS policy from a parent domain (such as example.com) to subdomains (such as www.development.example.com or api.example.com). Caution is encouraged with this header, as if any subdomains do not work with HTTPS they will become inaccessible."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_force_ssl_detect_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="fssl" name="aiows_plugin_global_options[aiows_force_ssl_detect_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_force_ssl_detect_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you are a advanced user. This option is useful when your website is behind a load balancer and the SSL encryption is handled by the loadbalancer itself."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

/* ==================================================================

                               Update

===================================================================== */

    public function aiows_auto_plugin_update_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="auto-update" name="aiows_plugin_global_options[aiows_auto_plugin_update_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_auto_plugin_update_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable automatic plugins update."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_auto_theme_update_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="auto-theme" name="aiows_plugin_global_options[aiows_auto_theme_update_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_auto_theme_update_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable automatic themes update."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_auto_tran_update_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="auto-tran" name="aiows_plugin_global_options[aiows_auto_tran_update_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_auto_tran_update_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable auto translation updates."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_wp_update_core_checkbox_setting() {

        if(!isset($this->options_global['aiows_wp_update_core_checkbox'])){
            $this->options_global['aiows_wp_update_core_checkbox'] = 'Minor';
        }

    $items = array("Minor", "Major", "Development", "Disable");
    echo "<select id='auto-core' name='aiows_plugin_global_options[aiows_wp_update_core_checkbox]'>";
    foreach($items as $item) {
        $selected = ($this->options_global['aiows_wp_update_core_checkbox']==$item) ? 'selected="selected"' : '';
        echo "<option value='$item' $selected>$item</option>";
    }
    echo "</select>";

        ?>&nbsp;&nbsp;<span class="tooltip" title="Select WordPress auto code update level from here. 'Minor' is default."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_enable_update_vcs_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="vcs" name="aiows_plugin_global_options[aiows_enable_update_vcs_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_update_vcs_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable updates for WP VCS installations."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

/* ==================================================================

                            Mailing

===================================================================== */

    public function aiows_custom_mail_sender_name_display() {
        ?>
        <input id="cus-sname" name="aiows_plugin_global_options[aiows_custom_mail_sender_name]" type="text" size="30" style="width:30%;" placeholder="Mail Sender Name" value="<?php if (isset($this->options_global['aiows_custom_mail_sender_name'])) { echo $this->options_global['aiows_custom_mail_sender_name']; } ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="Enter custom WordPress mail sender name. Leave it blank, to use default mail sender name."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_custom_mail_sender_email_display() {
        ?>
        <input id="cus-semail" name="aiows_plugin_global_options[aiows_custom_mail_sender_email]" type="email" size="35" style="width:35%;" placeholder="no-reply@yourdomain.com" value="<?php if (isset($this->options_global['aiows_custom_mail_sender_email'])) { echo $this->options_global['aiows_custom_mail_sender_email']; } ?>" />
        &nbsp;&nbsp;<span class="tooltip" title="Enter custom WordPress mail sender email. Leave it blank, to use default mail sender email."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_enable_smtp_checkbox_setting() {
        ?>
        <label class="switch">
        <input id="smtp" name="aiows_plugin_global_options[aiows_enable_smtp_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_smtp_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable SMTP mailer in your wordpress website."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_custom_smtp_host_display() {
        ?> <input id="host" name="aiows_plugin_global_options[aiows_custom_smtp_host]" type="text" size="35" style="width:35%;" placeholder="mail@yourdomain.com" value="<?php if (isset($this->options_global['aiows_custom_smtp_host'])) { echo $this->options_global['aiows_custom_smtp_host']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Enter SMTP host here."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_set_smtp_port_display() {
        ?> <input id="port" name="aiows_plugin_global_options[aiows_set_smtp_port]" type="number" size="8" style="width:8%;" placeholder="25" value="<?php if (isset($this->options_global['aiows_set_smtp_port'])) { echo $this->options_global['aiows_set_smtp_port']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Enter SMTP posrt here. Some common posts are 25 (non ssl/tls), 465, 587, 26 (for gmail smtp)."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_enable_smtp_auth_checkbox_display() {
        ?>
        <label class="switch">
        <input id="auth" name="aiows_plugin_global_options[aiows_enable_smtp_auth_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_smtp_auth_checkbox'] )); ?> />
        <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to use smtp authentication."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_enable_smtp_secure_display() {

        if(!isset($this->options_global['aiows_enable_smtp_secure'])){
            $this->options_global['aiows_enable_smtp_secure'] = 'None';
        }

        $items = array("None", "TLS", "SSL");
    echo "<select id='auth-secure' name='aiows_plugin_global_options[aiows_enable_smtp_secure]'>";
    foreach($items as $item) {
        $selected = ($this->options_global['aiows_enable_smtp_secure'] == $item) ? 'selected="selected"' : '';
        echo "<option value='$item' $selected>$item</option>";
    }
    echo "</select>";

        ?>&nbsp;&nbsp;<span class="tooltip" title="For most servers TLS is the recommended option. If your SMTP provider offers both SSL and TLS options, we recommend using TLS."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_smtp_auth_username_display() {
        ?> <input id="auth-user" name="aiows_plugin_global_options[aiows_smtp_auth_username]" type="text" size="30" style="width:30%;" autocomplete="off" placeholder="info@yourdomain.com" value="<?php if (isset($this->options_global['aiows_smtp_auth_username'])) { echo $this->options_global['aiows_smtp_auth_username']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Enter SMTP username for authentication."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

    public function aiows_smtp_auth_password_display() {
        ?> <input id="auth-pass" name="aiows_plugin_global_options[aiows_smtp_auth_password]" type="password" size="20" style="width:20%;" autocomplete="off" placeholder="********" value="<?php if (isset($this->options_global['aiows_smtp_auth_password'])) { echo $this->options_global['aiows_smtp_auth_password']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Enter SMTP password for authentication."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

/* ==================================================================

                            Header & Footer

===================================================================== */

public function aiows_plugin_header_area_display() {
        ?>
        <textarea id="header" placeholder="Enter your custom header code here." name="aiows_plugin_global_options[aiows_plugin_header_area]" rows="10" cols="95" style="width:95%"><?php if (isset($this->options_global['aiows_plugin_header_area'])) { echo $this->options_global['aiows_plugin_header_area']; } ?></textarea>
        <br>These snippets will be printed at very beginning of the <code>&lt;head&gt;</code> section. Do not place plain text in this!
        <?php
}

public function aiows_plugin_header_area_end_display() {
    ?>
    <textarea id="header" placeholder="Enter your custom header code here." name="aiows_plugin_global_options[aiows_plugin_header_area_end]" rows="10" cols="95" style="width:95%"><?php if (isset($this->options_global['aiows_plugin_header_area_end'])) { echo $this->options_global['aiows_plugin_header_area_end']; } ?></textarea>
    <br>These snippets will be printed at the end of <code>&lt;head&gt;</code> section. Do not place plain text in this!
    <?php
}

public function aiows_plugin_footer_area_display() {
        ?>
        <textarea id="footer" placeholder="Enter your custom footer code here." name="aiows_plugin_global_options[aiows_plugin_footer_area]" rows="10" cols="95" style="width:95%"><?php if (isset($this->options_global['aiows_plugin_footer_area'])) { echo $this->options_global['aiows_plugin_footer_area']; } ?></textarea>
        <br>These snippets will be printed at very beginning of the <code>&lt;/body&gt;</code> tag before a footers scripts. Do not place plain text in this!
        <?php
}

public function aiows_plugin_footer_area_end_display() {
    ?>
    <textarea id="footer" placeholder="Enter your custom footer code here." name="aiows_plugin_global_options[aiows_plugin_footer_area_end]" rows="10" cols="95" style="width:95%"><?php if (isset($this->options_global['aiows_plugin_footer_area_end'])) { echo $this->options_global['aiows_plugin_footer_area_end']; } ?></textarea>
    <br>These snippets will be printed at the end of <code>&lt;/body&gt;</code> tag after all footers scripts. Do not place plain text in this!
    <?php
}

/* ==================================================================

                            Maintenance

===================================================================== */

public function aiows_enable_maintenance_mode_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="auth" name="aiows_plugin_global_options[aiows_enable_maintenance_mode_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_maintenance_mode_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable maintenance mode (not applicable for logged in users)."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_plugin_maintenance_custom_title_field() {

    if(empty($this->options_global['aiows_plugin_maintenance_custom_title'])){
        $this->options_global['aiows_plugin_maintenance_custom_title'] = 'Under Maintenance!';
    }
        ?>
        <input id="mtitle" name="aiows_plugin_global_options[aiows_plugin_maintenance_custom_title]" type="text" size="40" style="width:40%" placeholder="Maintenance Mode" value="<?php if (isset($this->options_global['aiows_plugin_maintenance_custom_title'])) { echo $this->options_global['aiows_plugin_maintenance_custom_title']; } ?>" />
  &nbsp;&nbsp;<span class="tooltip" title="Enter custom title for maintenance page here."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

public function aiows_plugin_maintenance_header_field() {

    if(empty($this->options_global['aiows_plugin_maintenance_header'])){
        $this->options_global['aiows_plugin_maintenance_header'] = 'This Website is Under Maintenance!';
    }
        ?>
        <input id="mheading" name="aiows_plugin_global_options[aiows_plugin_maintenance_header]" type="text" size="60" style="width:60%" placeholder="Maintenance mode is active!" value="<?php if (isset($this->options_global['aiows_plugin_maintenance_header'])) { echo $this->options_global['aiows_plugin_maintenance_header']; } ?>" />
  &nbsp;&nbsp;<span class="tooltip" title="Enter custom title for maintenance page here."><span title="" class="dashicons dashicons-editor-help"></span></span>
        <?php
    }

public function aiows_plugin_maintenance_body_field() {

    if(empty($this->options_global['aiows_plugin_maintenance_body'])){
        $this->options_global['aiows_plugin_maintenance_body'] = 'We are performing scheduled maintenance. We will be back on-line shortly!';
    }
    $args = array(
        //'teeny'         => true,
        'textarea_name' => 'aiows_plugin_global_options[aiows_plugin_maintenance_body]',
        //'textarea_rows' => '15',
        'editor_height' => '300',
        //'quicktags' => false
    );
    wp_editor( html_entity_decode($this->options_global['aiows_plugin_maintenance_body'], ENT_COMPAT, "UTF-8"), 'aiows_plugin_maintenance_body_content', $args );

}

/* ==================================================================

                              posts and pages

===================================================================== */

public function aiows_add_publish_confirm_popup_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="cpopup" name="aiows_plugin_global_options[aiows_add_publish_confirm_popup_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_add_publish_confirm_popup_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to add a warning to avoid accidental publishing."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_feature_image_required_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="fimg" name="aiows_plugin_global_options[aiows_feature_image_required_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_feature_image_required_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to maake featured image required at the time of publishing."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_category_count_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="category-count" name="aiows_plugin_global_options[aiows_enable_category_count_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_category_count_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable category count in posts page."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_excerpt_on_pages_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="page-excerpt" name="aiows_plugin_global_options[aiows_enable_excerpt_on_pages_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_excerpt_on_pages_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable excerpt on pages. This will add an excerpt box in page edit page."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_post_autosave_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="autosave-disable" name="aiows_plugin_global_options[aiows_disable_post_autosave_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_post_autosave_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable posts autosave."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_post_revision_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="revision" name="aiows_plugin_global_options[aiows_disable_post_revision_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_post_revision_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable posts revision. Keep this option enabled, if you have used next option."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_revision_num_display() {
    ?> <input id="rev-num" name="aiows_plugin_global_options[aiows_revision_num]" type="number" size="8" style="width:8%;" placeholder="3" value="<?php if (isset($this->options_global['aiows_revision_num'])) { echo $this->options_global['aiows_revision_num']; } ?>" />
&nbsp;&nbsp;<span class="tooltip" title="Enter the number, you want to set as post revision."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_hide_pages_search_result_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="page-search" name="aiows_plugin_global_options[aiows_hide_pages_search_result_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_hide_pages_search_result_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to hide all pages from search results."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_selection_patent_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="patent" name="aiows_plugin_global_options[aiows_disable_selection_patent_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_selection_patent_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable selection of parent categories."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_texturization_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="textu" name="aiows_plugin_global_options[aiows_disable_texturization_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_texturization_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable Texturization - Smart Quotes."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_auto_correct_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="wpauto" name="aiows_plugin_global_options[aiows_disable_auto_correct_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_auto_correct_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable auto correction of WP Capitalization."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_auto_paragraph_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="autop" name="aiows_plugin_global_options[aiows_disable_auto_paragraph_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_auto_paragraph_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable auto insert of <p> tags."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

/* ==================================================================

                               Comments

===================================================================== */

public function aiows_remove_url_field_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="remove-url" name="aiows_plugin_global_options[aiows_remove_url_field_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_remove_url_field_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to hide default url/website field from wordpress comment form."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_make_comment_link_nonclickable_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="cl-non" name="aiows_plugin_global_options[aiows_make_comment_link_nonclickable_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_make_comment_link_nonclickable_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to make links non clickable in wordpress comments."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_remove_comment_link_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="cl-remove" name="aiows_plugin_global_options[aiows_remove_comment_link_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_remove_comment_link_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove links from wordpress comments."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_prevent_comment_spam_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="comment-spam" name="aiows_plugin_global_options[aiows_prevent_comment_spam_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_prevent_comment_spam_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to prevent comment spamming. It will show a error to users if there is no valid referrer."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_comments_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="fb-comment-post" name="aiows_plugin_global_options[aiows_disable_comments_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_comments_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable comments globally. It will hide all instances of wordpress comments from wordpress dashboard. You can also use fb comments after keep enabling this."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_fb_comments_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="fb-comments" name="aiows_plugin_global_options[aiows_enable_fb_comments_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_fb_comments_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable facebook comments for posts and pages. You can set/unset where to show fb comments from edit page."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_fb_app_id_display() {
    ?>
    <input id="fb-app-id" name="aiows_plugin_global_options[aiows_fb_app_id]" type="text" size="20" style="width:20%" placeholder="Enter facebook app id" value="<?php if (isset($this->options_global['aiows_fb_app_id'])) { echo $this->options_global['aiows_fb_app_id']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Enter your facebook app id."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_no_of_comments_to_display_setting() {
    if(empty($this->options_global['aiows_no_of_comments_to_display'])){
        $this->options_global['aiows_no_of_comments_to_display'] = '10';
    }
    ?>
    <input id="fb-comments-no" name="aiows_plugin_global_options[aiows_no_of_comments_to_display]" type="number" size="8" style="width:8%" placeholder="10" value="<?php if (isset($this->options_global['aiows_no_of_comments_to_display'])) { echo $this->options_global['aiows_no_of_comments_to_display']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Set the number of comments you want to display. Default is 10."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_fb_comment_sorting_setting() {

    if(!isset($this->options_global['aiows_fb_comment_sorting'])){
        $this->options_global['aiows_fb_comment_sorting'] = 'Social Ranking';
    }

    $items = array("Social Ranking", "Time", "Reverse Time");
echo "<select id='fb-comments-sort' name='aiows_plugin_global_options[aiows_fb_comment_sorting]'>";
foreach($items as $item) {
    $selected = ($this->options_global['aiows_fb_comment_sorting'] == $item) ? 'selected="selected"' : '';
    echo "<option value='$item' $selected>$item</option>";
}
echo "</select>";

    ?>&nbsp;&nbsp;<span class="tooltip" title="Select the facebook comment sorting method from here."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_fb_comment_language_setting() {
    if(empty($this->options_global['aiows_fb_comment_language'])){
        $this->options_global['aiows_fb_comment_language'] = 'en_US';
    }
    ?>
    <input id="fb-comments-lang" name="aiows_plugin_global_options[aiows_fb_comment_language]" type="text" size="10" style="width:10%" placeholder="en_US" value="<?php if (isset($this->options_global['aiows_fb_comment_language'])) { echo $this->options_global['aiows_fb_comment_language']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Select in which language you want to load facebook sdk."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_fb_comments_theme_setting() {
    if(!isset($this->options_global['aiows_fb_comments_theme'])){
        $this->options_global['aiows_fb_comments_theme'] = 'Light';
    }

    $items = array("Light", "Dark");
echo "<select id='fb-comments-theme' name='aiows_plugin_global_options[aiows_fb_comments_theme]'>";
foreach($items as $item) {
    $selected = ($this->options_global['aiows_fb_comments_theme'] == $item) ? 'selected="selected"' : '';
    echo "<option value='$item' $selected>$item</option>";
}
echo "</select>";

    ?>&nbsp;&nbsp;<span class="tooltip" title="Select the theme of facebook comments box from here."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_fb_comment_msg_setting() {
    if(empty($this->options_global['aiows_fb_comment_msg'])){
        $this->options_global['aiows_fb_comment_msg'] = 'Leave a Reply';
    }
    ?>
    <input id="fb-comments-msg" name="aiows_plugin_global_options[aiows_fb_comment_msg]" type="text" size="30" style="width:30%" placeholder="Leave a Reply" value="<?php if (isset($this->options_global['aiows_fb_comment_msg'])) { echo $this->options_global['aiows_fb_comment_msg']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Set a custom text which you want to display before fb comment box. You can set custom style for this using '.fb-comments-msg' class."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

/* ==================================================================

                               Minify

===================================================================== */

public function aiows_enable_html_minify_cb_display() {
    ?>
    <label class="switch">
    <input id="ehtml" name="aiows_plugin_global_options[aiows_enable_html_minify_cb]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_html_minify_cb'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to minify html code of your website."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_css_minify_cb_display() {
    ?>
    <label class="switch">
    <input id="ecss" name="aiows_plugin_global_options[aiows_enable_css_minify_cb]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_css_minify_cb'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you also want to minify css code of your website."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_js_minify_cb_display() {
    ?>
    <label class="switch">
    <input id="ejs" name="aiows_plugin_global_options[aiows_enable_js_minify_cb]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_js_minify_cb'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you also want to minify js code of your website."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_minify_allow_override_cb_display() {
    ?>
    <label class="switch">
    <input id="rovr" name="aiows_plugin_global_options[aiows_minify_allow_override_cb]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_minify_allow_override_cb'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to allow minification override using <!--aiows-html-compression no compression--> this tag."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_minify_allow_raw_tag_cb_display() {
    ?>
    <label class="switch">
    <input id="rraw" name="aiows_plugin_global_options[aiows_minify_allow_raw_tag_cb]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_minify_allow_raw_tag_cb'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable minification for raw tags i.e.<pre></pre> etc."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_minify_liu_cb_display() {
    ?>
    <label class="switch">
    <input id="mliu" name="aiows_plugin_global_options[aiows_enable_minify_liu_cb]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_minify_liu_cb'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable minification for logged in users also."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

/* ==================================================================

                              dashboard

===================================================================== */

public function aiows_custom_admin_footer_text() {
    ?>
    <input id="adminf" name="aiows_plugin_global_options[aiows_custom_admin_footer_text]" type="text" size="75" style="width:75%" placeholder="Thank you for creating with WordPress." value="<?php if (isset($this->options_global['aiows_custom_admin_footer_text'])) { echo $this->options_global['aiows_custom_admin_footer_text']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Enter custom left admin footer text here (HTML tags supported). Use blankspace to remove footer text."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_custom_right_footer_text() {
    ?>
    <input id="adminrf" name="aiows_plugin_global_options[aiows_custom_right_footer_text]" type="text" size="55" style="width:55%" placeholder="Version <?php printf( get_bloginfo ( 'version' ) ); ?>" value="<?php if (isset($this->options_global['aiows_custom_right_footer_text'])) { echo $this->options_global['aiows_custom_right_footer_text']; } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="Enter custom right admin footer text here (HTML tags supported). Use blankspace to remove footer text."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_change_pg_perma_display() { ?>
    <code><?php echo get_site_url(); ?></code><input id="page-perma" name="aiows_plugin_global_options[aiows_change_pg_perma]" type="text" size="40" style="width:40%" placeholder="/%pagename%.html" value="<?php if (isset($this->options_global['aiows_change_pg_perma'])) { echo $this->options_global['aiows_change_pg_perma']; } ?>" />
&nbsp;&nbsp;<span class="tooltip" title="Enter custom page permalink here. Use trailing slash only if it has been set in the permalink structure."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_admin_bar_all_checkbox_setting() {

    if(!isset($this->options_global['aiows_disable_admin_bar_all_checkbox'])){
        $this->options_global['aiows_disable_admin_bar_all_checkbox'] = 'Not Disable';
    }

$items = array("Not Disable", "For All Users", "For All Users Except Administrator");
foreach($items as $item) {
    $checked = ($this->options_global['aiows_disable_admin_bar_all_checkbox'] == $item) ? ' checked="checked" ' : '';
    echo "<label><input ".$checked." value='$item' name='aiows_plugin_global_options[aiows_disable_admin_bar_all_checkbox]' type='radio' /> $item</label>&nbsp;&nbsp;";
}
    ?><!--span class="tooltip" title="Enable this if you want to add a warning to avoid accidental publishing."><span title="" class="dashicons dashicons-editor-help"></span></span-->
    <?php
}

public function aiows_disable_admin_logo_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="wplogo" name="aiows_plugin_global_options[aiows_disable_admin_logo_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_admin_logo_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove wp logo from admin bar."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}


public function aiows_disable_file_editor_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="tp-editor" name="aiows_plugin_global_options[aiows_disable_file_editor_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_file_editor_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable file editor."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_help_tab_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="help-tab" name="aiows_plugin_global_options[aiows_disable_help_tab_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_help_tab_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable help tab from dashboard."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_screen_tab_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="screen-tab" name="aiows_plugin_global_options[aiows_disable_screen_tab_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_screen_tab_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable screen options tab from dashboard."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_wp_link_manager_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="link-manager" name="aiows_plugin_global_options[aiows_enable_wp_link_manager_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_wp_link_manager_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable WordPress default link manager which was disabled by default in WordPress 3.5."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_single_column_dashboard_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="scolumn" name="aiows_plugin_global_options[aiows_enable_single_column_dashboard_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_single_column_dashboard_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable WordPress single column dashboard."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_emoji_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="wpemoji" name="aiows_plugin_global_options[aiows_disable_emoji_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_emoji_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable wordpress default emojis. It will enable svg emojis."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_set_auto_alt_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="autop" name="aiows_plugin_global_options[aiows_set_auto_alt_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_set_auto_alt_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to set alt attribute for images automatically at the time of post publication."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_self_ping_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="self-ping" name="aiows_plugin_global_options[aiows_disable_self_ping_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_self_ping_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable self pingbacks and trackbacks."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_disable_wp_search_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="wp-search" name="aiows_plugin_global_options[aiows_disable_wp_search_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_disable_wp_search_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to disable from search result feature (it sends 404)."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_replace_howdy_welcome_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="howdy" name="aiows_plugin_global_options[aiows_replace_howdy_welcome_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_replace_howdy_welcome_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to replace 'Howdy with 'Welcome' in admin bar. Keep this option enabled, if you want to set custom text in next option."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_custom_welcome_text_display() {
    ?> <input id="custom-howdy" name="aiows_plugin_global_options[aiows_custom_welcome_text]" type="text" size="18" style="width:18%;" placeholder="Welcome," value="<?php if (isset($this->options_global['aiows_custom_welcome_text'])) { echo $this->options_global['aiows_custom_welcome_text']; } ?>" />
&nbsp;&nbsp;<span class="tooltip" title="Replace howdy with custom text from here."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_shortcodes_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="shortcode-everywhere" name="aiows_plugin_global_options[aiows_enable_shortcodes_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_shortcodes_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to enable shortcodes everywhere of wp dashboard."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

 public function aiows_remove_page_title_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="wp-title" name="aiows_plugin_global_options[aiows_remove_page_title_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_remove_page_title_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you not want to print 'WordPress' text in admin area title."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_sanitize_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="sanitize" name="aiows_plugin_global_options[aiows_enable_sanitize_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_sanitize_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Removes symbols, spaces, latin and other languages characters from uploaded files and gives them 'permalink' structure (clean characters, only lowercase and dahes)."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_enable_plugin_last_update_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="plug-last" name="aiows_plugin_global_options[aiows_enable_plugin_last_update_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_enable_plugin_last_update_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to add a option to show plugin's last update details."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

public function aiows_remove_orphan_shortcodes_checkbox_setting() {
    ?>
    <label class="switch">
    <input id="orphan-sc" name="aiows_plugin_global_options[aiows_remove_orphan_shortcodes_checkbox]" type="checkbox" value="1"<?php checked( 1 == isset($this->options_global['aiows_remove_orphan_shortcodes_checkbox'] )); ?> />
    <div class="slider round"></div></label>&nbsp;&nbsp;<span class="tooltip" title="Enable this if you want to remove orphan shortcodes from wordpress site."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}
}
}

?>
