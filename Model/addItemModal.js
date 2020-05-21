$(document).ready(function(){
    $("#itemName").blur(function() {
        if($(this).val()!=""){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    $("#unitCost").blur(function() {
        if((parseFloat($(this).val())>=parseFloat('0.01')) && ($(this).val()!="")){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    $("#salesPrice").blur(function() {
        if((parseFloat($(this).val())>=parseFloat('0.01')) && ($(this).val()!="")){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    $("#parAmount").blur(function() {
        if((parseInt($(this).val())>=parseInt('1')) && ($(this).val()!="")){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    $("#itemNameEdit").blur(function() {
        if($(this).val()!=""){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    $("#amountEdit").blur(function() {
        if((parseFloat($(this).val())>=parseFloat('0.01')) && ($(this).val()!="")){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    $("#unitCostEdit").blur(function() {
        if((parseFloat($(this).val())>=parseFloat('0.01')) && ($(this).val()!="")){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    $("#salesPriceEdit").blur(function() {
        if((parseInt($(this).val())>=parseInt('1')) && ($(this).val()!="")){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    $("#parAmountEdit").blur(function() {
        if((parseInt($(this).val())>=parseInt('1')) && ($(this).val()!="")){
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }
        else{
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
    });
    
});


//Returns true if validated
function checkDataEdit(){
    var errorCheck = 0;
    if($("#itemNameEdit").val()===""){
        errorCheck++;
    }
    if((parseFloat($("#unitCostEdit").val())>=parseFloat('0.01')) && ($("#unitCostEdit").val()!="")){}
    else{
        errorCheck++;
    }
    if((parseFloat($("#salesPriceEdit").val())>=parseFloat('0.01')) && ($("#salesPriceEdit").val()!="")){}
    else{
        errorCheck++;
    }
    if((parseFloat($("#parAmountEdit").val())>=parseFloat('0.01')) && ($("#parAmountEdit").val()!="")){}
    else{
        errorCheck++;
    }
    if((parseFloat($("#amountEdit").val())>=parseFloat('0.01')) && ($("#parAmountEdit").val()!="")){}
    else{
        errorCheck++;
    }
    
    if(errorCheck>0){
        return false;
        }
    else{ return true; }
  }
  
  //Returns true if validated
function checkData(){
    var errorCheck = 0;
    if($("#itemName").val()===""){
        errorCheck++;
    }
    if((parseFloat($("#unitCost").val())>=parseFloat('0.01')) && ($("#unitCost").val()!="")){}
    else{
        errorCheck++;
    }
    if((parseFloat($("#salesPrice").val())>=parseFloat('0.01')) && ($("#salesPrice").val()!="")){}
    else{
        errorCheck++;
    }
    if((parseFloat($("#parAmount").val())>=parseFloat('0.01')) && ($("#parAmount").val()!="")){}
    else{
        errorCheck++;
    }
    
    if(errorCheck>0){
        return false;
        }
    else{ return true; }
  }


