<?php

include_once __DIR__. "/Model/includes/functions.php";
include __DIR__ . '/Model/model_inventory.php';

session_start();

if( isset($_SESSION["usertype"])){
    //echo $_SESSION['usertype'];
    if($_SESSION["usertype"]=="admin"){
    }
    else{
        header('Location: ../InventoryManager/index.php');
    }
}
else{
        header('Location: ../InventoryManager/index.php');
    }
if(isPostRequest()){
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $userType = filter_input(INPUT_POST, 'group');
    //echo $userType;
    $answer = register($username, $password, $userType);
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
    <script type="text/javascript" src='../InventoryManager/Model/validation.js'></script>
</head>
<body>
<?php include __DIR__.'/model/navbar.php';?>

<div class="container">
    <div class="row nav" style="margin-top: 1%;">
        
        <div class="nav-item col-sm-4" style="margin-top: 1%;">
            <a href="manager_home.php"><b>Home</b></a>
        </div>
        <div class="form col-sm-4">
            <form>
                <div class="form-row">
                    <div class="col-9">
                        
                    </div>
                    <div class="col-3">
                        
                    </div>
                </div>
            </form>
        </div>
        <div class="nav-item col-sm-4" style="text-align: right; margin-top: 1%;">
            <a  href="index.php?action=false"><b>Log Out</b></a>
        </div>      
    </div>
    <div class="row justify-content-center">
        <h2 style="text-align: center; margin-top: 10%; margin-bottom: 2%">Welcome to the Inventory Management System</h2>
        <h3 style="text-align: center; margin-bottom: 4%">The Only Inventory You'll Ever Need !</h3>
    </div>
    <div class="row justify-content-center">    
        <form class="col-sm-6" action="register.php" method="post">
            
            <?php
                if(isPostRequest()){
                    if($answer === 0){
                    echo '<div style="width:70%; margin:auto;">
                            <div class="alert alert-dismissible alert-danger text-center">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Registration Failed </strong> "' .$username. '" is already registered
                            </div>
                        </div>';
                    }
                    if($answer === 1){
                    echo '<div style="width:70%; margin:auto;">
                            <div class="alert alert-dismissible alert-success text-center">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Success </strong> "' .$username. '" is now registered
                            </div>
                        </div>';
                    }
                }
            ?>
            <div class="form-group">
                
                <label class="control-label" for="user name">User Name:</label>
                          
                    <input type="text" class="form-control login" style="border-color: #5380b7;" id="username" placeholder="Enter User Name" name="username" >
                
            </div>
            <div class="form-group">
                <label class="control-label" for="password">Password:</label>
                          
                    <input type="password" class="form-control login" style="border-color: #5380b7;" id="password" placeholder="Enter Password" name="password" >
                
            </div>
            <div class="form-group">
                <label class="control-label" for="confirm_password">Confirm Password:</label>
                          
                    <input type="password" class="form-control login" style="border-color: #5380b7;" id="confirm_password" placeholder="Confirm Password" name="confirm_password" >
                    <div class="invalid-feedback">
                        Password and Confirm Password must match.
                    </div>
                
            </div>
            <div class="row justify-content-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="group" id="user" value="user" checked>
                    <label class="form-check-label" for="user">
                      Server
                    </label>
                </div>
                <div class="form-check offset-1">
                    <input class="form-check-input" type="radio" name="group" id="admin" value="admin">
                    <label class="form-check-label" for="admin">
                      Manager
                    </label>
                </div>
            </div>
            <div class="row mb-3 justify-content-center">
                    <button type="submit" name="submit" onclick='return checkData()' value="Register" class="btn btn-outline-primary">Sign Up</button>
            </div>    
        </form>
    </div>
</div>
      
      
</body>
</html>    
    
