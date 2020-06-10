<?php
include_once __DIR__ . "/Model/includes/functions.php";
include __DIR__ . '/Model/model_inventory.php';

session_start();

if (isset($_SESSION["usertype"])) {
    if ($_SESSION["usertype"] == "admin") {

        //Gets the most recent week from the sales and increments
        $salesWeek = getWeekSale(); //Most recent week in sales table
        $currentWeek = getWeek(); //Most recent week in purchasing table
        if (($salesWeek['week'] === $currentWeek['week'])&&$salesWeek['week']!=NULL) {//Increases the current week only if there is already sales for the week
            $currentWeek['week'] ++;
        }
        else{
            $currentWeek['week']=0;
        }

        //Post request moved to the top to fix the lack of inventory refresh after post bug
        if (isPostRequest()) {
            $action = filter_input(INPUT_POST, 'action');               //Checks if the POST is for the adding an Item
            if ($action === 'addItem') {
                $itemName = filter_input(INPUT_POST, 'itemName');
                $unitCost = filter_input(INPUT_POST, 'unitCost');
                $salesPrice = filter_input(INPUT_POST, 'salesPrice');
                $parAmount = filter_input(INPUT_POST, 'parAmount');
                addItem($itemName, $unitCost, $parAmount, $salesPrice);
            } else if ($action === 'purchaseItem') {
                $count = count($_SESSION["itemId"]);
                if ($count > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $answer = purchaseItem($_SESSION["itemId"][$i], $_SESSION["unitPrice"][$i], $_SESSION["purchaseAmount"][$i], $currentWeek['week']);
                    }
                }
                //Resets the SESSION variables
                $_SESSION["itemId"] = array();
                $_SESSION["unitPrice"] = array();
                $_SESSION["purchaseAmount"] = array();
                //The page wasn't reloading, So bellow is a workaround
                header('Location: ../InventoryManager/manager_home.php');
            } else if ($action === 'editItem') {
                $itemId = filter_input(INPUT_POST, 'idEdit');
                $itemName = filter_input(INPUT_POST, 'itemNameEdit');
                $amount = filter_input(INPUT_POST, 'amountEdit');
                $unitCost = filter_input(INPUT_POST, 'unitCostEdit');
                $salesPrice = filter_input(INPUT_POST, 'salesPriceEdit');
                $parAmount = filter_input(INPUT_POST, 'parAmountEdit');
                updateItem($itemId, $itemName, $amount, $unitCost, $salesPrice, $parAmount);
                header('Location: ../InventoryManager/manager_home.php');
            } else if ($action === 'delItem') {
                $itemId = filter_input(INPUT_POST, 'idDel');
                deleteItem($itemId);
            }
        }
        
        if($currentWeek['week']>0){
            $inventory = getInventoryOrderedLow(); //Pull ordered Inventory
            //Setting the Cards
            //
            //Set Low inventory card
            $lowInventory = array();
            for ($i = 0; $i < 3; $i++) {
                if ($inventory[$i]['orderAmount'] < 100) {
                    array_push($lowInventory, $inventory[$i]['name']);
                } else {
                    array_push($lowInventory, ''); //adds empty string to array incase less than three items are low
                }
            }
            //Sets the best selling Card
            $bestSelling = getBestSellingLastWeek($salesWeek['week']); //Pulls the most beers sold in the most recent week ordered by most sold
            $count = count($bestSelling); //Pulls back 1 if 1
            if (count($bestSelling) < 3) {
                $count;
                $bestSelling += [ $count => ['name' => ""]];
                $count++;
                $bestSelling += [ $count => ['name' => ""]];
            }
            //Sets the Highest Profit Card
            $highestProfit = getHighestProfitLastWeek($salesWeek['week']);
            $count = count($highestProfit);

            if($count<3){

                $count;
                $highestProfit += [ $count => ['name' => "", 'totalProfit' => ""]];
                $count++;
                $highestProfit += [ $count => ['name' => "", 'totalProfit' => ""]];
            }
        }
    }
    else{

        header('Location: ../InventoryManager/index.php');
    }
} else {
    header('Location: ../InventoryManager/index.php');
}

/* if($_SESSION['submit'] == 'Register'){
  header('Location: register.php');
  } */
?>

<html lang="en">
    <head>
        <title>Log In</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css">
        <link rel="stylesheet" href="Design/design.css">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script type="text/javascript" src="Model/modal.js"></script>
        <script type="text/javascript" src="Model/sort.js"></script>

    </head>
    <body>

        <?php include __DIR__ . '/Model/navbar.php'; ?>
        <div class="container">

            <?php
            if($currentWeek['week']!=NULL){
                include __DIR__ . '/Model/topCards.php';
            }
            ?>

            <div class="row justify-content-between" style="margin-top: 4%;">
                <div class="col-3">
                    <h3 style="margin-bottom: 0%;">Inventory</h3>
                </div>
                <div id="orderDiv" class="col-3 btn-lg" style="padding:0%; height:100%; display:none;">
                    <button class="d-block m-auto" type="button" id="orderBtn" style="color:#5380b7; border-color: #5380b7; border-radius: 7%; background-color: white;" onclick="confirmOrder()">Purchase</button>
                </div>
                <div class="col-3" style="text-align: right;">

                    <!--Add Button-->
                    <button type="button" id="addBtn" class="btn-lg fas fa-plus" style="color:#5380b7; border-color: #5380b7; background-color: white;" onclick="addFunction()"></button>

                    <div id="addModal" class="modal">

                        <div class="modal-content">
                            <div>
                                <span class="close">&times;</span>
                            </div>
                            <form action="../InventoryManager/manager_home.php" method="post">
                                <div class="modal-body container-fluid">
                                    <div class="form-group">
                                        <div class="form-row">
                                            <input type="hidden" name="action" value ="addItem">
                                            <label class="control-label" for="itemName">Item Name:</label>
                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="itemName" placeholder="Enter Item Name" name="itemName" >
                                            <div class="invalid-feedback">Please type your User Name.</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label class="control-label" for="unitCost">Unit Cost:</label>
                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="unitCost" placeholder='Enter Unit Cost example: 4.50' name="unitCost" >
                                            <div class="invalid-feedback">Please enter a unit price. Only use numbers and one decimal point</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label class="control-label" for="salesPrice">Sales Price:</label>
                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="salesPrice" placeholder="Enter Sales Price example: 7.50" name="salesPrice" >
                                            <div class="invalid-feedback">Please enter a sales price. Only use numbers and one decimal point</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label class="control-label" for="parAmount">Par Amount:</label>
                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="parAmount" placeholder="Enter Par Amount example: 24" name="parAmount" >
                                            <div class="invalid-feedback">Please enter your Par Amount as a whole number.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="submit" class="btn btn-success" onclick='return checkData()' value="Add Item" id="submitAdd">
                                    <script type="text/javascript" src="Model/addItemModal.js"></script>
                                </div>
                            </form>
                        </div>

                    </div>

                    <!--Purchase Button-->
                    <button type="button" id="purchaseBtn" class="btn-lg fas fa-shopping-cart" style="color:#5380b7; border-color: #5380b7; background-color: white;" onclick="orderFunction()"></button>

                    <!--Delete Button-->
                    <button type="button" id="deleteBtn" class="btn-lg fas fa-trash-alt" style="color:#5380b7; border-color: #5380b7; background-color: white;" onclick="deleteFunction()"></button>
                </div>
            </div>
            <?php
            if($currentWeek['week']>0){
                include __DIR__ . '/Model/inventoryDisplay.php';
            }
            ?>


            <script>
                        //deleteFunction Script
                        $(".delBtn").click(function () {
                id = $(this).data('idItem');
                        name = $(this).data("name");
                        //Sets the modal info with the current info
                        $("#idDel").val(id);
                        $("#nameDel").html(name);
                });            </script>
            <script>
/*function sortBy(n){
    var table, i, x, y, count = 0;

    table = document.getElementById("invTable");
    var switching = true;
    
    var direction = "ascending";
    
    while (switching) {
        switching = false;
        var rows = table.rows;
        
        for (i = 1; i < (rows.length - 1); i++) {
            var Switch = false;
            
            x = rows[i].cells[n];
            y = rows[i + 1].cells[n];
            
            // Check the direction of order 
            if (direction == "ascending") { 
 
                // Check if 2 rows need to be switched 
                if (x.innerHTML > y.innerHTML) 
                    { 
                        // If yes, mark Switch as needed and break loop 
                        Switch = true; 
                        break; 
                    } 
            } else if (direction == "descending") { 
  
                // Check direction 
                if (x.innerHTML < y.innerHTML) 
                    { 
                        // If yes, mark Switch as needed and break loop 
                        Switch = true; 
                        break; 
                    } 
            }         
        }
        if(Switch) {
            rows[i].parentNode.insertBefore(rows[i+1], rows[i]);
            for (i = 1; i < rows.length - 1; i++) {


                 x = rows[i].cells[n];
                 y = rows[i + 1].cells[n];




                 if (x.innerHTML > y.innerHTML)
                 {
                    // If yes, mark Switch as needed and break loop
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);


                 
                    for (var j = i - 1; j < i; j--) {
                       var newX = rows[i].cells[n];
                       var newY = rows[j].cells[n];

                       if (newX.innerHTML > newY.innerHTML && j > 0) {
                           rows[j].parentNode.insertBefore(rows[i], rows[j]);
                           
                       }else{
                           break;
                       }
                    }
                    



                 }
            }
            switching = true;
            
            // Increase count for each switch 
            count++; 
        }else { 
             // Run while loop again for descending order 
                if (count == 0 && direction == "ascending") { 
                    direction = "descending"; 
                    switching = true; 
                } 
           } 
    }
}*/

        /*function sortBy(n) {
            var table, i, x, y, count = 0;

            table = document.getElementById("invTable");


            var rows = table.rows;


            for (i = 1; i < rows.length - 1; i++) {


                 x = rows[i].cells[n];
                 y = rows[i + 1].cells[n];

                 document.getElementById("showMe").innerHTML = x.innerHTML;
                 document.getElementById("showMe2").innerHTML = y.innerHTML;


                 if (x.innerHTML > y.innerHTML)
                 {
                    // If yes, mark Switch as needed and break loop
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);

                    document.getElementById("showMe3").innerHTML = rows[i].cells[n].innerHTML;
                    document.getElementById("showMe4").innerHTML = rows[i -1].cells[n].innerHTML;
                 
                    for (var j = i - 1; j < i; j--) {
                       var newX = rows[i].cells[n];
                       var newY = rows[j].cells[n];

                       if (newX.innerHTML > newY.innerHTML && j > 0) {
                           rows[j].parentNode.insertBefore(rows[i], rows[j]);
                           
                       }else{
                           break;
                       }
                    }
                    

                    document.getElementById("showMe5").innerHTML = newX.innerHTML;
                    document.getElementById("showMe6").innerHTML = newY.innerHTML;

                 }
            }
        }*/
            </script>
        </div>
    </body>
</html>

