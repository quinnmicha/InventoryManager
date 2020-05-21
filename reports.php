<?php

include_once __DIR__. "/Model/includes/functions.php";
include __DIR__ . '/Model/model_inventory.php';

session_start();

if( isset($_SESSION["usertype"])){
    if($_SESSION["usertype"]=="admin"){
        //Gets the most recent week from the sales and purchasing table
        $salesWeek = getWeekSale();//Most recent week in sales table
        $purchaseWeek = getWeek();//Most recent week in purchasing table
        if($salesWeek['week']>$purchaseWeek['week']){
            $currentWeek = $salesWeek['week'];
        }
        else{
            $currentWeek = $purchaseWeek['week'];
        }
        
        $allWeeks = getWeeksAll();
        //init session variables for load
        $_SESSION["graphWeek"]= $currentWeek;
        $_SESSION["graphType"]= "bar";
        //
        
        if(isGetRequest()){
            $action = filter_input(INPUT_GET, 'action');               //Checks if the POST is for the adding an Item
            
            if($action === "thisWeek"){
                $_SESSION["graphWeek"]= $currentWeek;
                $_SESSION["graphType"]= "bar";
            }
            else if($action === "lastWeek"){
                $_SESSION["graphWeek"]= $currentWeek - 1;
                
                $_SESSION["graphType"]= "bar";
            }
            else if($action === "YTD"){
                $_SESSION["graphWeek"]= "YTD";
                $_SESSION["graphType"]= "line";
            }
            else if(is_numeric($action)){
                $_SESSION["graphWeek"]= $action;
                $_SESSION["graphType"]= "bar";
            }
        }
        
        $inventory = getReportInventory($_SESSION["graphWeek"]);//pulls report data based on week chosen for graph
        
        
    }
    else{
        header('Location: ../InventoryManager/index.php');
    }
}
else{
        header('Location: ../InventoryManager/index.php');
    }

/*if($_SESSION['submit'] == 'Register'){
        header('Location: register.php');
    }*/
?>

<html lang="en">
<head>
  <title>Reports</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css">
  <link rel="stylesheet" href="Design/design.css">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script type="text/javascript" src="Model/modal.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>

</head>
<body>
    
<?php include __DIR__.'/model/navbar.php';?>    
<div class="container">
    
    <div class="row nav" style="margin-top: 1%;">
        
        <div class="nav-item col-sm-2" style="margin-top: 1%;">
            <a href="manager_home.php"><b>Home</b></a>
        </div>
        <div class="nav-item col-sm-2" style="margin-top: 1%;">
            <a href="register.php"><b>Add User</b></a>
        </div>
        <div class="form col-sm-4">
            <form>
                <div class="form-row">
                    <div class="col-9">
                        <input class="form-control" type="text" placeholder="Search Inventory" aria-label="Search">
                    </div>
                    <div class="col-3">
                        <input class="btn btn-outline-success" style="border-color: #5380b7; color: #5380b7; background-color: white;" type="submit" value="search">
                    </div>
                </div>
            </form>
        </div>
        <div class="nav-item col-sm-2" style="text-align: right; margin-top: 1%;">
            <a href="reports.php"><b>Reports</b></a>
        </div>
        <div class="nav-item col-sm-2" style="text-align: right; margin-top: 1%;">
            <a href="index.php?action=false"><b>Log Out</b></a>
        </div>      
    </div>
    
    
   <!--/header ----------------------------------------------------------------> 
   <div class="row justify-content-center" style="margin-top: 6%;"><h1>Reports</h1></div>
   <div class="row justify-content-start" style="margin-top: 4%;">
       <div class="col-2">
           <form action="reports.php" method="get">
               <input type="hidden" name="action" value="YTD" />
               <button class="btn btn-outline-success" id="YTD" style="border-color: #5380b7; color: #5380b7; background-color: white; width: 100%;" type="submit">YTD</button>
           </form>
       </div>
       <div class="col-2">
           <form action="reports.php" method="get">
               <input type="hidden" name="action" value="thisWeek" />
               <button class="btn btn-outline-success" id="thisWeek" data-week="<?php echo $currentWeek;?>" style="border-color: #5380b7; color: #5380b7; background-color: white; width: 100%;" type="submit">This Week</button>
           </form>
       </div>
       <div class="col-2">        
           <form action="reports.php" method="get">
               <input type="hidden" name="action" value="lastWeek" />
               <button class="btn btn-outline-success" id="lastWeek" style="border-color: #5380b7; color: #5380b7; background-color: white; width: 100%;" type="submit">Last Week</button>
           </form>
       </div>
       <div class="dropdown col-2">
            <button class="btn btn-secondary dropdown-toggle" style="border-color: #5380b7; color: #5380b7; background-color: white; width: 100%;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Choose Week
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?php foreach ($allWeeks AS $w): ?>
                <a class="dropdown-item" href="../InventoryManager/reports.php?action=<?php echo $w['week']; ?>"><?php echo $w['week']; ?></a>
                <?php                endforeach; ?>
            </div>
      </div>
       
       
       
   </div>
   
   
   <div class="graphBox" style="width:100%; height:600px;">
        <canvas class="mt-4" id="graphProfit"></canvas>
   </div>
   
   <div class="row" style="margin-top: 4%;">
        <table class="table" id="invTable">
                <thead class="thead-light-blue">
                    <tr>
                        <th style="text-align: center; display:none;">ID</th>
                        <th></th>
                        <th><button type="button" class="sort-btn" onclick="sortBy(1)">Name</button></th>
                        <th><button type="button" class="sort-btn" onclick="sortBy(2)">Amount Bought</button></th>
                        <th><button type="button" class="sort-btn" onclick="sortBy(3)">Amount Sold</button></th>
                        <th><button type="button" class="sort-btn" onclick="sortBy(4)">Total Profit</button></th>
                    </tr>
                </thead>
                <tbody>


                <?php $totalProfit = 0; 
                foreach ($inventory as $item): ?>
                    <tr id="colorRow" class="">
                        <td></td>
                        <td style="text-align: left;">
                            <?php echo $item['name']; ?>
                        </td>
                        <td><?php echo $item["bought"] ?></td>
                        <td><?php echo $item["sold"] ?></td>
                        <td>$<?php echo number_format($item['totalProfit'], 2); $totalProfit+=$item['totalProfit']; ?></td>
                    </tr>
                <?php endforeach; ?>
                    <tr style="border-top:2px solid black;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total Profit</td>
                        <td class="<?php if($totalProfit>0){ echo 'text-success';} else{echo 'text-danger';}?>">$<?php echo number_format($totalProfit, 2); ?></td>

                    </tr>
            </table>
        </div>
   <script>
     //Show Individual week bar graph
     
     $(document).ready(function(){
         console.log("document is ready");
         $.get("../InventoryManager/ajaxGetGraph.php", function(data){
             console.log("get has started");
            profitData = $.parseJSON(data);
            console.log(profitData);
            console.log("profit data should have posted");
            if(profitData[0]==="line"){
                displayYTD(profitData);
            }
            else{
                displayWeek(profitData)
            }
         });
         console.log("get has been skipped");
     });
     
     
    function displayWeek(){
        var ctx = document.getElementById('graphProfit').getContext('2d');
        var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
               labels: ['expenses', 'revenue'],
               datasets: [
                {
                   label: ['expenses', 'revenue'],
                   data: [profitData[1][0], profitData[2][0]],
                   backgroundColor: ['rgba(240, 41, 41, 0.5)','rgba(77, 240, 41, 0.5)']
                }
                ]
            },
             options: {
                maintainAspectRatio: false,
               legend: { display: false },
               title: {
                 display: true,
                 text: 'Last Week Report: Week ' + profitData[3][0] 
               },
               scales: {
                     yAxes: [{
                         ticks: {
                             beginAtZero:true
                            }
                        }]
                    }
                }
        });
    }
    
    function displayYTD(){
        var week =[];
        var profit =[];
        var color =[];
        totalProfit=0;
        for(i in profitData[1]){
            week.push(profitData[1][i]);
        }
        for(i in profitData[1]){
           //.toFixed kept throwing an error on neitServer
           profit.push(parseFloat(profitData[2][i]).toFixed(2));
           //Bellow is the fix without .toFixed
           //profit.push(profitData[1][i]);
            totalProfit+=parseFloat(profit[i]);
        }
        if (totalProfit>0){
                console.log(profit[i]);
                color.push('rgba(77, 240, 41, 0.5)');
            }
            else{
                color.push('rgba(240, 41, 41, 0.5)');
            }
        var ctx = document.getElementById('graphProfit').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: week,
              datasets: [{
                label: "profit",
                data: profit,
                backgroundColor: color,
               }]
           },
            options: {
                maintainAspectRatio: false,
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Weekly Profit'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                   
                }
            }
        });
    }
      
   </script>
   
   
</div>
</body>
</html>

