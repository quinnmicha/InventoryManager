<?php
include __DIR__ . '/Model/model_inventory.php';


session_start();

if($_SESSION["graphWeek"]==="YTD"){
    $profitData = getProfitByWeek();
    $results[0] = $_SESSION["graphType"]; // graphType 
    $results[1] = array(); // week
    $results[2] = array(); // profit

    foreach ($profitData as $v) {
        array_push($results[1], $v['week']);
        array_push($results[2], $v['profit']);
    }
    $jsonResults= json_encode($results);
    echo $jsonResults;
}
    
else{
    $profitData = getReportByWeek($_SESSION["graphWeek"]);
    $results[0] = $_SESSION["graphType"]; //graph type
    $results[1] = array(); // expense 
    $results[2] = array(); // revenue
    $results[3] = array(); // week selected

    array_push($results[1], number_format($profitData['expense'], 2, '.', ''));
    array_push($results[2], number_format($profitData['revenue'], 2, '.', ''));
    array_push($results[3], $profitData['week']);
    $jsonResults= json_encode($results);
    echo $jsonResults;
}
?>