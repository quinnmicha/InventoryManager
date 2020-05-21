<?php
include_once __DIR__ . "/Model/includes/functions.php";
include __DIR__ . '/Model/model_inventory.php';

session_start();

if (isset($_SESSION["usertype"])) {
    if ($_SESSION["usertype"] == "user") {
        if (isPostRequest()) {
            $currentWeek = getWeek(); //Pulls the most recent purchasing week and sets the week to that
            $action = filter_input(INPUT_POST, 'action');               //Checks if the POST is for the adding an Item
            if ($action === 'sellItem') {
                $count = count($_SESSION["itemId"]);
                if ($count > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $answer = sellItem($_SESSION["itemId"][$i], $_SESSION["unitPrice"][$i], $_SESSION["purchaseAmount"][$i], $currentWeek['week'], $_SESSION['userId']);
                    }
                }
                $_SESSION["itemId"] = array();
                $_SESSION["unitPrice"] = array();
                $_SESSION["purchaseAmount"] = array();
            }
        }
        $inventory = getInventory();
    } else {
        header('Location: ../InventoryManager/index.php');
    }
} else {
    header('Location: ../InventoryManager/index.php');
}
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

    </head>
    <body>

<?php include __DIR__ . '/model/navbar.php'; ?>    
        <div class="container">

            <div class="row nav" style="margin-top: 1%;">

                <div class="nav-item col-sm-4" style="margin-top: 1%;">
                    <a href="manager_home.php"></a>
                </div>
                <div class="form col-sm-4">
                    <form action="userSearch.php" method="get">
                        <input type="hidden" name="action" value="search" />
                        <div class="form-row">
                            <div class="col-9">
                                <input class="form-control" type="text" name="fieldValue" placeholder="Search Inventory" aria-label="Search">
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-success" style="border-color: #5380b7; color: #5380b7; background-color: white;" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="nav-item col-sm-4" style="text-align: right; margin-top: 1%;">
                    <a  href="index.php?action=false"><b>Log Out</b></a>
                </div>      
            </div>


            <div style="text-align: center; margin-top: 4%;">
                <h2>Liquor Store Inventory</h2>
            </div>



            <div class="row" style="margin-top: 4%;">
                <div class="col-6">

                    <!--Purchase Button-->
                    <button type="button" id="purchaseBtn" class="btn-lg" style="color:#5380b7; border-color: #5380b7; background-color: white;" onclick="orderFunction()">Sell Items</button>         

                </div>

                <div id="orderDiv" class="col-6 btn-lg" style="padding:0%; height:100%; display:none; text-align: right;">
                    <button class="btn-lg" type="button" id="orderBtn" style="color:#5380b7; border-color: #5380b7; border-radius: 7%; background-color: white;" onclick="confirmSale()">Confirm</button>
                </div>

            </div>
<?php
$searchValue = "";
$order = "";
//$result = [];
$action = filter_input(INPUT_GET, 'action');

if ($action === 'search') {


    $searchValue = filter_input(INPUT_GET, 'fieldValue');


    $result = searchInventory($searchValue);
    ?>
                <div class="row" style="margin-top: 2%;">
                    <table class="table" id="invTable">
                        <thead class="thead-light-blue">
                            <tr>
                                <th style="text-align: center; display:none;">ID</th>
                                <th></th>
                                <th>Name</th>
                                <th>Unit Price</th>
                                <th>Sales Price</th>
                                <th>Par Amount</th>
                                <th>Current Amount</th>
                                <th id="numSelectTh">
                                    Sell Amount
                                </th>
                                <th id="delSelectTh">

                                </th>
                            </tr>
                        </thead>
                        <tbody>


    <?php foreach ($result as $row): ?>
                                <tr>
                                    <td style="display:none;"><?php echo $row['idItem'] ?></td>
                                    <td><input type="hidden" name="i-d" value="<?php echo $row['idItem'] ?>" /></td>
                                    <td style="text-align: left;"><?php echo $row['name'] ?></td>                                                 
                                    <td>$<?php echo number_format($row['unitPrice'], 2) ?></td>
                                    <td>$<?php echo number_format($row['salesPrice'], 2) ?></td>
                                    <td><?php echo$row['parAmount'] ?></td>
                                    <td><?php echo$row['amount'] ?></td>
                                    <td class="numSelectTd" style="text-align: center;">
                                        <input class="d-block m-auto" type="number" id="quantity" data-id-item="<?php echo $row['idItem'] ?>" data-name="<?php echo $row['name'] ?>" data-unit-price="<?php echo number_format($row['salesPrice'], 2); //This is read as 'unitPrice' but is supposed to be salesPrice !this is not a mistake  ?>" data-current-amount="<?php echo$row['amount'] ?>" name="quantity" min="1" max="25"><span style="color:red;" class="validation"></span>


                                        <div id="confirmSaleModal" class="modal">

                                            <div class="modal-content">

                                                <form action="user_home.php" method="POST">
                                                    <input type="hidden" name="action" value ="sellItem">
                                                    <table class="table" id="invTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Sell</th>
                                                                <th>Price</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="saleConfirmOutput">

                                                        </tbody>

                                                    </table>
                                                    <div style="text-align: center;">
                                                        <div>
                                                            <button type="submit">Confirm</button>
                                                            <button type='button' onclick='closeSaleModal()'>Cancel</button>
                                                        </div>
                                                    </div>  
                                                </form>  

                                            </div>

                                        </div>
                                    </td>
                                </tr>
    <?php endforeach; ?>
                </table>
            </div>

        </div>
    </body>
</html>

<?php
            
            
            
        }
