<?php if (!isset($is_ajax_processed)) { ?><div id="content-protector<?php echo $identifier; ?>" class="content-protector-access-form"><?php } ?>
        <?php if (!(($is_captcha) && ($captcha_instr_mode == 2))) { ?>
            <div id="content-protector-form-instructions<?php echo $identifier; ?>"
                 class="content-protector-form-instructions"><?php echo $form_instructions; ?></div>
        <?php } ?>
        <?php if ($is_captcha) { ?>
            <?php if ($captcha_instr_mode > 0) { ?>
                <div for="content-protector-captcha-instructions<?php echo $identifier; ?>"
                     id="content-protector-captcha-instructions<?php echo $identifier; ?>"
                     class="content-protector-form-instructions"><?php echo $captcha_instructions; ?></div>
            <?php } ?>
            <img id="content-protector-captcha-img<?php echo $identifier; ?>" class="content-protector-captcha-img"
                 src="<?php echo $captcha_data_uri; ?>"/><br/>
        <?php } ?>
    <form id="content-protector-access-form<?php echo $identifier; ?>" method="post" action="" autocomplete="off">
        <?php if ($is_captcha) { ?>
            <input name="content-protector-captcha" id="content-protector-captcha<?php echo $identifier; ?>"
                   type="hidden" value="1"/>
        <?php } ?>
        <?php
        // Error message on unsuccessful attempt. Check $_POST['content-protector-ident'] to make sure
        // we're showing the error message on the right Content Protector access form
        if ((isset($_POST['content-protector-ident'])) && ($_POST['content-protector-ident'] == $ident)) {
            ?>
            <div id="content-protector-incorrect-password<?php echo $identifier; ?>"
                 class="content-protector-incorrect-password"><?php echo $incorrect_password_message; ?></div>
        <?php } ?>
        <input name="content-protector-password" id="content-protector-password<?php echo $identifier; ?>"
               class="content-protector-password" type="<?php echo $password_field_type; ?>" placeholder="<?php echo $placeholder; ?>"
               value="" size="<?php echo $password_field_length; ?>" maxlength="<?php echo $password_field_length; ?>"/>
        <?php if (strlen(trim($cookie_expires)) > 0) { ?>
            <input name="content-protector-expires" id="content-protector-expires<?php echo $identifier; ?>"
                   type="hidden" value="<?php echo $cookie_expires; ?>"/>
        <?php } ?>
        <input name="content-protector-token" id="content-protector-token<?php echo $identifier; ?>" type="hidden"
               value="<?php echo $password_hash; ?>"/>
        <input name="content-protector-ident" id="content-protector-ident<?php echo $identifier; ?>" type="hidden"
               value="<?php echo $ident; ?>"/>
        <input name="content-protector-submit" id="content-protector-submit<?php echo $identifier; ?>"
               class="content-protector-form-submit" type="submit"
               value="<?php echo $form_submit_label; ?>"/>
    </form>
<?php if (!isset($is_ajax_processed)) { ?></div><?php } ?>
<?php if (isset($is_ajax_processed) && ($is_ajax_processed)) { ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var form =  {
                form_id: "#content-protector-access-form<?php echo $identifier; ?>",
                target: "#content-protector<?php echo $identifier; ?>",
                post_id: "<?php echo $post_id; ?>",
                identifier: "<?php echo $identifier; ?>",
                time: "<?php echo $nonce_time; ?>",
                show_css_on_success: "<?php echo $css_on_unlocked_content; ?>",
                ajax_security_nonce: "<?php echo $ajax_security_nonce; ?>"
            };
            jQuery(form.form_id).ajaxFormUnbind();
            setupAjaxForm(form);
        });
    </script>
<?php } ?>