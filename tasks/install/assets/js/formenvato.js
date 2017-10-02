$(document).ready(function() {

  // Forgotten password open / close
  $('.forgotten-password-link').click(function() {
    $('.forgotten-password-box').slideToggle('slow');
    $('.error-box').slideUp('slow');
    $('#ipt-fp-email').val(''); 
  });
  
  // Validation Sign in
  
  $("#sign-in-form").submit(function() {

    var value_login = $("#ipt-login").val();
    var value_password = $("#ipt-password").val();
    
    // If login is email
    /*
     var login_values = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

    // Email format validation    
       if (!login_values.test(value_login)) {
         $('#ipt-login').addClass('ipt-error');      
         $('.error-box').slideDown('slow').removeClass('green').addClass('red');
         $(".error-message").text("Please, fill your correct email.");
         return false;
    }
    */

    // Everything is all right
    if (value_login != "" && value_password != "")
    {
      $('#ipt-login').removeClass('ipt-error');
      $('#ipt-password').removeClass('ipt-error');
      $('.error-box').slideUp('slow');
      return true;
    }
    
    // If its not ok
    else {
      // If login isn't ok
      if (value_login == "")
      {
        $('#ipt-login').addClass('ipt-error');      
        $('.error-box').slideDown('slow').removeClass('green').addClass('red');
        $(".error-message").text("Incorrect login or password.");
      }
      
      // If login is ok but password not
      else if (value_login != "")
      {
        $('#ipt-login').removeClass('ipt-error');      
      }
      
      // If password isn't ok
      if (value_password == "")
      {
        $('#ipt-password').addClass('ipt-error');      
        $('.error-box').slideDown('slow').removeClass('green').addClass('red');
        $(".error-message").text("Incorrect login or password.");
      }
      
      // If password is ok but login not
      else if (value_password != "")
      {
        $('#ipt-password').removeClass('ipt-error');      
      }

      return false;
    }

   });
   
   // Validation Registration
  
  $("#registration-form").submit(function() {
    
    var value_login = $("#ipt-login").val();
    var value_email = $("#ipt-email").val();
    var value_password = $("#ipt-password").val();
    var value_repassword = $("#ipt-repassword").val();
    
    // Email format validation
    
    var email_values = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        
    if (value_login != "")
    { $('#ipt-login').removeClass('ipt-error'); }
    if (value_email != "")
    { $('#ipt-email').removeClass('ipt-error'); }
    if (value_password != "")
    { $('#ipt-password').removeClass('ipt-error'); }
    
    if (!email_values.test(value_email)) {
       $('#ipt-email').addClass('ipt-error');      
       $('.error-box').slideDown('slow').removeClass('green').addClass('red');
       $(".error-message").text("Please, fill your correct email.");
       return false;
    }
    
    // Everything is all right
    if (value_login != "" && value_email != "" && value_password != "" && value_repassword == value_password && ($('#tac-checkbox:checked').val() !== undefined))
    {
      $('#ipt-login').removeClass('ipt-error');
      $('#ipt-email').removeClass('ipt-error');
      $('#ipt-password').removeClass('ipt-error');
      $('#ipt-repassword').removeClass('ipt-error');
      $('.error-box').slideUp('slow');
      return true;
    }
    
    // If its not ok
    else {
      // If login isn't ok
      if (value_login == "")
      {
        $('#ipt-login').addClass('ipt-error');      
        $('.error-box').slideDown('slow').removeClass('green').addClass('red');
        $(".error-message").text("Please, fill all informations.");
      }
      
      // If login is ok
      else if (value_login != "")
      {
        $('#ipt-login').removeClass('ipt-error');      
      }
      
      // If email isn't ok
      if (value_email == "")
      {
        $('#ipt-email').addClass('ipt-error');      
        $('.error-box').slideDown('slow').removeClass('green').addClass('red');
        $(".error-message").text("Please, fill all informations.");
      }
      
      // If email is ok
      else if (value_email != "")
      {
        $('#ipt-email').removeClass('ipt-error');      
      }
      
      // If password isn't ok
      if (value_password == "")
      {
        $('#ipt-password').addClass('ipt-error');      
        $('.error-box').slideDown('slow').removeClass('green').addClass('red');
        $(".error-message").text("Please, fill all informations.");
      }
      
      // If password is ok
      else if (value_password != "")
      {
        $('#ipt-password').removeClass('ipt-error');      
      }
      
      // If repassword isn't same
      if (value_password != value_repassword)
      {
        $('#ipt-repassword').addClass('ipt-error');      
        $('.error-box').slideDown('slow').removeClass('green').addClass('red');
        $(".error-message").text("Retyped password doesn't match.");
      }
      
      // If repassword is ok
      else if (value_password == value_repassword)
      {
        $('#ipt-repassword').removeClass('ipt-error');      
      }
      
      if (($('#tac-checkbox:checked').val() == undefined) && value_login != "" && value_email != "" && value_password != "" && value_repassword == value_password)
      {
        $('.error-box').slideDown('slow');
        $(".error-message").text("You have to agree with terms and conditions.");
      }

      return false;
    }

   });
   
   // Validation Forgotten Password
  
  $("#forgotten-password-form").submit(function() {

    var email_values = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var value_email = $("#ipt-fp-email").val();
    
    // Email format validation    
    if (!email_values.test(value_email)) {
      $('#ipt-fp-email').addClass('ipt-error');      
      $('.error-box').slideDown('slow').removeClass('green').addClass('red');
      // Change message
      $(".error-message").text("Please, fill your correct email.");
      return false;
    }
    
    // Everything is all right
    if ((value_email != "") && (email_values.test(value_email)))
    {
      $('#ipt-fp-email').removeClass('ipt-error');
      $('.forgotten-password-box').slideUp('slow');
      $('.error-box').removeClass('red').addClass('green').slideDown('slow');
      // Change message
      $(".error-message").text("We have successfully sent the password reset email.");
      return true;
    }
    
    // If its not ok
    else {
      // If email isn't ok
      var value_email = $("#ipt-fp-email").val();
      if (value_email == "")
      {
        $('#ipt-fp-email').addClass('ipt-error');      
        $('.error-box').slideDown('slow').removeClass('green').addClass('red');
        $(".error-message").text("Please, fill your email.");
      }
      
      else if (email_values.test(value_email)) {
        $('.forgotten-password-box').slideUp('slow');
        $('.error-box').slideDown('slow');
        $(".error-message").text("We have successfully sent the password reset email.");
      }
      
      // If email is ok
      else if (value_email != "")
      {
        $('#ipt-fp-email').removeClass('ipt-error');
        $('.error-box').slideUp('slow');
        return true;      
      }
      
      return false;
    }

   });

});