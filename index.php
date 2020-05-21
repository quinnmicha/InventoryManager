<?php

include_once __DIR__. "/Model/includes/functions.php";
include __DIR__ . '/Model/model_inventory.php';

session_start();

if( isset($_SESSION["login"])){
    $action=  filter_input(INPUT_GET, 'action');
    if( $action=='false'){
        session_unset();
        session_destroy();
    }
    
    else if($_SESSION['usertype']==='admin') {
        header("Location: ../InventoryManager/manager_home.php");
    }
    else if($_SESSION['usertype']==='user'){
        header("Location: ../InventoryManager/user_home.php");
    }
}
else if(isPostRequest()){
    $user = filter_input(INPUT_POST, 'username');
    $pass = filter_input(INPUT_POST, 'password');
    $login = login($user, $pass);
    if($login!=false){
        
        $_SESSION['login'] = true;
        $_SESSION['username'] = $login[0]['username'];
        $_SESSION['usertype'] = $login[0]['group'];
        $_SESSION['userId'] = $login[0]['idUser'];
        //These Session arrays are used for Purchasing, Editing, Deleting
        $_SESSION["itemId"] = array();
        $_SESSION["unitPrice"] = array();
        $_SESSION["purchaseAmount"] = array();
        $_SESSION['editItem']=array();
        header("Location: ../InventoryManager/manager_home.php");
    }
}

?>
<html lang="en">
<head>
  <title>Log In</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="Design/design.css">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script type="text/javascript" src="Model/validation.js"></script>
</head>

<body>
    
    <?php include __DIR__.'/model/navbar.php';?>
    <div class="container">
    <div class="row justify-content-center">
        <h2 style="margin-top: 15%; margin-bottom: 2%">Welcome to the Inventory Management System</h2>
        <h3 style="margin-bottom: 4%">The Only Inventory You'll Ever Need !</h3>
    </div>
    <div class="row justify-content-center">
        <form action="index.php" method="post" class="col-sm-6">
            <?php
                if(isPostRequest())
                    {
                        echo 
                        '<div style="width:70%; margin:auto;">
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Login Failed</strong> check your spelling
                            </div>
                        </div>';
                    } 
            ?>
            <div class="form-group">
                <label class="contorl-label" for="username">User Name:</label>
                <input type="text" class="form-control login" style="border-color: #5380b7;" id="username" placeholder="Enter User Name" name="username" >
                <div class="invalid-feedback">Please type your User Name.</div>
            </div>              
            <div class="form-group">
                <label class="control-label" for="password">Password:</label>        
                <input type="password" class="form-control login" style="border-color: #5380b7;" id="password" placeholder="Enter Password" name="password" >
                <div class="invalid-feedback">Please type your Password.</div>
            </div>
            <div class="row justify-content-center">        
                <div style="padding-top: 2%">
                    <button type="submit" name="submit" value="Login" class=" btn btn-default btn-lg" style="border-color: #5380b7; color: #5380b7;">Login</button>
                </div>
            </div>    
        </form>
    </div>

            
        </form>
    </div>
        
   
</div>
</body>
</html>
