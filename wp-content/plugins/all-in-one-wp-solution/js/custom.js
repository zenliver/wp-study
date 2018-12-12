jQuery(document).ready(function(){
    jQuery("#btn1").click(function(){
       jQuery("#plugin-head").show();
       jQuery("#plugin-privacy").hide();
       jQuery("#plugin-login").hide();
       jQuery("#plugin-security").hide();
       jQuery("#plugin-update").hide();
       jQuery("#plugin-mail").hide();
       jQuery("#plugin-hf").hide();
       jQuery("#plugin-maintenance").hide();
       jQuery("#plugin-post-page").hide();
       jQuery("#plugin-comments").hide();
       jQuery("#plugin-minify").hide();
       jQuery("#plugin-dashborad").hide();
       jQuery("#plugin-email-test").hide();
       jQuery("#plugin-update-lock").hide();

    });

    jQuery("#btn2").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").show();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();

    });

    jQuery("#btn3").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").show();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();
 
    });

    jQuery("#btn4").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").show();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();
 
    });

    jQuery("#btn5").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").show();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").show();
 
    });

    jQuery("#btn6").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").show();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").show();
        jQuery("#plugin-update-lock").hide();
 
    });

    jQuery("#btn7").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").show();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();
 
    });

    jQuery("#btn8").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").show();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();
 
    });

    jQuery("#btn9").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").show();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();
    });

    jQuery("#btn10").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").show();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();
    });

    jQuery("#btn11").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").show();
        jQuery("#plugin-dashborad").hide();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();
 
    });

    jQuery("#btn12").click(function(){
        jQuery("#plugin-head").hide();
        jQuery("#plugin-privacy").hide();
        jQuery("#plugin-login").hide();
        jQuery("#plugin-security").hide();
        jQuery("#plugin-update").hide();
        jQuery("#plugin-mail").hide();
        jQuery("#plugin-hf").hide();
        jQuery("#plugin-maintenance").hide();
        jQuery("#plugin-post-page").hide();
        jQuery("#plugin-comments").hide();
        jQuery("#plugin-minify").hide();
        jQuery("#plugin-dashborad").show();
        jQuery("#plugin-email-test").hide();
        jQuery("#plugin-update-lock").hide();
 
    });

});

jQuery(document).ready(function($) {
    $('.header_logo_upload').click(function(e) {
        e.preventDefault();

        var custom_uploader = wp.media({
            title: 'Custom Image',
            button: {
                text: 'Upload Image'
            },
            multiple: false  // Set this to true to allow multiple files to be selected
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('.header_logo').attr('src', attachment.url);
            $('.header_logo_url').val(attachment.url);

        })
        .open();
    });
    $('.login_bg_upload').click(function(e) {
        e.preventDefault();

        var custom_uploader = wp.media({
            title: 'Custom Image',
            button: {
                text: 'Upload Image'
            },
            multiple: false  // Set this to true to allow multiple files to be selected
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('.login_bg').attr('src', attachment.url);
            $('.login_bg_url').val(attachment.url);

        })
        .open();
    });
});