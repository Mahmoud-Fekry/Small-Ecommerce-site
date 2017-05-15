/*global $, jQuery, alert, confirm*/

$(function () {

    'use strict';
    
    // Define all variable



    
    // Trigger Select Box plugin
    
    $("select").selectBoxIt({
        
        autoWidth: false
    });
    
    
    //Hide placeholder on form fouce
    
    $('[placeholder]').focus(function () {
        
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    
    //Confirm deleting any user
    
    $('.confirm').click(function () {
        
        return confirm('Are you sure about deleting !!');
    });


    // Switch between login and sign-up form

    $('.login-page h1 span').click(function () {

        $(this).addClass('active').siblings().removeClass('active');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(50);
    });

    // display ads preview durin creation

    $(".live").keyup(function () {

      $($(this).data('class')).text($(this).val());

    });

   
});