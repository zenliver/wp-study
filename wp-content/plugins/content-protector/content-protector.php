<?php
/*
Plugin Name: Content Protector
Text Domain: content-protector
Plugin URI: http://wordpress.org/plugins/content-protector/
Description: Plugin to password-protect portions of a Page or Post.
Author: K. Tough
Version: 2.11
Author URI: http://wordpress.org/plugins/content-protector/
*/
if ( !class_exists( "contentProtectorPlugin" ) ) {

    define( "CONTENT_PROTECTOR_VERSION", "2.11" );
    define( "CONTENT_PROTECTOR_SLUG", "content-protector" );
    define( "CONTENT_PROTECTOR_HANDLE", "content_protector" );
    define( "CONTENT_PROTECTOR_COOKIE_ID", CONTENT_PROTECTOR_HANDLE . "_" );
    define( "CONTENT_PROTECTOR_PLUGIN_URL", plugins_url() . '/' . CONTENT_PROTECTOR_SLUG );
    define( "CONTENT_PROTECTOR_SHORTCODE", CONTENT_PROTECTOR_HANDLE );
    define( "CONTENT_PROTECTOR_CAPTCHA_KEYWORD", "CAPTCHA" );
    // Default form field settings
    define( "CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS", __( "This content is protected. Please enter the password to access it.", "content-protector" ) );
    define( "CONTENT_PROTECTOR_DEFAULT_AJAX_LOADING_MESSAGE", __( "Checking Password...", "content-protector" ) );
    define( "CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE", __( "Incorrect password. Try again.", "content-protector" ) );
    define( "CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE", _x( "Authorized!", "Message when the correct password is entered", "content-protector" ) );
    define( "CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL", _x( "Submit", "Access form submit label", "content-protector" ) );
    define( "CONTENT_PROTECTOR_DEFAULT_ENCRYPTION_ALGORITHM", "CRYPT_STD_DES" );
    define( "CONTENT_PROTECTOR_DEFAULT_SHARE_AUTH_DURATION", "3600" ); // One hour (in seconds)
    define( "CONTENT_PROTECTOR_DEFAULT_FONT_SIZE_OPTION", "0" );  // Make sure default size used is from the stylesheet
    define( "CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT", "400" );  // normal
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_INSTRUCTIONS", __( "Prove you are human.  Please enter the code below to access the content.", "content-protector" ) );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_WIDTH", "120" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_HEIGHT", "40" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_CHARS", "23456789bcdfghjkmnpqrstvwxyz" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_HEIGHT_PCT", "0.75" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_LENGTH", "6" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_FONT", dirname( __FILE__ ) . '/res/monofont.ttf' );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_BACKGROUND_COLOR", "#FFFFFF" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_COLOR", "#142864" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_NOISE_COLOR", "#6478B4" );
    define( "CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_TYPE", "password" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_TYPE", "text" );
    define( "CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_LENGTH", "8" );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_LENGTH", "8" );
    define( "CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_PLACEHOLDER", _x( "Enter Password", "password field placeholder", "content-protector" ) );
    define( "CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_PLACEHOLDER", _x( "Enter CAPTCHA", "CAPTCHA field placeholder", "content-protector" ) );
    // Required for styling JQuery UI plugins
    define( "CONTENT_PROTECTOR_JQUERY_UI_CSS", CONTENT_PROTECTOR_PLUGIN_URL . "/css/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" );
    define( "CONTENT_PROTECTOR_JQUERY_UI_THEME_CSS", CONTENT_PROTECTOR_PLUGIN_URL . "/css/jqueryui/1.11.4/themes/smoothness/theme.css" );
    define( "CONTENT_PROTECTOR_JQUERY_UI_TIMEPICKER_JS", CONTENT_PROTECTOR_PLUGIN_URL . "/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js" );
    define( "CONTENT_PROTECTOR_JQUERY_UI_TIMEPICKER_CSS", CONTENT_PROTECTOR_PLUGIN_URL . "/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.css" );
    define( "CONTENT_PROTECTOR_CSS_DASHICONS", CONTENT_PROTECTOR_PLUGIN_URL . "/css/ca-aliencyborg-dashicons/style.css" );


    /**
     * Class contentProtectorPlugin
     *
     * Class that contains all of the functions required to run the plugin.  This cuts down on
     * the possibility of name collisions with other plugins.
     */
    class contentProtectorPlugin
    {
        private $forms_ajax;
        private $add_dep_js_wp_mediaelement;
        private $add_dep_js_wp_playlist;
        private $add_dep_js_froogaloop;
        private $default_content_filters = array('wptexturize' => "1",
            'convert_smilies' => "1",
            'convert_chars' => "1",
            'wpautop' => "1",
            'prepend_attachment' => "1",
            'do_shortcode' => "1" );

        // If you modify $default_options, don't forget to modify the same array in uninstall.php.
        private $default_options = array( 'form_instructions' => CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS,
            'form_instructions_font_size' => CONTENT_PROTECTOR_DEFAULT_FONT_SIZE_OPTION,
            'form_instructions_font_weight' => CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT,
            'form_instructions_color' => "",
            'ajax_loading_message' => CONTENT_PROTECTOR_DEFAULT_AJAX_LOADING_MESSAGE,
            'ajax_loading_message_font_weight' => CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT,
            'ajax_loading_message_font_style' => "",
            'ajax_loading_message_color' => "",
            'success_message_display' => "",
            'success_message' => CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE,
            'success_message_font_size' => CONTENT_PROTECTOR_DEFAULT_FONT_SIZE_OPTION,
            'success_message_font_weight' => CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT,
            'success_message_color' => "",
            'error_message' => CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE,
            'error_message_font_size' => CONTENT_PROTECTOR_DEFAULT_FONT_SIZE_OPTION,
            'error_message_font_weight' => CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT,
            'error_message_color' => "",
            'form_submit_label' => CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL,
            'form_submit_label_color' => "",
            'form_submit_button_color' => "",
            'captcha_instructions' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_INSTRUCTIONS,
            'captcha_instructions_display' => "1",
            'captcha_width' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_WIDTH,
            'captcha_height' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_HEIGHT,
            'captcha_text_chars' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_CHARS,
            'captcha_text_length' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_LENGTH,
            'captcha_text_height' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_HEIGHT_PCT,
            'captcha_text_angle_variance' => "0",
            'captcha_background_color' => "",
            'captcha_text_color' => "",
            'captcha_noise_color' => "",
            'border_style' => "",
            'border_color' => "",
            'border_radius' => "",
            'border_width' => "",
            'padding' => "",
            'background_color' => "",
            'form_css' => "",
            'encryption_algorithm' => CONTENT_PROTECTOR_DEFAULT_ENCRYPTION_ALGORITHM,
            'content_filters' => array(),
            'other_content_filters' => "",
            'share_auth' => array(),
            'share_auth_duration' => CONTENT_PROTECTOR_DEFAULT_SHARE_AUTH_DURATION,
            'store_encrypted_passwords' => "1",
            'delete_options_on_uninstall' => "",
            'password_field_type' => CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_TYPE,
            'captcha_field_type' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_TYPE,
            'captcha_case_insensitive' => "0",
            'password_field_placeholder' => CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_PLACEHOLDER,
            'captcha_field_placeholder' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_PLACEHOLDER,
            'password_field_length' => CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_LENGTH,
            'captcha_field_length' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_LENGTH );

        function contentProtectorPlugin()
        {
            //constructor
            $this->forms_ajax = array();
            $this->add_dep_js_wp_mediaelement = false;
            $this->add_dep_js_wp_playlist = false;
            $this->add_dep_js_froogaloop = false;
            $this->default_options['content_filters'] = $this->default_content_filters;

        }

        // Credit: http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
        function __hex2rgb( $hex )
        {
            $hex = str_replace( "#", "", $hex );

            if ( strlen( $hex ) == 3 ) {
                $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
                $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
                $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
            } elseif ( strlen( $hex ) == 6 ) {
                $r = hexdec( substr( $hex, 0, 2 ) );
                $g = hexdec( substr( $hex, 2, 2 ) );
                $b = hexdec( substr( $hex, 4, 2 ) );
            } else {  // Set to black
                $r = 0;
                $g = 0;
                $b = 0;
            }
            $rgb = array( $r, $g, $b );
            //return implode( ", ",  $rgb ); // returns the rgb values separated by commas
            return $rgb; // returns an array with the rgb values
        }

        // Inspired by http://www.white-hat-web-design.co.uk/blog/php-captcha-security-images/
        function __generateCaptchaDataUri( $password )
        {
            $width = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_width', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_WIDTH );
            $height = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_height', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_HEIGHT );
            $text_height = get_option( CONTENT_PROTECTOR_HANDLE . '"captcha_text_height', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_HEIGHT_PCT );
            $angle_variance = get_option( CONTENT_PROTECTOR_HANDLE . 'captcha_text_angle_variance', "0" );

            $b_color = $this->__hex2rgb( get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_background_color', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_BACKGROUND_COLOR ) );
            $t_color = $this->__hex2rgb( get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_text_color', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_COLOR ) );
            $n_color = $this->__hex2rgb( get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_noise_color', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_NOISE_COLOR ) );

            /* Font size will be {$text_height}% of the image height */
            $font_size = $height * $text_height;

            /* Image text angle will be a random integer in the range +/-{$angle_variance} degrees */
            $angle = mt_rand( ( - 1 * $angle_variance ), $angle_variance );

            $image = @imagecreate( $width, $height ) or die( 'Cannot initialize new GD image stream' );

            /* Set the colours */
            $background_color = imagecolorallocate( $image, $b_color[ 0 ], $b_color[ 1 ], $b_color[ 2 ] );
            $text_color = imagecolorallocate( $image, $t_color[ 0 ], $t_color[ 1 ], $t_color[ 2 ] );
            $noise_color = imagecolorallocate( $image, $n_color[ 0 ], $n_color[ 1 ], $n_color[ 2 ] );

            /* Generate random dots in background */
            for ( $i = 0; $i < ( $width * $height ) / 3; $i ++ ) {
                imagefilledellipse( $image, mt_rand( 0, $width ), mt_rand( 0, $height ), 1, 1, $noise_color );
            }

            /* Generate random lines in background */
            for ( $i = 0; $i < ( $width * $height ) / 150; $i ++ ) {
                imageline( $image, mt_rand( 0, $width ), mt_rand( 0, $height ), mt_rand( 0, $width ), mt_rand( 0, $height ), $noise_color );
            }

            /* Create textbox and add text */
            $textbox = imagettfbbox( $font_size, $angle, CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_FONT, $password ) or die( 'Error in imagettfbbox function' );
            $x = ( $width - $textbox[ 4 ] ) / 2;
            $y = ( $height - $textbox[ 5 ] ) / 2;
            imagettftext( $image, $font_size, $angle, $x, $y, $text_color, CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_FONT, $password ) or die( 'Error in imagettftext function' );

            /* Convert to base64 */
            ob_start(); //Start output buffer.
            imagejpeg( $image ); //This will normally output the image, but because of ob_start(), it won't.
            $contents = ob_get_contents(); //Instead, output above is saved to $contents
            ob_end_clean(); //End the output buffer.

            return "data:image/jpeg;base64," . base64_encode( $contents );
        }

        /**
         * Gets the colors from the active Theme's stylesheet (style.css)
         *
         * @return array    Array of colors in hexadecimal notation
         */
        function __getThemeColors()
        {
            $colors = array();
            $stylesheet = file_get_contents( get_stylesheet_directory() . "/style.css" );
            preg_match_all( "/\#[a-fA-F0-9]{3,6}/", $stylesheet, $matches, PREG_SET_ORDER );
            foreach ( $matches as $m ) $colors[] = $m[ 0 ];
            sort( $colors );
            return array_unique( $colors );
        }

        // Inspired by http://ca1.php.net/manual/en/function.timezone-identifiers-list.php#79284
        function __generateTimezoneSelectOptions( $default_tz )
        {
            $timezone_identifiers = timezone_identifiers_list();
            sort( $timezone_identifiers );
            $current_continent = "";
            $current_tz = date_default_timezone_get();
            $options_list = "";

            foreach ( $timezone_identifiers as $timezone_identifier ) {
                list( $continent, ) = explode( "/", $timezone_identifier, 2 );
                if ( in_array( $continent, array( "Africa", "America", "Antarctica", "Arctic", "Asia", "Atlantic", "Australia", "Europe", "Indian", "Pacific" ) ) ) {
                    list( , $city ) = explode( "/", $timezone_identifier, 2 );
                    if ( strlen( $current_continent ) === 0 ) {
                        $options_list .= "<optgroup label=\"" . $continent . "\">"; // Start first continent optgroup
                    } elseif ( $current_continent != $continent ) {
                        $options_list .= "</optgroup><optgroup label=\"" . $continent . "\">"; // End current continent optgroup and start new continent optgroup
                    }
                    date_default_timezone_set( $timezone_identifier );
                    $options_list .= "<option" . ( ( $timezone_identifier == $default_tz ) ? " selected=\"selected\"" : "" )
                        . " value=\"" . $timezone_identifier . "\">UTC " . date( "P" ) . " - " . str_replace( "_", " ", $city ) . "</option>"; //Timezone
                }
                $current_continent = $continent;
            }
            $options_list .= "</optgroup>"; // End last continent optgroup

            date_default_timezone_set( $current_tz );
            return $options_list;
        }

        /**
         * Generate a randomized salt for use in contentProtectorPlugin::__hashPassword()
         *
         * @param int $length Length of the salt requested
         * @return string       The salt
         */
        function __generateRandomSalt( $length = 2 )
        {
            $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./";
            $salt = "";
            for ( $i = 0; $i < $length; $i ++ )
                $salt .= substr( str_shuffle( $valid_chars ), mt_rand( 0, strlen( $valid_chars ) - 1 ), 1 );
            return $salt;
        }

        /**
         * Generate a randomized CAPTCHA code
         *
         * @param int $length Length of the code requested
         * @return string       The code
         */
        function __generateCaptchaCode( $length = 6 )
        {
            $valid_chars = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_text_chars', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_CHARS );
            $code = "";
            for ( $i = 0; $i < $length; $i ++ )
                $code .= substr( str_shuffle( $valid_chars ), mt_rand( 0, strlen( $valid_chars ) - 1 ), 1 );
            return $code;
        }

        /**
         * Creates a hash of the password from the [content_protector] shortcode, using the encryption
         * algorithm set in the "content_protector_encryption_algorithm" option
         *
         * @param string $pw Password
         * @return string       The hashed password
         */
        function __hashPassword( $pw = "" )
        {
            $encryption_algorithm = get_option( CONTENT_PROTECTOR_HANDLE . 'encryption_algorithm', CONTENT_PROTECTOR_DEFAULT_ENCRYPTION_ALGORITHM );
            $salt = "";

            $store_encrypted_passwords = ( ( "1" == get_option( CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords', "1" ) ) ? true : false );
            if ( $store_encrypted_passwords ) {
                if ( false === ( $password_hashes = get_transient( 'content_protector_password_hashes' ) ) ) {
                    // It wasn't there, so regenerate the data and save the transient
                    $password_hashes = array();
                }
                $password_hashes_idx = $pw . "|" . $encryption_algorithm;
                if ( isset( $password_hashes[ $password_hashes_idx ] ) )
                    return $password_hashes[ $password_hashes_idx ];
            }
            switch ( $encryption_algorithm ) {
                case "CRYPT_STD_DES" :
                    $salt = $this->__generateRandomSalt( 2 );
                    break;
                case "CRYPT_EXT_DES" :
                    $salt = '_' . $this->__generateRandomSalt( 8 );
                    break;
                case "CRYPT_MD5" :
                    $salt = '$1$' . $this->__generateRandomSalt( 8 ) . '$';
                    break;
                case "CRYPT_BLOWFISH" :
                    $prefix = ( version_compare( PHP_VERSION, '5.3.7', '>=' ) ? '$2y$' : '$2a$' );
                    $cost = sprintf( "%02d", mt_rand( 12, 15 ) );
                    $salt = $prefix . $cost . '$' . $this->__generateRandomSalt( 22 ) . '$';
                    break;
                case "CRYPT_SHA256" :
                    $cost = mt_rand( 5000, 20000 );
                    $salt = '$5$rounds=' . $cost . '$' . $this->__generateRandomSalt( 16 ) . '$';
                    break;
                case "CRYPT_SHA512" :
                    $cost = mt_rand( 5000, 20000 );
                    $salt = '$6$rounds=' . $cost . '$' . $this->__generateRandomSalt( 16 ) . '$';
                    break;
                default :
                    $salt = "";
            }
            $password_hash = crypt( $pw, $salt );
            if ( $store_encrypted_passwords ) {
                $password_hashes[ $password_hashes_idx ] = $password_hash;
                set_transient( 'content_protector_password_hashes', $password_hashes, 1 * HOUR_IN_SECONDS );
            }
            return $password_hash;
        }

        /**
         * Creates a cookie on the user's computer so they won't necessarily need to re-enter the password on every visit.
         */
        function setCookie()
        {
            global $post;

            if ( isset( $post ) )
                $the_post_id = $post->ID;
            elseif ( isset( $_POST[ 'post_id' ] ) )
                $the_post_id = $_POST[ 'post_id' ];
            else
                return;

            $is_captcha = ( ( isset( $_POST[ 'content-protector-captcha' ] ) ) && ( (int) $_POST[ 'content-protector-captcha' ] == 1 ) );

            $post_permalink = get_permalink( $the_post_id );
            $password_field = ( isset( $_POST[ 'content-protector-password' ] ) ? $_POST[ 'content-protector-password' ] : "" );
            if ( $is_captcha && ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', "0" ) ) ) $password_field = strtoupper( $password_field );


            if ( ( isset( $_POST[ 'content-protector-submit' ] ) )
                && ( isset( $_POST[ 'content-protector-expires' ] ) )
                && ( crypt( $password_field, $_POST[ 'content-protector-token' ] ) == $_POST[ 'content-protector-token' ] )
            ) {
                if ( !is_int( $_POST[ 'content-protector-expires' ] ) )
                    $expires = strtotime( $_POST[ 'content-protector-expires' ] );
                elseif ( (int) $_POST[ 'content-protector-expires' ] <> 0 )
                    $expires = time() + (int) $_POST[ 'content-protector-expires' ];
                else
                    $expires = 0;

                if ( $is_captcha ) {
                    $cookie_name = CONTENT_PROTECTOR_COOKIE_ID . md5( $_POST[ 'content-protector-ident' ] . $post_permalink . "_captcha" );
                    $cookie_val = md5( $_POST[ 'content-protector-expires' ] . $_POST[ 'content-protector-ident' ] . $post_permalink );
                    setcookie( $cookie_name, $cookie_val, $expires, COOKIEPATH, COOKIE_DOMAIN );
                } else {
                    $cookie_name = CONTENT_PROTECTOR_COOKIE_ID . md5( $_POST[ 'content-protector-ident' ] . $post_permalink );
                    $cookie_val = md5( $password_field . $_POST[ 'content-protector-expires' ] . $_POST[ 'content-protector-ident' ] . $post_permalink );
                    setcookie( $cookie_name, $cookie_val, $expires, COOKIEPATH, COOKIE_DOMAIN );
                    $share_auth = get_option( CONTENT_PROTECTOR_HANDLE . '_share_auth', array() );
                    if ( !empty( $share_auth ) ) {
                        $share_auth_cookie_name = CONTENT_PROTECTOR_COOKIE_ID . "share_auth";
                        if ( ( isset( $share_auth[ 'same_identifier' ] ) ) && ( $share_auth[ 'same_identifier' ] == "1" ) )
                            $share_auth_cookie_name .= "_" . md5( $_POST[ 'content-protector-ident' ] );
                        if ( ( isset( $share_auth[ 'same_page' ] ) ) && ( $share_auth[ 'same_page' ] == "1" ) )
                            $share_auth_cookie_name .= "_" . md5( $post_permalink );
                        $share_auth_cookie_expires = time() + get_option( CONTENT_PROTECTOR_HANDLE . '_share_auth_duration', CONTENT_PROTECTOR_DEFAULT_SHARE_AUTH_DURATION );
                        setcookie( $share_auth_cookie_name, md5( $password_field ), $share_auth_cookie_expires, COOKIEPATH, COOKIE_DOMAIN );
                    }
                }

            }
        }

        /**
         * Processes the [content-protector] shortcode.
         *
         * @param array $atts
         * @param null  $content
         * @return string
         */
        function processShortcode( $atts, $content = null )
        {
            // Get the ID of the current post
            global $post;
            $post_id = $post->ID;
            $post_permalink = get_permalink( $post_id );

            extract( shortcode_atts( array( 'password' => "", 'cookie_expires' => "", 'identifier' => "", 'ajax' => "false" ), $atts ) );
            //error_log("Starting processShortcode. 'password' => " . $password . ", 'cookie_expires' => " . $cookie_expires .", 'identifier' => ". $identifier . ", 'is_ajax' => " . $is_ajax);
            // Make sure $is_ajax is boolean and set to true ONLY if the string $ajax equals 'true'
            $is_ajax = ( $ajax === "true" ? true : false );

            // If the password equals 'CAPTCHA' (upper- or lower-case), we'll set up a CAPTCHA
            $is_captcha = ( strtoupper( $password ) === CONTENT_PROTECTOR_CAPTCHA_KEYWORD ? true : false );
            $is_password = !$is_captcha;

            $can_manage_options = current_user_can( "manage_options" );
            $can_edit_post = current_user_can( "edit_post", $post_id );

            // Password empty; show error message to those who can edit this post, but fail silently
            // otherwise.  Never show protected content.
            if ( $is_password && ( strlen( trim( $password ) ) == 0 ) ) {
                if ( $can_edit_post )
                    return "<div class=\"content-protector-error\">"
                    . "<p class=\"heading\">" . __( "Error", "content-protector" ) . "</p>"
                    . "<p>" . sprintf( _x( "No password set in this %s shortcode!", "%s refers to the Content Protector shortcode", "content-protector" ), "<code>[" . CONTENT_PROTECTOR_SHORTCODE . "]</code>" ) . "</p>"
                    . "<p><em>" . __( "You can edit the post and add a password.", "content-protector" ) . "</em></p>"
                    . "</div>";
                else
                    return "";
            }

            // Password field isn't long enough for password; show error message to those who can do something about it,
            // but fail silently otherwise.  Never show protected content.
            if ( $is_password && ( strlen( trim( $password ) ) > get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_length', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_LENGTH ) ) ) {
                $remedy_msg = "";
                if ( $can_manage_options )
                    $remedy_msg .= "<li><em>" . sprintf( _x( 'You can change the %1$s setting under the %2$s tab.', '%1$s refers to the Password Field Length, %2$s refers to the Password/CAPTCHA Field tab under Settings -> Content Protector',  "content-protector" ), __( 'Password Field Length', "content-protector" ), __( "Password/CAPTCHA Field", "content-protector" ) ) . "</em></li>";
                if ( $can_edit_post )
                    $remedy_msg .= "<li><em>" . __( "You can edit the post and change the password.", "content-protector" ) . "</em></li>";
                if ( ( $can_manage_options ) || ( $can_edit_post ) )
                    return "<div class=\"content-protector-error\">"
                    . "<p class=\"heading\">" . __( "Error", "content-protector" ) . "</p>"
                    . "<p>" . sprintf( _x( 'Password set in this %1$s shortcode is longer than the maximum %2$s!', '%1$s refers to the Content Protector shortcode, %2$s refers to the Password Field Length', "content-protector" ), "<code>[" . CONTENT_PROTECTOR_SHORTCODE . "]</code>", __( 'Password Field Length', "content-protector" ) ) . "</p>"
                    . "<ul>" . $remedy_msg . "</ul>"
                    . "</div>";
                else
                    return "";
            }

            // CAPTCHA field isn't long enough for CAPTCHA; show error message to those who can do something about it,
            // but fail silently otherwise.  Never show protected content.
            if ( $is_captcha && ( get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_text_length', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_LENGTH ) > get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_length', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_LENGTH ) ) ) {
                if ( $can_manage_options )
                    return "<div class=\"content-protector-error\">"
                    . "<p class=\"heading\">" . __( "Error", "content-protector" ) . "</p>"
                    . "<p>" . sprintf( _x( '%1$s for the CAPTCHA is longer than the maximum %2$s!', '%1$s refers to the CAPTCHA Image Text Length, %2$s refers to the CAPTCHA Field Length', "content-protector" ), __( 'Image Text Length', "content-protector" ), __( 'CAPTCHA Field Length', "content-protector" ) ) . "</p>"
                    . "<p><em>" . sprintf( _x( 'You can change either the %1$s setting under the %2$s tab, or the %3$s setting under the %4$s tab.', '%1$s refers to the CAPTCHA Field Length, %2$s refers to the Password/CAPTCHA Field tab under Settings -> Content Protector, %3$s refers to the Image Field Length, %4$s refers to the CAPTCHA Image tab under Settings -> Content Protector', "content-protector" ), __( 'CAPTCHA Field Length', "content-protector" ), __( "Password/CAPTCHA Field", "content-protector" ), __( 'Image Text Length', "content-protector" ), __( 'CAPTCHA Image', "content-protector" ) ) . "</em></p>"
                    . "</div>";
                else
                    return "";
            }

            // $is_ajax is true but $identifier empty; show error message to those who can edit this post,
            // but fail silently otherwise.  Never show protected content.
            // (no AJAX allowed unless an identifier is set)
            if ( ( $is_ajax ) && ( strlen( trim( $identifier ) ) == 0 ) ) {
                if ( $can_edit_post )
                    return "<div class=\"content-protector-error\">"
                    . "<p class=\"heading\">" . __( "Error", "content-protector" ) . "</p>"
                    . "<p>" . sprintf( _x( "No AJAX allowed in this %s shortcode unless an identifier is set!", "%s refers to the Content Protector shortcode", "content-protector" ), "<code>[" . CONTENT_PROTECTOR_SHORTCODE . "]</code>" ) . "</p>"
                    . "<p><em>" . sprintf( _x( "You can edit the post and add an identifier to the shortcode using the %s attribute.", "%s refers to the identifier attribute in the Content Protector shortcode", "content-protector" ), "</em><code>identifier</code><em>" ) . "</em></p>"
                    . "</div>";
                else
                    return "";
            }

            // We need to differentiate between multiple instances of protected content on a single
            // Post/Page.  If $identifier is set, we'll use that; otherwise, we take a message digest of
            // the protected content.
            if ( strlen( trim( $identifier ) ) > 0 ) {
                $ident = md5( $identifier );
            } else {
                $ident = md5( $content );
            }

            // If not empty, add '-$identifier' to the DOM IDs in the form so designers/developers can refer to each
            // individual DOM node in their custom CSS.  If $id is empty or not set, use the MD5 digest of the protected content.
            if ( strlen( trim( $identifier ) ) > 0 ) {
                $identifier = "-" . $identifier;
            } else {
                $identifier = "-" . $ident;
            }

            $isAuthorized = false;
            $successMessage = "";
            $cookie_name = CONTENT_PROTECTOR_COOKIE_ID . md5( $ident . $post_permalink );
            $captcha_cookie_name = CONTENT_PROTECTOR_COOKIE_ID . md5( $ident . $post_permalink . "_captcha" );
            $share_auth = get_option( CONTENT_PROTECTOR_HANDLE . '_share_auth', array() );
            $share_auth_cookie_name = CONTENT_PROTECTOR_COOKIE_ID . "share_auth";
            if ( !empty( $share_auth ) ) {
                if ( ( isset( $share_auth[ 'same_identifier' ] ) ) && ( $share_auth[ 'same_identifier' ] == "1" ) )
                    $share_auth_cookie_name .= "_" . md5( $ident );
                if ( ( isset( $share_auth[ 'same_page' ] ) ) && ( $share_auth[ 'same_page' ] == "1" ) )
                    $share_auth_cookie_name .= "_" . md5( $post_permalink );
            }
            $password_field = ( isset( $_POST[ 'content-protector-password' ] ) ? $_POST[ 'content-protector-password' ] : "" );
            if ( $is_captcha && ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', "0" ) ) ) $password_field = strtoupper( $password_field );

            // Authorization by group cookie
            if ( ( !empty( $share_auth ) ) && ( isset( $_COOKIE[ $share_auth_cookie_name ] ) ) && ( $_COOKIE[ $share_auth_cookie_name ] == md5( $password ) ) )
                $isAuthorized = true;

            // ...or authorization by individual cookie
            elseif ( ( isset( $_COOKIE[ $cookie_name ] ) ) && ( $_COOKIE[ $cookie_name ] == md5( $password . $cookie_expires . $ident . $post_permalink ) ) )
                $isAuthorized = true;

            //  ...or authorization by CAPTCHA cookie
            elseif ( ( $is_captcha ) && ( isset( $_COOKIE[ $captcha_cookie_name ] ) ) && ( $_COOKIE[ $captcha_cookie_name ] == md5( $cookie_expires . $ident . $post_permalink ) ) )
                $isAuthorized = true;

            // ...or authorization by $_POST
            elseif ( ( ( isset( $_POST[ 'content-protector-password' ] ) ) && ( isset( $_POST[ 'content-protector-token' ] ) ) )
                && ( crypt( $password_field, $_POST[ 'content-protector-token' ] ) == $_POST[ 'content-protector-token' ] )
                && ( ( isset( $_POST[ 'content-protector-ident' ] ) ) && ( $_POST[ 'content-protector-ident' ] === $ident ) )
            ) {
                $isAuthorized = true;
                // We only want to see this on initial authorization, not whenever the cookie authorizes you
                $success_message_display = get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_display', "0" );
                if ( $success_message_display == "1" )
                    $successMessage = "<div id=\"content-protector-correct-password" . $identifier . "\" class=\"content-protector-correct-password\">" . get_option( CONTENT_PROTECTOR_HANDLE . '_success_message', CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE ) . "</div>";
                else
                    $successMessage = "";
            }
            $css_on_unlocked_content = ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content', "1" ) ? true : false );

            if ( $isAuthorized ) {
                //error_log(has_filter( "content_protector_unlocked_content", "" ) );
                return "<div id=\"content-protector" . $identifier . "\"" . ( $css_on_unlocked_content ? " class=\"content-protector-access-form\"" : "" ) . ">" . $successMessage . str_replace( ']]>', ']]&gt;', apply_filters( "content_protector_unlocked_content", $content ) ) . "</div>";
            } else {
                // Generate random CAPTCHA code/image if we're setting up a CAPTCHA
                //error_log( "Building form. ");
                if ( $is_captcha ) {
                    $password = $this->__generateCaptchaCode( get_option( CONTENT_PROTECTOR_HANDLE . "_captcha_text_length", CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_LENGTH ) );
                    $captcha_data_uri = $this->__generateCaptchaDataUri( $password );
                    $password_field_type = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_type', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_TYPE );
                    $password_field_length = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_length', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_LENGTH );
                    $placeholder = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_placeholder', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_PLACEHOLDER );
                    // If CAPTCHA can be case-insensitive, convert to upper-case (this is what we'll hash later for '-token')
                    $password = ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', "0" ) ? strtoupper( $password ) : $password );
                } else {
                    $captcha_data_uri = "";
                    $password_field_type = get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_type', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_TYPE );
                    $password_field_length = get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_length', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_LENGTH );
                    $placeholder = get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_placeholder', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_PLACEHOLDER );
                }
                $captcha_instr_mode = get_option( CONTENT_PROTECTOR_HANDLE . "_captcha_instructions_display", "1" );
                $incorrect_password_message = get_option( CONTENT_PROTECTOR_HANDLE . '_error_message', CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE );

                $form_instructions = str_replace( ']]>', ']]&gt;', apply_filters( "the_content", get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions', CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS ) ) );
                $captcha_instructions = str_replace( ']]>', ']]&gt;', apply_filters( "the_content", get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_instructions', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_INSTRUCTIONS ) ) );

                $form_submit_label = get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label', CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL );
                $password_hash = $this->__hashPassword( $password );
                if ( $is_ajax ) {
                    $nonce_time = time();
                    $ajax_security_nonce = wp_create_nonce(CONTENT_PROTECTOR_HANDLE . "_post-" . $post_id . "_identifier-" . $identifier . "_time-" . $nonce_time);
                    $this->forms_ajax[] = array(
                        "form_id" => "#content-protector-access-form" . $identifier,
                        "target" => "#content-protector" . $identifier,
                        "post_id" => $post_id,
                        "identifier" => $identifier,
                        "time" => $nonce_time,
                        "show_css_on_success" => $css_on_unlocked_content,
                        "ajax_security_nonce" => $ajax_security_nonce
                    );
                    if ( has_shortcode( $content, 'audio') || has_shortcode( $content, 'video') )
                        $this->add_dep_js_wp_mediaelement = true;
                    if ( has_shortcode( $content, 'playlist') )
                        $this->add_dep_js_wp_playlist = true;
                } else
                    $ajax_security_nonce = "";
                ob_start();
                include( "screens/access_form.php" );
                $the_form = ob_get_contents();
                ob_end_clean();

                return $the_form;
            }
        }

        function contentProtectorProcessFormAjax()
        {
            // First, make sure the AJAX request is coming from the site, if not,
            // we kill with extreme prejudice.
            check_ajax_referer( CONTENT_PROTECTOR_HANDLE . "_post-" . $_POST[ 'post_id' ] . "_identifier-" . $_POST[ 'identifier' ] . "_time-" . $_POST[ 'time' ], "ajax_security_nonce" );

            // No more explicit support for Contact Form 7 (but I think I fixed that anyway; use the "content_protector_ajax_support" action to load any extra files you need           
            do_action( "content_protector_ajax_support" );

            // Find the post
            $post = get_post( $_POST[ 'post_id' ] );
            $post_id = $_POST[ 'post_id' ];

            // Find all instances of [content_protector] in the post
            $regex_pattern = "\[(\[?)(" . CONTENT_PROTECTOR_SHORTCODE . ")(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\\2\])[^\[]*+)*+)\[\/\\2\])?)(\]?)";

            if ( preg_match_all( '/' . $regex_pattern . '/s', $post->post_content, $matches, PREG_SET_ORDER ) ) {
                foreach ( $matches as $match ) {
                    // Got one!  But is it the one we want? Let's check the attributes, which
                    // should be in $match[3].
                    // response output
                    $attributes = shortcode_parse_atts( $match[3] );
                    //error_log(print_r($attributes,true));
                    $is_ajax = ( ( ( isset( $attributes[ 'ajax' ] ) ) && ( $attributes[ 'ajax' ] === "true" ) ) ? true : false );

                    // If $ajax isn't explicitly set to 'true', keep looking at other shortcodes
                    if ( !$is_ajax )
                        continue;

                    $cookie_expires = ( ( isset( $attributes[ 'cookie_expires' ] ) ) ? $attributes[ 'cookie_expires' ] : "" );

                    // If the password equals 'CAPTCHA' (upper- or lower-case), we'll need to set up a new CAPTCHA if
                    // the entered code is incorrect.
                    $is_captcha = ( strtoupper( $attributes[ 'password' ] ) === CONTENT_PROTECTOR_CAPTCHA_KEYWORD ? true : false );

										// Also,  we check if entered passwords/CAPTCHAs can be case-insensitive
				            $password_field = ( isset( $_POST[ 'content-protector-password' ] ) ? $_POST[ 'content-protector-password' ] : "" );
				            if ( $is_captcha && ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', "0" ) ) ) $password_field = strtoupper( $password_field );

                    // We need to differentiate between multiple instances of protected content on a single
                    // Post/Page.  Since we ensured that $attributes['identifier'] is already set set, we'll use that.
                    $ident = md5( $attributes[ 'identifier' ] );

                    // Add '-$identifier' to the DOM IDs in the form so designers/developers can refer to each
                    // individual DOM node in their custom CSS.
                    $identifier = "-" . $attributes[ 'identifier' ];

                    if ( ( isset( $_POST[ 'content-protector-ident' ] ) ) && ( $_POST[ 'content-protector-ident' ] === $ident ) ) {
                        $response = array();
                        $response['show_css_on_success'] = ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content', "1" ) ? true : false );

                        if ( crypt( $password_field, $_POST[ 'content-protector-token' ] ) == $_POST[ 'content-protector-token' ] ) {
                            // Right instance, right password.  Let's roll!
                            if ( strlen( $cookie_expires ) > 0 )
                                $this->setCookie();
                            $success_message_display = get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_display', "0" );
                            if ( $success_message_display == "1" )
                                $successMessage = "<div id=\"content-protector-correct-password" . $identifier . "\" class=\"content-protector-correct-password\">" . get_option( CONTENT_PROTECTOR_HANDLE . '_success_message', CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE ) . "</div>";
                            else
                                $successMessage = "";
                                
                            // Set up the content filters for unlocked protected content
                            $this->setupContentFilters();
                            $ajax_content = $match[5];

                            if ( has_filter( "content_protector_unlocked_content", "do_shortcode" ) ) {
		                            // Here, we add support for some of Wordpress's built-in shortcodes.  We'll load the
		                            // necessary CSS/JS down in the footer.
		                            $embed_regex_pattern = "\[(\[?)(" . "embed" . ")(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\\2\])[^\[]*+)*+)\[\/\\2\])?)(\]?)";

		                            if ( preg_match_all( '/' . $embed_regex_pattern . '/s', $ajax_content, $embed_matches, PREG_SET_ORDER ) ) {
		                                foreach ( $embed_matches as $embed_match ) {
		                                    //error_log(print_r($embed_match,true));
		                                    $embed_attributes = shortcode_parse_atts( $embed_match[3] );
		                                    //error_log(print_r($embed_attributes,true));
		                                    $embed_code = wp_oembed_get( $embed_match[5], $embed_attributes );
		                                    //error_log( $embed_code );
		                                    $ajax_content = str_replace( $embed_match[0], $embed_code, $ajax_content );
		                                    //error_log( $ajax_content );
		                                }
		                            }

		                            $audio_regex_pattern = "\[(\[?)(" . "audio" . ")(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\\2\])[^\[]*+)*+)\[\/\\2\])?)(\]?)";
		                            if ( preg_match_all( '/' . $audio_regex_pattern . '/s', $ajax_content, $audio_matches, PREG_SET_ORDER ) ) {
		                                foreach ( $audio_matches as $audio_match ) {
		                                    //error_log(print_r($audio_match,true));
		                                    $audio_attributes = shortcode_parse_atts( $audio_match[3] );
		                                    // Normally, if no sources are set, the shortcode looks for any media attached to
		                                    // the post.  We can't set the post ID, nor can wp_audio_shortcode() figure
		                                    // it out because it's not being called from inside the Loop. So, we'll check
		                                    // for ourselves and add it manually if necessary. This should ensure full
		                                    // functionality of the shortcode.
		                                    $sources = wp_get_audio_extensions();
		                                    $sources[] = 'src';
		                                    $no_sources = false;
		                                    foreach ( $sources as $source ) {
		                                        if ( !isset( $audio_attributes[$source] ) ) {
		                                            $no_sources = true;
		                                            continue;
		                                        }
		                                    }
		                                    if ( $no_sources ) {
		                                        $audios = get_attached_media( 'audio', $post_id );
		                                        if ( !empty( $audios ) ) {
		                                            $audio = reset( $audios );
		                                            $audio_attributes['src'] = wp_get_attachment_url( $audio->ID );
		                                        }
		                                    }
		                                    $audio_code = wp_audio_shortcode( $audio_attributes, $audio_match[5] );
		                                    //error_log( $audio_code );
		                                    $ajax_content = str_replace( $audio_match[0], $audio_code, $ajax_content );
		                                    //error_log( $ajax_content );
		                                }
		                                $this->add_dep_js_wp_mediaelement = true;
		                            }

		                            $video_regex_pattern = "\[(\[?)(" . "video" . ")(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\\2\])[^\[]*+)*+)\[\/\\2\])?)(\]?)";
		                            if ( preg_match_all( '/' . $video_regex_pattern . '/s', $ajax_content, $video_matches, PREG_SET_ORDER ) ) {
		                                foreach ( $video_matches as $video_match ) {
		                                    //error_log(print_r($video_match,true));
		                                    $video_attributes = shortcode_parse_atts( $video_match[3] );
		                                    // Normally, if no sources are set, the shortcode looks for any media attached to
		                                    // the post.  We can't set the post ID, nor can wp_video_shortcode() figure
		                                    // it out because it's not being called from inside the Loop. So, we'll check
		                                    // for ourselves and add it manually if necessary. This should ensure full
		                                    // functionality of the shortcode.
		                                    $sources = wp_get_video_extensions();
		                                    $sources[] = 'src';
		                                    $no_sources = false;
		                                    foreach ( $sources as $source ) {
		                                        if ( !isset( $video_attributes[$source] ) ) {
		                                            $no_sources = true;
		                                            continue;
		                                        }
		                                    }
		                                    if ( $no_sources ) {
		                                        $videos = get_attached_media( 'video', $post_id );
		                                        if ( !empty( $videos ) ) {
		                                            $video = reset( $videos );
		                                            $video_attributes['src'] = wp_get_attachment_url( $video->ID );
		                                        }
		                                    }
		                                    if ( false !== strstr( $video_attributes['src'], "vimeo.com" ) )
		                                        $this->add_dep_js_froogaloop = true;
		                                    $video_code = wp_video_shortcode( $video_attributes, $video_match[5] );
		                                    //error_log( $video_code );
		                                    $ajax_content = str_replace( $video_match[0], $video_code, $ajax_content );
		                                    //error_log( $ajax_content );
		                                }
		                                $this->add_dep_js_wp_mediaelement = true;
		                            }

		                            $playlist_regex_pattern = "\[(\[?)(" . "playlist" . ")(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\\2\])[^\[]*+)*+)\[\/\\2\])?)(\]?)";
		                            if ( preg_match_all( '/' . $playlist_regex_pattern . '/s', $ajax_content, $playlist_matches, PREG_SET_ORDER ) ) {
		                                foreach ( $playlist_matches as $playlist_match ) {
		                                    //error_log(print_r($playlist_match,true));
		                                    $playlist_attributes = shortcode_parse_atts( $playlist_match[3] );
		                                    if ( !isset( $playlist_attributes['id'] ) )
		                                        $playlist_attributes['id'] = $post_id;
		                                    $playlist_code = wp_playlist_shortcode( $playlist_attributes, $playlist_match[5] );
		                                    //error_log( $playlist_code );
		                                    $ajax_content = str_replace( $playlist_match[0], $playlist_code, $ajax_content );
		                                    //error_log( $ajax_content );
		                                }
		                                $this->add_dep_js_wp_mediaelement = true;
		                                $this->add_dep_js_wp_playlist = true;
		                            }

		                            $gallery_regex_pattern = "\[(\[?)(" . "gallery" . ")(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\\2\])[^\[]*+)*+)\[\/\\2\])?)(\]?)";
		                            if ( preg_match_all( '/' . $gallery_regex_pattern . '/s', $ajax_content, $gallery_matches, PREG_SET_ORDER ) ) {
		                                foreach ( $gallery_matches as $gallery_match ) {
		                                    //error_log(print_r($gallery_match,true));
		                                    $gallery_attributes = shortcode_parse_atts( $gallery_match[3] );
		                                    if ( !isset( $gallery_attributes['id'] ) )
		                                        $gallery_attributes['id'] = $post_id;
		                                    $gallery_code = gallery_shortcode( $gallery_attributes, $gallery_match[5] );
		                                    //error_log( $gallery_code );
		                                    $ajax_content = str_replace( $gallery_match[0], $gallery_code, $ajax_content );
		                                    //error_log( $ajax_content );
		                                }
		                            }

		                            $caption_regex_pattern = "\[(\[?)(" . "caption" . ")(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\\2\])[^\[]*+)*+)\[\/\\2\])?)(\]?)";
		                            if ( preg_match_all( '/' . $caption_regex_pattern . '/s', $ajax_content, $caption_matches, PREG_SET_ORDER ) ) {
		                                foreach ( $caption_matches as $caption_match ) {
		                                    //error_log(print_r($caption_match,true));
		                                    $caption_attributes = shortcode_parse_atts( $caption_match[3] );
		                                    if ( !isset( $caption_attributes['id'] ) )
		                                        $caption_attributes['id'] = $post_id;
		                                    $caption_code = img_caption_shortcode( $caption_attributes, $caption_match[5] );
		                                    //error_log( $caption_code );
		                                    $ajax_content = str_replace( $caption_match[0], $caption_code, $ajax_content );
		                                    //error_log( $ajax_content );
		                                }
		                            }
                          	}
                            
                            $response['html'] = $successMessage . str_replace( ']]>', ']]&gt;', apply_filters( "content_protector_unlocked_content", $ajax_content ) );
                            $response['authorized'] = true;
                            $response['trigger_mediaelement'] = $this->add_dep_js_wp_mediaelement;
                            $response['trigger_playlist'] = $this->add_dep_js_wp_playlist;

                            // response output
                            header( "Content-Type: text/json" );
                            echo json_encode($response);
                            wp_die();
                        } else {
                            $is_ajax_processed = true;
                            // Generate random CAPTCHA code if we're setting up a CAPTCHA
                            if ( $is_captcha ) {
                                $password = $this->__generateCaptchaCode( get_option( CONTENT_PROTECTOR_HANDLE . "_captcha_text_length", CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_LENGTH ) );
                                $captcha_data_uri = $this->__generateCaptchaDataUri( $password );
                                $password_field_type = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_type', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_TYPE );
                                $placeholder = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_placeholder', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_PLACEHOLDER );
                      					$password = ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', "0" ) ? strtoupper( $password ) : $password );
                          } else {
                                $password = $attributes[ 'password' ];
                                $captcha_data_uri = "";
                                $password_field_type = get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_type', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_TYPE );
                                $placeholder = get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_placeholder', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_PLACEHOLDER );
                            }

                            $captcha_instr_mode = get_option( CONTENT_PROTECTOR_HANDLE . "_captcha_instructions_display", "1" );
                            $incorrect_password_message = get_option( CONTENT_PROTECTOR_HANDLE . '_error_message', CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE );

                            $form_instructions = str_replace( ']]>', ']]&gt;', apply_filters( "the_content", get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions', CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS ) ) );
                            $captcha_instructions = str_replace( ']]>', ']]&gt;', apply_filters( "the_content", get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_instructions', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_INSTRUCTIONS ) ) );

                            $form_submit_label = get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label', CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL );
                            $password_hash = $this->__hashPassword( $password );
                            if ( $is_ajax ) {
                                $nonce_time = time();
                                $css_on_unlocked_content = ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content', "1" ) ? true : false );
                                $ajax_security_nonce = wp_create_nonce(CONTENT_PROTECTOR_HANDLE . "_post-" . $post_id . "_identifier-" . $identifier . "_time-" . $nonce_time);
                            } else
                                $ajax_security_nonce = "";
                            ob_start();
                            include( "screens/access_form.php" );
                            $the_form = ob_get_contents();
                            ob_end_clean();

                            $response['html'] = $the_form;
                            $response['authorized'] = false;

                            // response output
                            header( "Content-Type: text/json" );
                            echo json_encode($response);
                            wp_die();
                        }
                    }
                }
            }
            $response = "<div id=\"content-protector-unknown-error\" class=\"content-protector-incorrect-password\">"
                /* translators: %1$s refers to the plugin name. */
                . sprintf( __( 'Something unusual has happened.  %1$s cannot find your shortcode in this Post.', 'content-protector' ), __( "Content Protector", 'content-protector' ) )
                . "</div>";

            // response output
            header( "Content-Type: text/plain" );
            echo $response;
            wp_die();
        }

        /**
         * Enqueues the CSS/JavaScript code necessary for the functionality of the [content-protector] shortcode.
         */
        function addHeaderCode()
        {
            wp_enqueue_style( CONTENT_PROTECTOR_SLUG . '_css', CONTENT_PROTECTOR_PLUGIN_URL . '/css/content-protector.css', CONTENT_PROTECTOR_VERSION );
            if ( !is_admin() ) { ?>
<!-- Content Protector plugin v. <?php echo CONTENT_PROTECTOR_VERSION; ?> CSS -->
<style type="text/css">
    div.content-protector-access-form {
    <?php if ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_padding' ) ) { ?> padding: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_padding' ); ?>px;  <?php } ?>
    <?php if ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_border_style' ) ) { ?> border-style: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_border_style' ); ?>;<?php } ?>
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_border_color' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_border_color' ) ) ) > 0 ) ) { ?> border-color: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_border_color' ); ?>;<?php } ?>
    <?php if ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_border_width' ) ) { ?> border-width: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_border_width' ); ?>px;<?php } ?>
    <?php if ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_border_radius' ) ) { ?> border-radius: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_border_radius' ); ?>px;<?php } ?>
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_background_color' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_background_color' ) ) ) > 0 ) ) { ?> background-color: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_background_color' ); ?>;<?php } ?>
}

    input.content-protector-form-submit {
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_color' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_color' ) ) ) > 0 ) ) { ?> color: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_color' ); ?>;<?php } ?>
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_button_color' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_button_color' ) ) ) > 0 ) ) { ?> background-color: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_button_color' ); ?>;<?php } ?>
}

    div.content-protector-correct-password {
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_color' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_color' ) ) ) > 0 ) ) { ?> color: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_color' ); ?>;<?php } ?>
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_font_size' ) ) && ( get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_font_size' ) > 0 ) ) { ?> font-size: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_font_size' ); ?>px;<?php } ?>
    <?php if ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_font_weight' ) ) { ?> font-weight: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_font_weight' ); ?>;<?php } ?>
}

    div.content-protector-incorrect-password {
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_color' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_color' ) ) ) > 0 ) ) { ?> color: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_color' ); ?>;<?php } ?>
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_font_size' ) ) && ( get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_font_size' ) > 0 ) ) { ?> font-size: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_font_size' ); ?>px;<?php } ?>
    <?php if ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_font_weight' ) ) { ?> font-weight: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_font_weight' ); ?>;<?php } ?>
}

    div.content-protector-ajaxLoading {
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_color' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_color' ) ) ) > 0 ) ) { ?> color: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_color' ); ?>;<?php } ?>
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_style' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_style' ) ) ) > 0 ) ) { ?> font-style: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_style' ); ?>;<?php } ?>
    <?php if ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_weight' ) ) { ?> font-weight: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_weight' ); ?>;<?php } ?>
}

    div.content-protector-form-instructions {
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_color' ) ) && ( strlen( trim( get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_color' ) ) ) > 0 ) ) { ?> color: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_color' ); ?>;<?php } ?>
    <?php if ( ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_size' ) ) && ( get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_size' ) > 0 ) ) { ?> font-size: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_size' ); ?>px;<?php } ?>
    <?php if ( false !== get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_weight' ) ) { ?> font-weight: <?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_weight' ); ?>;<?php } ?>
}
</style>
            <?php }
            $css = get_option(CONTENT_PROTECTOR_HANDLE . '_form_css', "");
            if ((!is_admin()) && (strlen(trim($css)) > 0)) { ?>
                <!-- Content Protector plugin v. <?php echo CONTENT_PROTECTOR_VERSION; ?> Additional CSS -->
                <style type="text/css">
                <?php echo $css; ?>
                </style>
            <?php }
        }

        function addFooterCode()
        {
            if ( count( $this->forms_ajax ) > 0 ) {
                $js_deps = array( 'jquery', 'jquery-form' );
                $mediaelement_is_enqueued = false;
                // Add MediaElement.js dependencies for any AJAX'd content that requires them.
                if ( $this->add_dep_js_wp_mediaelement ) {
                    $js_deps[] = 'wp-mediaelement';
                    wp_enqueue_style( 'wp-mediaelement' );
                    echo "\n<!--[if lt IE 9]><script>document.createElement('audio');</script><![endif]-->\n";
                    echo "<!--[if lt IE 9]><script>document.createElement('video');</script><![endif]-->\n";
                    $mediaelement_is_enqueued = true;
                }
                // Add Froogaloop dependencies for any AJAX'd content that requires Vimeo support.
                if ( $this->add_dep_js_froogaloop ) {
                    $js_deps[] = 'froogaloop';
                }
                // Add Playlist dependencies for any AJAX'd content that requires them.
                if ( $this->add_dep_js_wp_playlist ) {
                    $js_deps[] = 'wp-mediaelement';
                    $js_deps[] = 'wp-playlist';
                    wp_enqueue_style( 'wp-mediaelement' );
                    if ( $mediaelement_is_enqueued ) {
                        echo "\n<!--[if lt IE 9]><script>document.createElement('audio');</script><![endif]-->\n";
                        echo "<!--[if lt IE 9]><script>document.createElement('video');</script><![endif]-->\n";
                    }
                    wp_underscore_playlist_templates();
                }
                wp_enqueue_script( CONTENT_PROTECTOR_SLUG . '-ajax_js', CONTENT_PROTECTOR_PLUGIN_URL . '/js/content-protector-ajax.js', $js_deps, CONTENT_PROTECTOR_VERSION );
                // Set up local variables used in the AJAX JavaScript file
                wp_localize_script( CONTENT_PROTECTOR_SLUG . '-ajax_js', 'contentProtectorAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    "forms" => $this->forms_ajax,
                    'loading_label' => get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message', CONTENT_PROTECTOR_DEFAULT_AJAX_LOADING_MESSAGE ),
                    'loading_img' => CONTENT_PROTECTOR_PLUGIN_URL . '/img/wpspin.gif',
                    'error_heading' => __( "Error", "content-protector" ),
                    'error_desc' => __( "Something unexpected has happened along the way.  The specific details are below:", "content-protector" ) ) );
            }
        }

        /**
         * Adds back-facing Javascript for form fields on the Settings page.
         *
         */
        function addAdminHeaderCode()
        {
            wp_enqueue_style( CONTENT_PROTECTOR_SLUG . '-jquery-ui-css', CONTENT_PROTECTOR_JQUERY_UI_CSS, false, CONTENT_PROTECTOR_VERSION );
            wp_enqueue_style( CONTENT_PROTECTOR_SLUG . '-jquery-ui-theme-css', CONTENT_PROTECTOR_JQUERY_UI_THEME_CSS, false, CONTENT_PROTECTOR_VERSION );
            wp_enqueue_style( 'wp-color-picker' );

            $css_all_default = "/* " . __( "These styles will be applied to all Content Protector access forms.", "content-protector" ) . " */\n" .
                "form.content-protector-access-form {\n" .
                "\t/* " . __( "CSS styles for the entire form", "content-protector" ) . " */\n}\n" .
                "div.content-protector-form-instructions {\n" .
                "\t/* " . __( "CSS styles for the form instructions", "content-protector" ) . " */\n}\n" .
                "div.content-protector-correct-password {\n" .
                "\t/* " . __( "CSS styles for the message when the correct password is entered", "content-protector" ) . " */\n}\n" .
                "div.content-protector-incorrect-password {\n" .
                "\t/* " . __( "CSS styles for the error message for an incorrect password", "content-protector" ) . " */\n}\n" .
                "input.content-protector-form-submit {\n" .
                "\t/* " . __( "CSS styles for the Submit button", "content-protector" ) . " */\n}\n" .
                "input.content-protector-captcha-img {\n" .
                "\t/* " . __( "CSS styles for the CAPTCHA box", "content-protector" ) . " */\n}\n" .
                "div.content-protector-ajaxLoading {\n" .
                "\t/* " . __( "CSS styles for the AJAX loading message", "content-protector" ) . " */\n}\n";
            $css_ident_default = "/* " . __( "These styles will be applied to the Content Protector access form whose identifier is &quot;{id}&quot;.", "content-protector" ) . " */\n" .
                "#content-protector-access-form-{id} {\n" .
                "\t/* " . __( "CSS styles for the entire form", "content-protector" ) . " */\n}\n" .
                "#content-protector-form-instructions-{id} {\n" .
                "\t/* " . __( "CSS styles for the form instructions", "content-protector" ) . " */\n}\n" .
                "#content-protector-correct-password-{id} {\n" .
                "\t/* " . __( "CSS styles for the message when the correct password is entered", "content-protector" ) . " */\n}\n" .
                "#content-protector-incorrect-password-{id} {\n" .
                "\t/* " . __( "CSS styles for the error message for an incorrect password", "content-protector" ) . " */\n}\n" .
                "#content-protector-form-submit-{id} {\n" .
                "\t/* " . __( "CSS styles for the Submit button", "content-protector" ) . " */\n}\n" .
                "#content-protector-captcha-img-{id} {\n" .
                "\t/* " . __( "CSS styles for the CAPTCHA box", "content-protector" ) . " */\n}\n" .
                "#content-protector-ajaxLoading-{id} {\n" .
                "\t/* " . __( "CSS styles for the AJAX loading message", "content-protector" ) . " */\n}\n";
            $color_controls = array( "#" . CONTENT_PROTECTOR_HANDLE . "_border_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_background_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_form_instructions_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_ajax_loading_message_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_form_submit_label_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_form_submit_button_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_captcha_background_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_captcha_text_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_captcha_noise_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_success_message_color",
                "#" . CONTENT_PROTECTOR_HANDLE . "_error_message_color"
            );

            wp_enqueue_script( CONTENT_PROTECTOR_SLUG . '-admin-js', CONTENT_PROTECTOR_PLUGIN_URL . '/js/content-protector-admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-accordion', 'wp-color-picker' ), CONTENT_PROTECTOR_VERSION );
            wp_localize_script( CONTENT_PROTECTOR_SLUG . '-admin-js',
                'contentProtectorAdminOptions',
                array( 'theme_colors' => "['" . join( "','", $this->__getThemeColors() ) . "']",
                    'color_controls' => join( ",", $color_controls ),
                    'form_instructions_default' => CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS,
                    'form_instructions_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_form_instructions',
                    'ajax_loading_message_default' => CONTENT_PROTECTOR_DEFAULT_AJAX_LOADING_MESSAGE,
                    'ajax_loading_message_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message',
                    'success_message_default' => CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE,
                    'success_message_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_success_message',
                    'error_message_default' => CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE,
                    'error_message_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_error_message',
                    'form_submit_label_default' => CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL,
                    'form_submit_label_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_form_submit_label',
                    'captcha_instructions_default' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_INSTRUCTIONS,
                    'captcha_instructions_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_captcha_instructions',
                    'captcha_width_default' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_WIDTH,
                    'captcha_width_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_captcha_width',
                    'captcha_height_default' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_HEIGHT,
                    'captcha_height_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_captcha_height',
                    'captcha_text_chars_default' => CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_CHARS,
                    'captcha_text_chars_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_captcha_text_chars',
                    'form_css_all_default' => $css_all_default,
                    'form_css_ident_default' => $css_ident_default,
                    'form_css_ident_dialog' => __( "Enter the Content Protector identifier \nwhose form you want to customize:", "content-protector" ),
                    'form_css_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_form_css' ) );
        }

        /**
         * Initialize the Settings page and associated fields.
         *
         */
        function initSettingsPage()
        {

            $plugin_page = add_options_page( __( 'Content Protector', "content-protector" ), __( 'Content Protector', "content-protector" ), 'manage_options', CONTENT_PROTECTOR_HANDLE, array( &$this, 'drawSettingsPage' ) );
            add_action( "admin_print_styles-" . $plugin_page, array( &$this, "addAdminHeaderCode" ) );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_general_settings_section', __( 'General Settings', "content-protector" ), array( &$this, '__generalSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage' );
            // Add the fields for the General Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_encryption_algorithm', __( 'Encryption Algorithm', "content-protector" ), array( &$this, '__encryptionAlgorithmFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_general_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_content_filters', __( 'Default Protected Content Filters', "content-protector" ), array( &$this, '__contentFiltersFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_general_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_other_content_filters', __( 'Other Protected Content Filters', "content-protector" ), array( &$this, '__otherContentFiltersFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_general_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_share_auth', __( 'Shared Authorization', "content-protector" ), array( &$this, '__shareAuthFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_general_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_share_auth_duration', __( 'Shared Authorization Cookie Duration', "content-protector" ), array( &$this, '__shareAuthDurationFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_general_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords', __( 'Store Encrypted Passwords ', "content-protector" ), array( &$this, '__storeEncryptedPasswordsFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_general_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_delete_options_on_uninstall', __( 'Delete Plugin Options On Uninstall ', "content-protector" ), array( &$this, '__deleteOptionsOnUninstallFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_general_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_general_settings_group', CONTENT_PROTECTOR_HANDLE . '_encryption_algorithm', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_general_settings_group', CONTENT_PROTECTOR_HANDLE . '_content_filters', '' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_general_settings_group', CONTENT_PROTECTOR_HANDLE . '_other_content_filters', '' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_general_settings_group', CONTENT_PROTECTOR_HANDLE . '_share_auth', '' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_general_settings_group', CONTENT_PROTECTOR_HANDLE . '_share_auth_duration', 'intval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_general_settings_group', CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords', '' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_general_settings_group', CONTENT_PROTECTOR_HANDLE . '_delete_options_on_uninstall', '' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_section', __( 'Form Instructions', "content-protector" ), array( &$this, '__formInstructionsSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage' );
            // Add the fields for the Form Instructions Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_instructions', __( 'Instructions Text', "content-protector" ), array( &$this, '__formInstructionsFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_weight', __( 'Font Weight', "content-protector" ), array( &$this, '__formInstructionsFontWeightFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_size', __( 'Font Size', "content-protector" ), array( &$this, '__formInstructionsFontSizeFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_instructions_color', __( 'Text Color', "content-protector" ), array( &$this, '__formInstructionsColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_group', CONTENT_PROTECTOR_HANDLE . '_form_instructions', '' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_group', CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_weight', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_group', CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_size', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_group', CONTENT_PROTECTOR_HANDLE . '_form_instructions_color', 'esc_attr' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_section', __( 'AJAX Loading Message', "content-protector" ), array( &$this, '__ajaxLoadingMessageSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage' );
            // Add the fields for the AJAX Loading Message Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message', __( 'Message Text', "content-protector" ), array( &$this, '__ajaxLoadingMessageFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_weight', __( 'Font Weight', "content-protector" ), array( &$this, '__ajaxLoadingMessageFontWeightFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_style', __( 'Font Style', "content-protector" ), array( &$this, '__ajaxLoadingMessageFontStyleFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_color', __( 'Text Color', "content-protector" ), array( &$this, '__ajaxLoadingMessageColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_weight', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_style', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_color', 'esc_attr' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_section', __( 'Success Message', "content-protector" ), array( &$this, '__successMessageSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage' );
            // Add the fields for the Success Message Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_success_message_display', __( 'Display Success Message', "content-protector" ), array( &$this, '__successMessageDisplayFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_success_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_success_message', __( 'Message Text', "content-protector" ), array( &$this, '__successMessageFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_success_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_success_message_font_weight', __( 'Font Weight', "content-protector" ), array( &$this, '__successMessageFontWeightFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_success_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_success_message_font_size', __( 'Font Size', "content-protector" ), array( &$this, '__successMessageFontSizeFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_success_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_success_message_color', __( 'Text Color', "content-protector" ), array( &$this, '__successMessageColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_success_message_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_success_message_display', '' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_success_message', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_success_message_font_weight', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_success_message_font_size', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_success_message_color', 'esc_attr' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_section', __( 'Error Message', "content-protector" ), array( &$this, '__errorMessageSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage' );
            // Add the fields for the Error Message Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_error_message', __( 'Message Text', "content-protector" ), array( &$this, '__errorMessageFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_error_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_error_message_font_weight', __( 'Font Weight', "content-protector" ), array( &$this, '__errorMessageFontWeightFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_error_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_error_message_font_size', __( 'Font Size', "content-protector" ), array( &$this, '__errorMessageFontSizeFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_error_message_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_error_message_color', __( 'Text Color', "content-protector" ), array( &$this, '__errorMessageColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_error_message_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_error_message', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_error_message_font_weight', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_error_message_font_size', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_group', CONTENT_PROTECTOR_HANDLE . '_error_message_color', 'esc_attr' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_section', __( 'Form Submit Button', "content-protector" ), array( &$this, '__formSubmitLabelSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_subpage' );
            // Add the fields for the Form Submit Button Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_submit_label', __( 'Label Text', "content-protector" ), array( &$this, '__formSubmitLabelFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_color', __( 'Text Color', "content-protector" ), array( &$this, '__formSubmitLabelColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_submit_button_color', __( 'Button Color', "content-protector" ), array( &$this, '__formSubmitButtonColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_group', CONTENT_PROTECTOR_HANDLE . '_form_submit_label', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_group', CONTENT_PROTECTOR_HANDLE . '_form_submit_label_color', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_group', CONTENT_PROTECTOR_HANDLE . '_form_submit_button_color', 'esc_attr' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section', __( 'CAPTCHA Image', "content-protector" ), array( &$this, '__captchaSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage' );
            // Add the fields for the Form Submit Button Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_instructions', __( 'CAPTCHA Instructions Text', "content-protector" ), array( &$this, '__captchaInstructionsFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_instructions_display', __( 'CAPTCHA Instructions Display Mode', "content-protector" ), array( &$this, '__captchaInstructionsDisplayFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_width', __( 'Image Width', "content-protector" ), array( &$this, '__captchaWidthFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_height', __( 'Image Height', "content-protector" ), array( &$this, '__captchaHeightFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_text_chars', __( 'Image Text Characters', "content-protector" ), array( &$this, '__captchaTextCharsFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_text_length', __( 'Image Text Length', "content-protector" ), array( &$this, '__captchaTextLengthFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_text_height', __( 'Image Text Height', "content-protector" ), array( &$this, '__captchaTextHeightFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_text_angle_variance', __( 'Image Text Angle Variance', "content-protector" ), array( &$this, '__captchaTextAngleVarianceFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_background_color', __( 'Image Background Color', "content-protector" ), array( &$this, '__captchaBackgroundColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_text_color', __( 'Image Text Color', "content-protector" ), array( &$this, '__captchaTextColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_noise_color', __( 'Image Noise Color', "content-protector" ), array( &$this, '__captchaNoiseColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_captcha_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_instructions', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_instructions_display', 'intval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_width', 'intval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_height', 'intval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_text_chars', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_text_length', 'intval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_text_height', 'floatval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_text_angle_variance', 'intval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_background_color', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_text_color', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_noise_color', 'esc_attr' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section', __( 'Form CSS', "content-protector" ), array( &$this, '__formCSSSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage' );
            // Add the fields for the Form CSS Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content', __( 'Display Form CSS On Unlocked Content', "content-protector" ), array( &$this, '__formCSSOnUnlockedContentCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_border_style', __( 'Border Style', "content-protector" ), array( &$this, '__formBorderStyleFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_border_color', __( 'Border Color', "content-protector" ), array( &$this, '__formBorderColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_border_width', __( 'Border Width', "content-protector" ), array( &$this, '__formBorderWidthFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_border_radius', __( 'Border Radius', "content-protector" ), array( &$this, '__formBorderRadiusFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_padding', __( 'Padding', "content-protector" ), array( &$this, '__formPaddingFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_background_color', __( 'Background Color', "content-protector" ), array( &$this, '__formBackgroundColorFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_css', __( 'Additional CSS', "content-protector" ), array( &$this, '__formCSSFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_form_css_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group', CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group', CONTENT_PROTECTOR_HANDLE . '_border_style', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group', CONTENT_PROTECTOR_HANDLE . '_border_color', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group', CONTENT_PROTECTOR_HANDLE . '_border_width', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group', CONTENT_PROTECTOR_HANDLE . '_border_radius', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group', CONTENT_PROTECTOR_HANDLE . '_padding', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group', CONTENT_PROTECTOR_HANDLE . '_background_color', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group', CONTENT_PROTECTOR_HANDLE . '_form_css', 'esc_attr' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_password_settings_section', __( 'Password/CAPTCHA Field', "content-protector" ), array( &$this, '__passwordSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage' );
            // Add the fields for the Form CSS Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_password_field_type', __( 'Password Field Type', "content-protector" ), array( &$this, '__passwordFieldTypeFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_password_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_field_type', __( 'CAPTCHA Field Type', "content-protector" ), array( &$this, '__captchaFieldTypeFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_password_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_password_field_placeholder', __( 'Password Field Placeholder', "content-protector" ), array( &$this, '__passwordFieldPlaceholderFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_password_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_field_placeholder', __( 'CAPTCHA Field Placeholder', "content-protector" ), array( &$this, '__captchaFieldPlaceholderFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_password_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_password_field_length', __( 'Password Field Length', "content-protector" ), array( &$this, '__passwordFieldLengthFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_password_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_field_length', __( 'CAPTCHA Field Length', "content-protector" ), array( &$this, '__captchaFieldLengthFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_password_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', __( 'CAPTCHA Case Insensitive', "content-protector" ), array( &$this, '__captchaCaseInsensitiveFieldCallback' ), CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage', CONTENT_PROTECTOR_HANDLE . '_password_settings_section' );
            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE . '_password_settings_group', CONTENT_PROTECTOR_HANDLE . '_password_field_type', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_password_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_field_type', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_password_settings_group', CONTENT_PROTECTOR_HANDLE . '_password_field_placeholder', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_password_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_field_placeholder', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_password_settings_group', CONTENT_PROTECTOR_HANDLE . '_password_field_length', 'intval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_password_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_field_length', 'intval' );
            register_setting( CONTENT_PROTECTOR_HANDLE . '_password_settings_group', CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', 'esc_attr' );
        }

        function __formInstructionsSettingsSectionFieldCallback()
        {
            _e( "Customize the form instructions on the access forms.", "content-protector" );
        }

        function __formInstructionsFieldCallback()
        {
            $editor_settings = array( "textarea_rows" => "4" );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions', CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS );

            wp_editor( $current_value, CONTENT_PROTECTOR_HANDLE . '_form_instructions', $editor_settings );
            echo "&nbsp;<a href=\"javascript:;\" id=\"form-instructions-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Instructions for your access form.", "content-protector" );
            /* translators: %s refers to a CSS class on the access form. */
            echo "<br /><em>" . sprintf( __( "You can manually style this on all access forms using the %s CSS class.", "content-protector" ), "</em><code>div.content-protector-form-instructions</code><em>" ) . "</em>";
        }

        function __formInstructionsFontSizeFieldCallback()
        {
            $option_values = array_combine( range( 8, 20 ), range( 8, 20 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_size', CONTENT_PROTECTOR_DEFAULT_FONT_SIZE_OPTION );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_size' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_size' . '">';
            echo '<option value="0" ' . selected( '0', $current_value, false ) . ' >Default</option>';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . ' px</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Font size of the form instructions text.", "content-protector" );
        }

        function __formInstructionsFontWeightFieldCallback()
        {
            $option_values = array_combine( range( 100, 900, 100 ), range( 100, 900, 100 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_weight', CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_weight' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_instructions_font_weight' . '">';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Font weight of the form instructions text (400 is normal, 700 is bold).", "content-protector" );
        }

        function __formInstructionsColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_form_instructions_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_instructions_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the form instructions text.", "content-protector" );
        }

        function __ajaxLoadingMessageSettingsSectionFieldCallback()
        {
            _e( "Customize the loading message on the access forms when using AJAX for inline loading of your protected content.", "content-protector" );
        }

        function __ajaxLoadingMessageFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message', CONTENT_PROTECTOR_DEFAULT_AJAX_LOADING_MESSAGE );
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message' . '" value="' . $current_value . '" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"ajax-loading-message-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "When using AJAX, this message is displayed while the password is being checked.", "content-protector" );
            /* translators: %s refers to a CSS class on the access form. */
            echo "<br /><em>" . sprintf( __( "You can manually style this on all access forms using the %s CSS class.", "content-protector" ), "</em><code>div.content-protector-ajaxLoading</code><em>" ) . "</em>";
        }

        function __ajaxLoadingMessageFontWeightFieldCallback()
        {
            $option_values = array_combine( range( 100, 900, 100 ), range( 100, 900, 100 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_weight', CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_weight' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_font_weight' . '">';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Font weight of the AJAX loading message text (400 is normal, 700 is bold).", "content-protector" );
        }

        function __ajaxLoadingMessageFontStyleFieldCallback()
        {
            $options = array( "normal", "italic", "oblique" );
            $option_values = array_combine( $options, $options );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . "_ajax_loading_message_font_style", "" );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_ajax_loading_message_font_style\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_ajax_loading_message_font_style\">";
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Font style of the AJAX loading message text.", "content-protector" );
        }

        function __ajaxLoadingMessageColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the AJAX loading message text.", "content-protector" );
        }

        function __successMessageSettingsSectionFieldCallback()
        {
            _e( "Customize the message displayed when the correct password is entered.", "content-protector" );
        }

        function __successMessageDisplayFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_display', "" );
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_success_message_display" id="' . CONTENT_PROTECTOR_HANDLE . '_success_message_display" value="1"' . ( ( ( isset( $current_value ) ) && ( $current_value == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '&nbsp;<label for="' . CONTENT_PROTECTOR_HANDLE . '_success_message_display">' . __( "Show the success message when a user first successfully logs in.", "content-protector" ) . '</label>';
        }

        function __successMessageFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_success_message', CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE );
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_success_message' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_success_message' . '" value="' . $current_value . '" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"success-message-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Message when your users enter the correct password.", "content-protector" );
            /* translators: %s refers to a CSS class on the access form. */
            echo "<br /><em>" . sprintf( __( "You can manually style this on all access forms using the %s CSS class.", "content-protector" ), "</em><code>div.content-protector-correct-password</code><em>" ) . "</em>";
        }

        function __successMessageFontSizeFieldCallback()
        {
            $option_values = array_combine( range( 8, 20 ), range( 8, 20 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_font_size', CONTENT_PROTECTOR_DEFAULT_FONT_SIZE_OPTION );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_success_message_font_size' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_success_message_font_size' . '">';
            echo '<option value="0" ' . selected( '0', $current_value, false ) . ' >Default</option>';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . ' px</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Font size of the success message text.", "content-protector" );
        }

        function __successMessageFontWeightFieldCallback()
        {
            $option_values = array_combine( range( 100, 900, 100 ), range( 100, 900, 100 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_font_weight', CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_success_message_font_weight' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_success_message_font_weight' . '">';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Font weight of the success message text (400 is normal, 700 is bold).", "content-protector" );
        }

        function __successMessageColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_success_message_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_success_message_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_success_message_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the success message text.", "content-protector" );
        }

        function __errorMessageSettingsSectionFieldCallback()
        {
            _e( "Customize the message displayed when an incorrect password is entered.", "content-protector" );
        }

        function __errorMessageFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_error_message', CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE );
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_error_message' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_error_message' . '" value="' . $current_value . '" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"error-message-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Error message when your users enter an incorrect password.", "content-protector" );
            /* translators: %s refers to a CSS class on the access form. */
            echo "<br /><em>" . sprintf( __( "You can manually style this on all access forms using the %s CSS class.", "content-protector" ), "</em><code>div.content-protector-incorrect-password</code><em>" ) . "</em>";
        }

        function __errorMessageFontSizeFieldCallback()
        {
            $option_values = array_combine( range( 8, 20 ), range( 8, 20 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_font_size', CONTENT_PROTECTOR_DEFAULT_FONT_SIZE_OPTION );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_error_message_font_size' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_error_message_font_size' . '">';
            echo '<option value="0" ' . selected( '0', $current_value, false ) . ' >Default</option>';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . ' px</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Font size of the error message text.", "content-protector" );
        }

        function __errorMessageFontWeightFieldCallback()
        {
            $option_values = array_combine( range( 100, 900, 100 ), range( 100, 900, 100 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_font_weight', CONTENT_PROTECTOR_DEFAULT_FONT_WEIGHT );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_error_message_font_weight' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_error_message_font_weight' . '">';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Font weight of the error message text (400 is normal, 700 is bold).", "content-protector" );
        }

        function __errorMessageColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_error_message_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_error_message_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_error_message_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the error message text.", "content-protector" );
        }

        function __formSubmitLabelSettingsSectionFieldCallback()
        {
            _e( "Customize the submit button on the access forms.", "content-protector" );
        }

        function __formSubmitLabelFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label', CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL );
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_form_submit_label' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_submit_label' . '" value="' . $current_value . '" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"form-submit-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Customize the submit button label on the form.", "content-protector" );
            /* translators: %s refers to a CSS class on the access form. */
            echo "<br /><em>" . sprintf( __( "You can manually style this on all access forms using the %s CSS class.", "content-protector" ), "</em><code>input.content-protector-form-submit</code><em>" ) . "</em>";
        }

        function __formSubmitLabelColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_form_submit_label_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_submit_label_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the form submit label text.", "content-protector" );
        }

        function __formSubmitButtonColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_button_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_form_submit_button_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_submit_button_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the form submit button.", "content-protector" );
        }

        function __captchaSettingsSectionFieldCallback()
        {
            _e( "Customize the CAPTCHA on the access forms.", "content-protector" );
            /* translators: %s refers to the input.content-protector-captcha-img CSS class on the access form. */
            echo "<br /><em>" . sprintf( __( "You can manually style the CAPTCHA image on all access forms using the %s CSS class.", "content-protector" ), "</em><code>input.content-protector-captcha-img</code><em>" ) . "</em>";
        }

        function __captchaInstructionsFieldCallback()
        {
            $editor_settings = array( "textarea_rows" => "4" );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_instructions', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_INSTRUCTIONS );
            wp_editor( $current_value, CONTENT_PROTECTOR_HANDLE . '_captcha_instructions', $editor_settings );
            echo "&nbsp;<a href=\"javascript:;\" id=\"captcha-instructions-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Instructions for filling in the CAPTCHA.", "content-protector" );
            /* translators: %s refers to a CSS class on the access form. */
            echo "<br /><em>" . sprintf( __( "You can manually style this on all access forms using the %s CSS class.", "content-protector" ), "</em><code>label.content-protector-form-instructions</code><em>" ) . "</em>";
        }

        function __captchaInstructionsDisplayFieldCallback()
        {
            $option_values = array( "0" => __( "Do not display", "content-protector" ),
                "1" => __( "Display with Form Instructions", "content-protector" ),
                "2" => __( "Display instead of Form Instructions", "content-protector" ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . "_captcha_instructions_display", "1" );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_instructions_display\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_instructions_display\">";
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "When using a CAPTCHA, how should the CAPTCHA Instructions be displayed?", "content-protector" );
        }

        function __captchaWidthFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_width', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_WIDTH );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_width' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_width' . '" value="' . $current_value . '" size="3" maxlength="3" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"captcha-width-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<br />" . __( "Set the width (in pixels) of the CAPTCHA box.", "content-protector" );
        }

        function __captchaHeightFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_height', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_HEIGHT );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_height' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_height' . '" value="' . $current_value . '" size="3" maxlength="3" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"captcha-height-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<br />" . __( "Set the height (in pixels) of the CAPTCHA box.", "content-protector" );
        }

        function __captchaTextCharsFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_text_chars', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_CHARS );
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_text_chars' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_text_chars' . '" value="' . $current_value . '" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"captcha-text-chars-reset\">" . __( "Reset To Default", "content-protector" ) . "</a>";
            echo "<br />" . __( "Characters used to generate the CAPTCHA image text.", "content-protector" );
        }

        function __captchaTextLengthFieldCallback()
        {
            $option_values = array_combine( range( 3, 10 ), range( 3, 10 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . "_captcha_text_length", CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_LENGTH );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_text_length\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_text_length\">";
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "String length of the CAPTCHA image text", "content-protector" );
        }

        function __captchaTextHeightFieldCallback()
        {
            $option_values = array_combine( range( 0.4, 0.8, 0.05 ), range( 40, 80, 5 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . "_captcha_text_height", CONTENT_PROTECTOR_DEFAULT_CAPTCHA_TEXT_HEIGHT_PCT );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_text_height\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_text_height\">";
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . ' %</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Height of the CAPTCHA image text, relative to the image height", "content-protector" );
        }

        function __captchaTextAngleVarianceFieldCallback()
        {
            $option_values = array_combine( range( 0, 30, 5 ), range( 0, 30, 5 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . "_captcha_text_angle_variance", "0" );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_text_angle_variance\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_text_angle_variance\">";
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>+/- ' . $label . '&deg;</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Maximum angle of the CAPTCHA image text (the angle of the text will vary within this range for each CAPTCHA image)", "content-protector" );
        }

        function __captchaBackgroundColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_background_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_background_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_background_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the CAPTCHA background.", "content-protector" );
        }

        function __captchaTextColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_text_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_text_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_text_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the CAPTCHA text.", "content-protector" );
        }

        function __captchaNoiseColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_noise_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_noise_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_noise_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Color of the CAPTCHA background noise.", "content-protector" );
        }

        function __formCSSSettingsSectionFieldCallback()
        {
            _e( "Customize the overall look-and-feel of your access forms.", "content-protector" );
            /* translators: %s refers to the 'form.content-protector-access-form' CSS class. */
            echo "<br /><em>" . sprintf( __( "You can manually style the overall look of all access forms using the %s CSS class.", "content-protector" ), "</em><code>form.content-protector-access-form</code><em>" ) . "</em>";
        }

        function __formCSSOnUnlockedContentCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content', "1" );
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content" id="' . CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content" value="1"' . ( ( ( isset( $current_value ) ) && ( $current_value == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '&nbsp;<label for="' . CONTENT_PROTECTOR_HANDLE . '_css_on_unlocked_content">' . __( "Wrap the form's CSS style around protected content when it's unlocked?.", "content-protector" ) . '</label>';
        }

        function __formBorderStyleFieldCallback()
        {
            $options = array( "dotted", "dashed", "solid", "double", "groove", "ridge", "inset", "outset" );
            $option_values = array_combine( $options, $options );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . "_border_style", "" );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_border_style\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_border_style\">";
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . ' >' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Border style of the access form.", "content-protector" );
        }

        function __formBorderColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_border_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_border_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_border_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Border color of the access form.", "content-protector" );
        }

        function __formBorderRadiusFieldCallback()
        {
            $option_values = array_combine( range( 0, 45, 5 ), range( 0, 45, 5 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_border_radius', "" );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_border_radius' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_border_radius' . '">';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Border radius (curvature of the corners) of the access form.", "content-protector" );
        }

        function __formBorderWidthFieldCallback()
        {
            $option_values = array_combine( range( 0, 5 ), range( 0, 5 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_border_width', "" );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_border_width' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_border_width' . '">';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Border width of the access form.", "content-protector" );
        }

        function __formPaddingFieldCallback()
        {
            $option_values = array_combine( range( 0, 25, 5 ), range( 0, 25, 5 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_padding', "" );

            echo '<select name="' . CONTENT_PROTECTOR_HANDLE . '_padding' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_padding' . '">';
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Padding inside the border of the access form.", "content-protector" );
        }

        function __formBackgroundColorFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_background_color', "" );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_background_color' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_background_color' . '" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "<br />" . __( "Background color of the access form.", "content-protector" );
        }

        function __formCSSFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_form_css', "" );
            echo '<textarea style="vertical-align: top; float: left;" rows="12" cols="70" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_form_css' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_css' . '">' . $current_value . '</textarea>';
            echo "&nbsp;<a href=\"javascript:;\" id=\"form-css-all\">" . __( "Add CSS scaffolding for all access forms", "content-protector" ) . "</a>";
            echo "<br />&nbsp;<a href=\"javascript:;\" id=\"form-css-ident\">" . __( "Add CSS scaffolding for a specific access form", "content-protector" ) . "</a>";
            echo "<br />&nbsp;<a href=\"javascript:;\" id=\"form-css-reset\">" . _x( "Clear", "Clear the textarea", "content-protector" ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Apply custom CSS to your access form.", "content-protector" );
            echo " <strong>" . __( "Knowledge of CSS required.", "content-protector" ) . "</strong>";
        }

        function __generalSettingsSectionFieldCallback()
        {
            echo __( "Control how your content is protected.", "content-protector" );
        }

        function __encryptionAlgorithmFieldCallback()
        {
            $option_values = array( "CRYPT_STD_DES" => _x( "Standard DES", "Encryption algorithm", "content-protector" ),
                "CRYPT_EXT_DES" => _x( "Extended DES", "Encryption algorithm", "content-protector" ),
                "CRYPT_MD5" => _x( "MD5", "Encryption algorithm", "content-protector" ),
                "CRYPT_BLOWFISH" => _x( "Blowfish", "Encryption algorithm", "content-protector" ),
                "CRYPT_SHA256" => _x( "SHA-256", "Encryption algorithm", "content-protector" ),
                "CRYPT_SHA512" => _x( "SHA-512", "Encryption algorithm", "content-protector" ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . "_encryption_algorithm", CONTENT_PROTECTOR_DEFAULT_ENCRYPTION_ALGORITHM );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_encryption_algorithm\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_encryption_algorithm\">";
            foreach ( $option_values as $value => $label ) {
                if ( ( defined( $value ) ) && ( constant( $value ) === 1 ) )
                    echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<p>" . __( "Select the encryption algorithm to encrypt the password for your protected content. Only those algorithms supported by your server are listed.", "content-protector" ) . "</p>";
            /* translators: %1$s refers to 'URL for PHP's crypt() man page (if available, please change this to link to the URL for your translated language).'; %2$s refers to 'Link text for PHP's crypt() man page (language-specific)'. */
            echo "<p>" . sprintf( __( 'More info at <a href="%1$s">%2$s</a>.', "content-protector" ),
                    _x( "http://www.php.net/manual/en/function.crypt.php", "URL for PHP's crypt() man page (if available, please change this to link to the URL for your translated language).", "content-protector" ),
                    _x( "PHP's crypt() man page", "Link text for PHP's crypt() man page (language-specific)", "content-protector" ) ) . "</p>";
        }

        function __contentFiltersFieldCallback()
        {
            echo "<p>" . __( "Select which default filters to use on protected content that has been unlocked.", "content-protector" ) . "</p>";
            $current_values = get_option( CONTENT_PROTECTOR_HANDLE . '_content_filters', $this->default_content_filters );
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_content_filters[wptexturize]" id="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_wptexturize" value="1"' . ( ( ( isset( $current_values[ 'wptexturize' ] ) ) && ( $current_values[ 'wptexturize' ] == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_wptexturize"><code>wptexturize</code></label><br />';
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_content_filters[convert_smilies]" id="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_convert_smilies" value="1"' . ( ( ( isset( $current_values[ 'convert_smilies' ] ) ) && ( $current_values[ 'convert_smilies' ] == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_convert_smilies"><code>convert_smilies</code></label><br />';
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_content_filters[convert_chars]" id="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_convert_chars" value="1"' . ( ( ( isset( $current_values[ 'convert_chars' ] ) ) && ( $current_values[ 'convert_chars' ] == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_convert_chars"><code>convert_chars</code></label><br />';
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_content_filters[wpautop]" id="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_wpautop" value="1"' . ( ( ( isset( $current_values[ 'wpautop' ] ) ) && ( $current_values[ 'wpautop' ] == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_wpautop"><code>wpautop</code></label><br />';
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_content_filters[prepend_attachment]" id="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_prepend_attachment" value="1"' . ( ( ( isset( $current_values[ 'prepend_attachment' ] ) ) && ( $current_values[ 'prepend_attachment' ] == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_prepend_attachment"><code>prepend_attachment</code></label><br />';
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_content_filters[do_shortcode]" id="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_do_shortcode" value="1"' . ( ( ( isset( $current_values[ 'do_shortcode' ] ) ) && ( $current_values[ 'do_shortcode' ] == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_content_filters_do_shortcode"><code>do_shortcode</code></label><br />';
            echo "<p>" . sprintf( __( "These filters will be added to the %s filter, applied when content is unlocked.", "content-protector" ), "<code>content_protector_unlocked_content</code>" ) . "</p>";
        }

        function __otherContentFiltersFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_other_content_filters', "" );
            $placeholder = __( "Example: filter_1,filter_2,filter_3", "content-protector");
            echo "<p>" . sprintf( __( "Specify any other filters to use to use on protected content that has been unlocked. Separate each filter name with %s.", "content-protector" ), "<code>,</code>" ). "</p>";
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_other_content_filters' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_other_content_filters' . '" value="' . $current_value . '" placeholder="' . $placeholder . '" />';
            echo "<p>" . sprintf( __( "These filters will be added to the %s filter, applied when content is unlocked.", "content-protector" ), "<code>content_protector_unlocked_content</code>" ) . "</p>";
        }

        function __shareAuthFieldCallback()
        {
            echo "<p>" . __( "If checked, sets a cookie to share authorization among protected content sections if the sections share specific properties.", "content-protector" ) . "</p>";
            $current_values = get_option( CONTENT_PROTECTOR_HANDLE . '_share_auth', array() );
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_share_auth[same_page]" id="' . CONTENT_PROTECTOR_HANDLE . '_share_auth_same_page" value="1"' . ( ( ( isset( $current_values[ 'same_page' ] ) ) && ( $current_values[ 'same_page' ] == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_share_auth_same_page">' . __( "Share authorization for protected content that share the same Post/Page and Password", "content-protector" ) . '</label><br />';
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_share_auth[same_identifier]" id="' . CONTENT_PROTECTOR_HANDLE . '_share_auth_same_identifier" value="1"' . ( ( ( isset( $current_values[ 'same_identifier' ] ) ) && ( $current_values[ 'same_identifier' ] == "1" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_share_auth_same_identifier">' . __( "Share authorization for protected content that share the same Identifier and Password", "content-protector" ) . '</label><br />';
            echo "<p>" . __( "NOTE: Visitors must successfully log into one matching protected content section in order to automatically access the others.", "content-protector" ) . "</p>";
            echo "<p>" . __( "NOTE: Will not work wth CAPTCHAs since passwords for CAPTCHA protected content are always changing.", "content-protector" ) . "</p>";
        }

        function __shareAuthDurationFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_share_auth_duration', CONTENT_PROTECTOR_DEFAULT_SHARE_AUTH_DURATION );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_share_auth_duration' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_share_auth_duration' . '" value="' . $current_value . '" size="7" style="width: 100px;" />';
            echo "<p>" . __( "Duration (in seconds) for any shared authorization cookies.  Once a shared authorization cookie expires, any cookies previously set for individual protected content sections in the group will be referenced instead.", "content-protector" ) . "</p>";
        }

        function __storeEncryptedPasswordsFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords', "1" );
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords" id="' . CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords" value="1" ' . checked( 1, get_option( CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords' ), false ) . ' />';
            echo "&nbsp;<label for='" . CONTENT_PROTECTOR_HANDLE . "_store_encrypted_passwords'>" . __( "If checked, encrypted passwords are kept in Transient storage for quicker lookup.", "content-protector" ) . "</label><br />";
            if ( $current_value == "1" ) {
                $password_hashes = get_transient( 'content_protector_password_hashes' );
                if ( is_array( $password_hashes ) ) {
                    $text = sprintf( _n( '%d password is stored in the database.', '%d passwords are stored in the database.', count( $password_hashes ), "content-protector" ), count( $password_hashes ) );
                    echo "<p><em>" . $text . "</em></p>";
                }
            }
        }

        // Function to clear or initialize passwords transients if "Store Encrypted Passwords" is unchecked
        function updateEncryptedPasswordsTransients( $option, $old_value, $value )
        {
            if ( $option == CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords' ) {
                if ( $old_value != $value ) {
                    if ( "1" == $value ) {
                        set_transient( 'content_protector_password_hashes', array(), 1 * HOUR_IN_SECONDS );
                        $message = __( "Encrypted Passwords Storage enabled", "content-protector" );
                    } else {
                        delete_transient( 'content_protector_password_hashes' );
                        $message = __( "Encrypted Passwords Storage disabled", "content-protector" );
                    }
                    add_settings_error(
                        CONTENT_PROTECTOR_HANDLE . '_store_encrypted_passwords',
                        'updateEncryptedPasswordsTransients',
                        $message,
                        "updated" );
                }
            }
        }

        function __deleteOptionsOnUninstallFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_delete_options_on_uninstall', "" );
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_delete_options_on_uninstall" id="' . CONTENT_PROTECTOR_HANDLE . '_delete_options_on_uninstall" value="1" ' . checked( 1, get_option( CONTENT_PROTECTOR_HANDLE . '_delete_options_on_uninstall' ), false ) . ' />';
            echo "&nbsp;<label for='" . CONTENT_PROTECTOR_HANDLE . "_delete_options_on_uninstall'>" . __( "If checked, all plugin options will be deleted if the plugin is unstalled.", "content-protector" ) . "</label><br />";
        }

        function __passwordSettingsSectionFieldCallback()
        {
            echo __( "Control how the password/CAPTCHA entry field works in your access forms.", "content-protector" );
            /* translators: %s refers to the 'input.content-protector-password' CSS class. */
            echo "<br /><em>" . sprintf( __( "You can manually style this on all access forms using the %s CSS class.", "content-protector" ), "</em><code>input.content-protector-password</code><em>" ) . "</em>";

        }

        function __passwordFieldTypeFieldCallback()
        {
            echo "<p>" . __( "Select the HTML form element to use when you use a regular password for your forms.", "content-protector" ) . "</p>";
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_type', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_TYPE );
            echo '<input type="radio" name="' . CONTENT_PROTECTOR_HANDLE . '_password_field_type" id="' . CONTENT_PROTECTOR_HANDLE . '_password_field_type_password" value="password"' . ( ( ( isset( $current_value ) ) && ( $current_value == "password" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_password_field_type_password">' . _x( "Password", "HTML element", "content-protector" ) . '</label><br />';
            echo '<input type="radio" name="' . CONTENT_PROTECTOR_HANDLE . '_password_field_type" id="' . CONTENT_PROTECTOR_HANDLE . '_password_field_type_text" value="text"' . ( ( ( isset( $current_value ) ) && ( $current_value == "text" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_password_field_type_text">' . _x( "Text", "HTML element", "content-protector" ) . '</label><br />';
        }

        function __captchaFieldTypeFieldCallback()
        {
            echo "<p>" . __( "Select the HTML form element to use when you use a CAPTCHA for your forms.", "content-protector" ) . "</p>";
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_type', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_TYPE );
            echo '<input type="radio" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_field_type" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_field_type_password" value="password"' . ( ( ( isset( $current_value ) ) && ( $current_value == "password" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_captcha_field_type_password">' . _x( "Password", "HTML element", "content-protector" ) . '</label><br />';
            echo '<input type="radio" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_field_type" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_field_type_text" value="text"' . ( ( ( isset( $current_value ) ) && ( $current_value == "text" ) ) ? ' checked="checked"' : '' ) . ' />';
            echo '<label for="' . CONTENT_PROTECTOR_HANDLE . '_captcha_field_type_text">' . _x( "Text", "HTML element", "content-protector" ) . '</label><br />';
        }

        function __captchaCaseInsensitiveFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', "1" );
            echo '<input type="checkbox" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive" value="1" ' . checked( 1, get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive' ), false ) . ' />';
            echo "&nbsp;<label for='" . CONTENT_PROTECTOR_HANDLE . "_captcha_case_insensitive'>" . __( "If checked, entered CAPTCHAs will be compared as case-insensitive.", "content-protector" ) . "</label><br />";
        }

        function __passwordFieldPlaceholderFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_placeholder', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_PLACEHOLDER );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_password_field_placeholder' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_password_field_placeholder' . '" value="' . $current_value . '" />';
            echo "<p>" . __( "Set a placeholder to use when you use a regular password for your forms.", "content-protector" ) . "</p>";
        }

        function __captchaFieldPlaceholderFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_placeholder', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_PLACEHOLDER );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_captcha_field_placeholder' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_captcha_field_placeholder' . '" value="' . $current_value . '" />';
            echo "<p>" . __( "Set a placeholder to use when you use a CAPTCHA for your forms.", "content-protector" ) . "</p>";
        }

        function __passwordFieldLengthFieldCallback()
        {
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_password_field_length', CONTENT_PROTECTOR_DEFAULT_PASSWORD_FIELD_LENGTH );
            echo '<input type="text" name="' . CONTENT_PROTECTOR_HANDLE . '_password_field_length' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_password_field_length' . '" value="' . $current_value . '" size="2" maxlength="2" />';
            echo "<p>" . __( "Set the character length for the HTML password/text element when you use a regular password for your forms.", "content-protector" ) . "</p>";
        }

        function __captchaFieldLengthFieldCallback()
        {
            $option_values = array_combine( range( 3, 10 ), range( 3, 10 ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_field_length', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_FIELD_LENGTH );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_field_length\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_captcha_field_length\">";
            foreach ( $option_values as $value => $label ) {
                echo '<option value="' . $value . '" ' . selected( $value, $current_value, false ) . '>' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Set the character length for the HTML password/text element when you use a CAPTCHA for your forms.", "content-protector" );
        }


        /**
         * Prints out the Settings page.
         *
         */
        function drawSettingsPage()
        {
            ob_start();
            // Optional: you can display the admin screen as an accordion. Uncomment the next line of PHP code,
            // comment out the line following it, and follow the instructions in /js/content-protector-admin.js.
            //include( "screens/admin_screen_accordion.php" );
            include( "screens/admin_screen_tabs.php" );
            $content = ob_get_contents();
            ob_end_clean();

            echo $content;
        }

        /**
         *  Initializes the TinyMCE plugin bundled with this Wordpress plugin
         */
        function initTinyMCEPlugin()
        {
            if ( ( !current_user_can( 'edit_posts' ) ) && ( !current_user_can( 'edit_pages' ) ) )
                return;

            // Add only in Rich Editor mode
            if ( get_user_option( 'rich_editing' ) == 'true' ) {
                add_filter( "mce_external_plugins", array( &$this, "addTinyMCEPlugin" ) );
                add_filter( "mce_buttons", array( &$this, "registerTinyMCEButton" ) );
            }
        }

        /**
         * Sets up variables to use in the TinyMCE plugin's editor_plugin_src.js.
         *
         */
        function setTinyMCEPluginVars()
        {
            global $wp_version;
            if ( ( !current_user_can( 'edit_posts' ) ) && ( !current_user_can( 'edit_pages' ) ) )
                return;

            // Add only in Rich Editor mode
            if ( get_user_option( 'rich_editing' ) == 'true' ) {
                if ( version_compare( $wp_version, "3.8", "<" ) )
                    $image = "/lock.gif";
                else
                    $image = "";
                wp_localize_script( 'editor',
                    'contentProtectorAdminTinyMCEOptions',
                    array( 'version' => CONTENT_PROTECTOR_VERSION,
                        'handle' => CONTENT_PROTECTOR_HANDLE,
                        'desc' => __( "Add Content Protector shortcode", "content-protector" ),
                        'image' => $image ) );
            }
        }

        /**
         * Sets up the button for the associated TinyMCE plugin for use in the editor menubar.
         *
         * @param array $buttons Array of menu buttons already registered with TinyMCE
         * @return array            The array of TinyMCE menu buttons with ours now loaded in as well
         */
        function registerTinyMCEButton( $buttons )
        {
            array_push( $buttons, "|", CONTENT_PROTECTOR_HANDLE );
            return $buttons;
        }

        /**
         * Loads the associated TinyMCE plugin into TinyMCE's plugin array
         *
         * @param array $plugin_array Array of plugins already registered with TinyMCE
         * @return array                The array of TinyMCE plugins with ours now loaded in as well
         */
        function addTinyMCEPlugin( $plugin_array )
        {
            $plugin_array[ CONTENT_PROTECTOR_HANDLE ] = CONTENT_PROTECTOR_PLUGIN_URL . "/tinymce_plugin/plugin.js";
            return $plugin_array;
        }

        /**
         * Display a dialog box for this plugin's associated TinyMCE plugin.  Called from TinyMCE via AJAX.
         *
         */
        function contentProtectorPluginGetTinyMCEDialog()
        {
            include( "lib/jquery-ui-datetime-i18n.php" );

            wp_enqueue_style( CONTENT_PROTECTOR_SLUG . '-jquery-ui-css', CONTENT_PROTECTOR_JQUERY_UI_CSS );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_register_style( CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-css', CONTENT_PROTECTOR_JQUERY_UI_TIMEPICKER_CSS );
            wp_enqueue_style( CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-css' );
            wp_register_script( CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-js', CONTENT_PROTECTOR_JQUERY_UI_TIMEPICKER_JS, array( 'jquery', 'jquery-ui-datepicker' ), CONTENT_PROTECTOR_VERSION );
            wp_enqueue_script( CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-js' );
            if ( !( wp_script_is( CONTENT_PROTECTOR_SLUG . '-jquery-ui-datetime-i18n-js', 'registered' ) ) ) {
                wp_register_script( CONTENT_PROTECTOR_SLUG . '-jquery-ui-datetime-i18n-js', CONTENT_PROTECTOR_PLUGIN_URL . "/js/content-protector-datetime-i18n.js", array( 'jquery', 'jquery-ui-datepicker', CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-js' ), CONTENT_PROTECTOR_VERSION );
                wp_enqueue_script( CONTENT_PROTECTOR_SLUG . '-jquery-ui-datetime-i18n-js' );
                wp_localize_script( CONTENT_PROTECTOR_SLUG . '-jquery-ui-datetime-i18n-js', 'ContentProtectorJQDatepickerI18n', $jquery_ui_datetime_datepicker_i18n );
                wp_localize_script( CONTENT_PROTECTOR_SLUG . '-jquery-ui-datetime-i18n-js', 'ContentProtectorJQTimepickerI18n', $jquery_ui_datetime_timepicker_i18n );
            }

            ob_start();
            include( "tinymce_plugin/dialog.php" );
            $content = ob_get_contents();
            ob_end_clean();
            echo $content;
            wp_die();
        }

        /**
         * Enqueues the CSS code necessary for custom icons for the TinyMCE editor.  Echo'd to output.
         */
        function addTinyMCEIcons()
        {
            wp_enqueue_style( 'ca-aliencyborg-dashicons', CONTENT_PROTECTOR_CSS_DASHICONS, false, CONTENT_PROTECTOR_VERSION );
            ?>
            <style type="text/css" media="screen">
                .mce-i-content_protector:before {
                    font: 400 24px/1 'ca-aliencyborg-dashicons' !important;
                    padding: 0;
                    vertical-align: top;
                    content: '\e602';
                }
            </style>
            <?php
        }

        /**
         * Loads the appropriate i18n files
         *
         */
        function i18nInit() {
            $plugin_dir = basename( dirname( __FILE__ ) ) . "/lang/";
            load_plugin_textdomain( "content-protector", null, $plugin_dir );
        }

        function setupContentFilters() {
            $filters = get_option( CONTENT_PROTECTOR_HANDLE . '_content_filters', $this->default_content_filters );
            foreach ( $filters as $filter => $is_active ) {
                add_filter("content_protector_unlocked_content", $filter);
            }

            $other_filters = explode( ",", get_option( CONTENT_PROTECTOR_HANDLE . '_other_content_filters', "" ) );
            foreach ( $other_filters as $other_filter )
                if ( !empty( $other_filter ) )
                    add_filter( "content_protector_unlocked_content", $other_filter );

        }

        function activatePlugin() {
            foreach ( $this->default_options as $option => $value )
                update_option( CONTENT_PROTECTOR_HANDLE . '_' . $option, $value );
        }

    }

    //End Class contentProtectorPlugin

    function content_protector_is_logged_in( $password = "", $identifier = "", $post_id = "", $cookie_expires = "" )
    {
        if ( trim( $password ) == "" || trim( $identifier ) == "" || trim( $post_id == "" ) )
            return false;

        $ident = md5( $identifier );
        $post_permalink = get_permalink( $post_id );

        $captcha = ( strtoupper( $password ) === CONTENT_PROTECTOR_CAPTCHA_KEYWORD ? true : false );
        $password_field = ( isset( $_POST[ 'content-protector-password' ] ) ? $_POST[ 'content-protector-password' ] : "" );

        // Cookies from CAPTCHA protected content are built differently from cookies from password protected content
        if ( !$captcha ) {
            $cookie_name = CONTENT_PROTECTOR_COOKIE_ID . md5( $ident . $post_permalink );
            $is_live_cookie = ( ( isset( $_COOKIE[ $cookie_name ] ) ) && ( $_COOKIE[ $cookie_name ] == md5( $password . $cookie_expires . $ident . $post_permalink ) ) );
        		if ( 1 == get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_case_insensitive', "0" ) ) $password_field == strtoupper( $password_field );
        } else {
            $cookie_name = CONTENT_PROTECTOR_COOKIE_ID . md5( $ident . $post_permalink . "_captcha" );
            $is_live_cookie = ( ( isset( $_COOKIE[ $cookie_name ] ) ) && ( $_COOKIE[ $cookie_name ] == md5( $cookie_expires . $ident . $post_permalink ) ) );
       }
        

        return ( $is_live_cookie ||
            ( ( ( isset( $_POST[ 'content-protector-password' ] ) ) && ( isset( $_POST[ 'content-protector-token' ] ) ) )
                && ( crypt( $password_field, $_POST[ 'content-protector-token' ] ) == $_POST[ 'content-protector-token' ] )
                && ( ( isset( $_POST[ 'content-protector-ident' ] ) ) && ( $_POST[ 'content-protector-ident' ] === $ident ) ) ) );
    }

}

// Initialize plugin
if ( class_exists( "contentProtectorPlugin" ) ) {
    $contentProtectorPluginInstance = new contentProtectorPlugin();
}

// Actions and Filters
if ( isset( $contentProtectorPluginInstance ) ) {
    register_activation_hook( __FILE__, array( &$contentProtectorPluginInstance, "activatePlugin" ) );

    add_action( "init", array( &$contentProtectorPluginInstance, "i18nInit" ), 1 );
    add_action( "wp", array( &$contentProtectorPluginInstance, "setupContentFilters" ), 1 );
    add_action( "wp", array( &$contentProtectorPluginInstance, "setCookie" ), 1 );
    add_action( "wp_head", array( &$contentProtectorPluginInstance, "addHeaderCode" ), 99 );
    add_action( "wp_footer", array( &$contentProtectorPluginInstance, "addFooterCode" ), 1 );
    add_action( "admin_init", array( &$contentProtectorPluginInstance, "setTinyMCEPluginVars" ), 1 );
    add_action( "admin_init", array( &$contentProtectorPluginInstance, "initTinyMCEPlugin" ), 2 );
    add_action( "admin_menu", array( &$contentProtectorPluginInstance, "initSettingsPage" ), 1 );
    add_action( "admin_head", array( &$contentProtectorPluginInstance, "addTinyMCEIcons" ), 1 );
    add_action( 'wp_ajax_contentProtectorProcessFormAjax', array( &$contentProtectorPluginInstance, "contentProtectorProcessFormAjax" ), 1 );
    add_action( 'wp_ajax_nopriv_contentProtectorProcessFormAjax', array( &$contentProtectorPluginInstance, "contentProtectorProcessFormAjax" ), 1 );
    add_action( 'wp_ajax_contentProtectorPluginGetTinyMCEDialog', array( &$contentProtectorPluginInstance, "contentProtectorPluginGetTinyMCEDialog" ), 1 );
    add_action( 'update_option', array( &$contentProtectorPluginInstance, "updateEncryptedPasswordsTransients" ), 10, 3 );
    add_shortcode( CONTENT_PROTECTOR_SHORTCODE, array( &$contentProtectorPluginInstance, "processShortcode" ), 1 );
}
?>