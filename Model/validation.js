//window.addEventListener("load", init);
$(document).ready(function() {
    //Checks if input is correct when user clicks out of the text box
    $("input[type=text]").blur( function() {
        if($(this).val()===""){
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');
        }
        else{
            $(this).addClass('is-valid');
            $(this).removeClass('is-invalid');
        }
      });
    $("#confirm_password").blur(function() {
        if(($(this).val()===$("#password").val()) && ($(this).val()!="")){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    //Login Validation
    $(".login").blur(function(){
        if($(this).val()===""){
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');
        }
        else{
            $(this).addClass('is-valid');
            $(this).removeClass('is-invalid');
        }
    });

});

//Returns true and sends to php if everything is valid
function checkData(){
    var errorCheck = 0;
    if($("#username").val()===""){
        $("#username").addClass('is-invalid');
        $("#username").removeClass('is-valid');
        errorCheck++;
    }
    if($("#password").val()===""){
        $("#password").addClass('is-invalid');
        $("#password").removeClass('is-valid');
        errorCheck++;
    }
    if($("#confirm_password").val()!=$("#password").val() || $("#confirm_password").val()===""){
        $("#confirm_password").addClass('is-invalid');
        $("#confirm_password").removeClass('is-valid');
        console.log('tripped up here');
        errorCheck++;
      }
    console.log('working');
  if(errorCheck>0){
    return false;
    }
    else{ return true; }
}