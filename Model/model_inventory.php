<?php
    include('db.php');
    
    //Takes in user and pass, hashes pass
    //Pulls back idUser, username, and group for SESSION
    //Comes back false if fail
    function login($user, $pass) {
        global $db;
        $pass = sha1($pass);
        $stmt = $db->prepare("SELECT idUser, username, `group` FROM login_inventory WHERE username = :user && password = :pass");   
        
        $binds = array(
                    ":user" => $user,
                    ":pass" => $pass
                );
 
        $results = array();
        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ($results);
        }
        else{
            return false;
        }
    }
    
    //Used to validate registration
    // so that no two accounts can have the same username
    function getUserNames(){
        global $db;
        
        $stmt=$db->prepare("SELECT username FROM login_inventory");
        
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ($results);
        }
        else{
            return false;
        }
    }
    
    //registers user after js confirms it is correct
    //returns false if fails or true if success
    function register($user, $pass, $group){
        global $db;
        $Users= array();                        //
        $Users = getUserNames();                // Checks if the username supplied is already registered
        foreach($Users as $u){                  // If it is then the function fails
            if(in_array($user, $u, true)){      //
                                                //
            return 0;                           //
            }
        }
        
        $pass = sha1($pass);
        $stmt= $db->prepare("INSERT INTO login_inventory (username, password, `group`) VALUES (:user, :pass, :group)");
        
        $binds = array(
            ":user" => $user,
            ":pass" => $pass,
            ":group" => $group
        );
        
        if ($stmt->execute($binds) && $stmt->rowCount() >0){
            return 1;
        }
    }
    
    //Adds an item to the inventory table
    //Sets amount to 0 automatically
    function addItem($itemName, $unitPrice, $parNumber, $salesPrice){
        global $db;
        $success=false;
        $stmt=$db->prepare("INSERT INTO inventory (name, amount, unitPrice, salesPrice, parAmount) Values (:itemName, 0, :unitPrice, :salesPrice, :parNumber)");
        
        $binds = array(
            ":itemName" => $itemName,
            ":unitPrice" => $unitPrice,
            ":parNumber" => $parNumber,
            ":salesPrice"=>$salesPrice
        );
        
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $success=true;
        }
        return($success);
    }
    
    //Pulls the id name amount price par from inventory table
    function getInventory(){
        global $db;
        
        $stmt=$db->prepare("SELECT idItem, `name`, amount, unitPrice, salesPrice, parAmount FROM inventory");
        
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ($results);
        }
        else{
            return false;
        }
    }
    
    //Orders the inventory by lowest quantity first
    function getInventoryOrderedLow(){
        global $db;
        
        $stmt=$db->prepare("SELECT idItem, `name`, amount, unitPrice, salesPrice, parAmount, (amount / parAmount) AS orderAmount FROM inventory ORDER BY orderAmount ASC;");
        
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ($results);
        }
        else{
            return false;
        }
    }
    
    //Searches thorugh the inventory
    function searchInventory ($searchValue) {
        global $db;
        
      
      //$stmt = $db->prepare("SELECT * FROM schools WHERE $column LIKE :search");

           
      
        $results = [];
        $stmt = $db->prepare("SELECT idItem, `name`, amount, unitPrice, salesPrice, parAmount FROM inventory WHERE name LIKE :search");
        $search = '%'.$searchValue.'%';
        $binds = array(
              ":search" => $search
        );

        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

         }

         return ($results);
  }
	
	//Pulls recs on a single idItem from inventory
    function getItem($id){
        global $db;
        
        $stmt=$db->prepare("SELECT idItem, `name`, amount, unitPrice, salesPrice, parAmount FROM inventory WHERE idItem = :id");
        
		$binds= array(
			":id"=>$id
		);
		
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ($results);
        }
        else{
            return false;
        }
    }
    
    //Gives abiltity to update every part of an item
    function updateItem($idItem, $name, $amount, $unitPrice, $salesPrice, $parAmount){
        global $db;
        
        $stmt=$db->prepare("UPDATE inventory SET name = :name, amount = :amount, unitPrice = :unitPrice, salesPrice = :salesPrice, parAmount = :parAmount WHERE idItem = :idItem");
        
        $binds = array(
            ":name" => $name,
            ":unitPrice" => $unitPrice,
            ":salesPrice" => $salesPrice,
            ":parAmount" => $parAmount,
            ":amount" => $amount,
            ":idItem" => $idItem
        );
        
        $result=false;
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $result=true;
        }
        return $result;
    }
    
    //Updates item's amount in the inventory table
    //Is called within other functions
    function updateItemAmount($idItem, $amount){
        global $db;
        
        $stmt=$db->prepare("UPDATE inventory SET amount = :amount WHERE idItem = :idItem");
        
        $binds = array(
            ":amount" => $amount,
            ":idItem" => $idItem
        );
        
        $result=false;
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $result=true;
        }
        return $result;
    }
    
    //Deletes item from the inventory table
    function deleteItem($idItem){
        global $db;
        
        $stmt=$db->prepare("DELETE FROM inventory WHERE idItem = :idItem");
        
        $binds = array(
            ":idItem" => $idItem
        );
        $result = false;
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $result = true;
        }
        return $result;
    }
    
    //Will return the most recent week from the purchasing table
    //Must be adjusted on the website to increase weeks for purchasing
    //Returns an array of one int (the max week in the purchasing table)
    //Returns 1 if no purchases
    //Returns null if db doesn't connect
    function getWeek(){
        global $db;
        
        $stmt=$db->prepare("SELECT MAX(week) AS 'week' from purchases");
        
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($results)){
                $results = ['week' => 0];
            }
            return $results;
        }
        else{
            return null;
        }
    }
    
    //Gets all weeks for Reports page Dropdown
    function getWeeksAll(){
        global $db;
        $stmt=$db->prepare("SELECT DISTINCT week FROM purchases ORDER BY week");
        if($stmt->execute() && $stmt->rowCount()>0){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            return NULL;
        }
    }
    
    function getWeekSale(){
        global $db;
        
        $stmt=$db->prepare("SELECT MAX(week) AS 'week' from sales");
        
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($results)){
                $results = ['week' => 0];
            }
            return $results;
        }
        else{
            return null;
        }
    }
    
    //Pulls the amount of a certain item for adding or subtracting
    //may not need this function....
    function getAmount($idItem){
        global $db;
        
        $stmt=$db->prepare("SELECT amount FROM inventory WHERE idItem = :idItem");
        
        $binds= array(
            ":idItem"=> $idItem
        );
        
        $results=[];
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            return $results;
        }
        else{
            return false;
        }
        
    }
    
    /*
    ## getRecentSaleId returns an array ##
    $recentSale= getRecentSaleId();
    $test =getRecentSale($recentSale['idSale']);  << must access value like this
    var_dump($test);
     */
    //Pulls the most recent Sale id for invoice table
    function getRecentSaleId(){
        global $db;
        
        $stmt=$db->prepare("SELECT MAX(idSale) AS 'idSale' from sales"); //<<sets column name from MAX(idSale) to idSale
        
        $results=[];
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else{
            $results = false;
        }
        return $results;
    }
    
    
    //Pulls sales data off idSale from sales table
    function getRecentSale($idSale){
        global $db;
        
        $stmt=$db->prepare("SELECT * FROM sales WHERE idSale = :idSale");
        
        $binds = array(
            ":idSale"=>$idSale
        );
        
        $results=[];
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else{
            $results = false;
        }
        return $results;
    }
    
    //Pulls most recent purchase id for connecting the invoice table
    function getRecentPurchaseId(){
        global $db;
        
        $stmt=$db->prepare("SELECT MAX(idPurchase)AS idPurchase from purchases");
        
        $results=[];
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else{
            $results = false;
        }
        return $results;
    }
    
    //Purchases ITEM
    //adds item to purchases table
    //also updates inventory and invoice table
    //returns 1 if true 0 if false
    function purchaseItem($idItem, $unitCost, $amount, $week){ //Seems to be no add() so maybe pull the new amount from the website or call an updateItemAmount()
        global $db;
        
        $money = $amount * $unitCost;
        $stmt=$db->prepare("INSERT INTO purchases (week, idItem, amount, money) VALUES (:week, :idItem, :amount, :money)");
        
        $binds= array (
            ":week" => $week,
            ":idItem" => $idItem,
            ":amount" => $amount,
            ":money" => $money
        );
        
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            //Runs functions to keep the other dataTables working
            addExpense($week, $money);
            $invAmount = getAmount($idItem);
            $netAmount = $invAmount['amount'] + $amount;
            return updateItemAmount($idItem, $netAmount);//returns true if successful
            
        }
        else{
            return false;
        }
    }
    
    function sellItem($idItem, $unitCost, $amount, $week, $idUser){
        global $db;
        
        $money = $amount * $unitCost;
        
        $stmt=$db->prepare("INSERT INTO sales (week, idUser, idItem, amount, money) VALUES (:week, :idUser, :idItem, :amount, :money)");
        
        $binds= array (
            ":week" => $week,
            ":idItem" => $idItem,
            ":amount" => $amount,
            ":idUser" => $idUser,
            ":money" => $money
        );
        
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            //Runs functions to keep the other dataTables working
            
            addIncome($week, $money);
            $invAmount = getAmount($idItem);
            $netAmount = $invAmount['amount'] - $amount;
            return updateItemAmount($idItem, $netAmount);//returns true if successful
        }
        else{
            return false;
        }
    }
    
    //Adds income to the invoices table
    //Pulls the Recent idSale and then adds rect to the invoices table
    function addIncome($week, $money){
        global $db;
        
        $get = getRecentSaleId();
        $idSale=$get['idSale'];
        $stmt =$db->prepare("INSERT INTO invoices (week, revenue, idSale) VALUES (:week, :revenue, :idSale)");
        
        $binds= array(
            ":week"=> $week,
            ":revenue"=> $money,
            ":idSale"=>$idSale
        );
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            return true;
        }
        else{
            return false;
        }
    }
    
    //Adds expense to the invoices table
    //Pulls the Recent idPurchase and then adds rect to the invoices table
    function addExpense($week, $money){
        global $db;
        
        $get = getRecentPurchaseId();
        $idPurchase=$get['idPurchase'];
        $stmt =$db->prepare("INSERT INTO invoices (week, expense, idPurchase) VALUES (:week, :expense, :idPurchase)");
        
        $binds= array(
            ":week"=> $week,
            ":expense"=> $money,
            ":idPurchase"=>$idPurchase
        );
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            return true;
        }
        else{
            return false;
        }
    }
    
    //Returns the most sold beers for the most recent sales week
    function getBestSellingLastWeek($week){
        global $db;
        
        $stmt=$db->prepare("SELECT week, sales.idItem, inventory.`name`, SUM(sales.amount) AS totalAmount FROM sales INNER JOIN inventory ON sales.idItem=inventory.idItem WHERE `week` = :week GROUP BY sales.idItem ORDER BY totalAmount DESC;");
        
        $binds = array(
            ":week" => $week
        );
        
        $results=[];
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
    }
    
    //Pulls total money and total profit from the sales week
    function getHighestProfitLastWeek($week){
        global $db;
        
        $stmt=$db->prepare("SELECT inventory.`name`, SUM(sales.money) AS totalMoney, SUM(sales.money) - (inventory.unitPrice * SUM(sales.amount)) AS totalProfit  FROM sales INNER JOIN inventory ON sales.idItem=inventory.idItem WHERE `week` = :week GROUP BY sales.idItem ORDER BY totalProfit DESC;");
        
        $binds = array(
            ":week" => $week
        );
        
        $results=[];
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $results;
    }
    
    //Pulls the most recent week from purchases table
    //Returns the profit of that week in an array
    //Can be modulated to get Profit of anyweek
    function getProfitLastWeek(){
        global $db;
        
        $get = getWeek();//Pulls most recent week from purchasing table
        $week = $get['week'];
        
        $stmt=$db->prepare("SELECT SUM(revenue) - SUM(expense) AS 'profit' FROM invoices WHERE week = :week");
        
        $binds=array(
            ":week"=>$week
        );
        $results= false;
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $results;
    }
    
    //Pulls the Sum of expenses and revenue for graph
    function getReportLastWeek(){
        global $db;
        
        $get = getWeekSale();//Pulls most recent week from purchasing table
        $week = $get['week'];
        
        $stmt=$db->prepare("SELECT week, SUM(expense) AS 'expense', SUM(revenue) AS 'revenue' FROM invoices WHERE week = :week");
        
        $binds=array(
            ":week"=>$week
        );
        $results= false;
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $results;
    }
    function getReportByWeek($week){
        global $db;
        
        $stmt=$db->prepare("SELECT week, SUM(expense) AS 'expense', SUM(revenue) AS 'revenue' FROM invoices WHERE week = :week");
        
        $binds=array(
            ":week"=>$week
        );
        $results= false;
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $results;
    }
    
    function getReportInventory($week){
        global $db;
        $results = false;
        if ($week==="YTD"){
            $stmt=$db->prepare("SELECT `name`, bought, sold, (IFNULL(revenue, 0) - IFNULL(expense, 0)) AS totalProfit FROM (SELECT salesTable.`name`, IFNULL(SUM(sold), 0) AS sold, IFNULL(SUM(bought), 0) AS bought, expense, revenue FROM (SELECT `name`, SUM(sales.amount) AS sold, SUM(money) AS revenue FROM sales JOIN inventory ON sales.idItem = inventory.idItem GROUP BY sales.idItem) AS salesTable LEFT JOIN (SELECT `name`, SUM(purchases.amount) AS bought, SUM(purchases.money) AS expense FROM purchases JOIN inventory ON purchases.idItem = inventory.idItem GROUP BY purchases.idItem ) AS purchasesTable ON salesTable.`name` = purchasesTable.`name` GROUP BY `name` UNION ALL SELECT purchasesTable.`name`, IFNULL(sold, 0) AS sold, IFNULL(bought, 0) AS bought, expense, revenue FROM (SELECT `name`, SUM(sales.amount) AS sold, SUM(money) AS revenue FROM sales JOIN inventory ON sales.idItem = inventory.idItem GROUP By sales.idItem) AS salesTable RIGHT JOIN (SELECT `name`, SUM(purchases.amount) AS bought, SUM(purchases.money) AS expense FROM purchases JOIN inventory ON purchases.idItem = inventory.idItem GROUP BY purchases.idItem) AS purchasesTable ON salesTable.`name` = purchasesTable.`name` GROUP BY `name`) AS reportsTable GROUP BY `name`;");
        
            if($stmt->execute() && $stmt->rowCount()>0){
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            }
        }
        else{
            $stmt=$db->prepare("SELECT `name`, bought, sold, `week`, (IFNULL(revenue, 0) - IFNULL(expense, 0)) AS totalProfit FROM (SELECT salesTable.`name`, IFNULL(SUM(sold), 0) AS sold, IFNULL(SUM(bought), 0) AS bought, salesTable.`week`, expense, revenue  FROM (SELECT `name`, SUM(sales.amount) AS sold, `week`, SUM(money) AS revenue FROM sales JOIN inventory ON sales.idItem = inventory.idItem WHERE week= :week1 GROUP BY sales.idItem) AS salesTable LEFT JOIN (SELECT `name`, SUM(purchases.amount) AS bought, `week`, SUM(purchases.money) AS expense FROM purchases JOIN inventory ON purchases.idItem = inventory.idItem WHERE week= :week2 GROUP BY purchases.idItem ) AS purchasesTable ON salesTable.`name` = purchasesTable.`name` GROUP BY `name` UNION ALL SELECT purchasesTable.`name`, IFNULL(sold, 0) AS sold, IFNULL(bought, 0) AS bought, purchasesTable.`week`, expense, revenue FROM (SELECT `name`, SUM(sales.amount) AS sold, `week`, SUM(money) AS revenue FROM sales JOIN inventory ON sales.idItem = inventory.idItem WHERE week= :week3 GROUP By sales.idItem) AS salesTable RIGHT JOIN (SELECT `name`, SUM(purchases.amount) AS bought, `week`, SUM(purchases.money) AS expense FROM purchases JOIN inventory ON purchases.idItem = inventory.idItem WHERE week= :week4 GROUP BY purchases.idItem) AS purchasesTable ON salesTable.`name` = purchasesTable.`name` GROUP BY `name`) AS reportsTable GROUP BY `name`;");
            
            $binds = array(//Since week is referred to 4 times within the SQL statement, the binds needed to reflect that
                ":week1"=>$week,
                ":week2"=>$week,
                ":week3"=>$week,
                ":week4"=>$week
            );
            if($stmt->execute($binds) && $stmt->rowCount()>0){
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            }
            else{//If there is no sales data for the week, this SQL catches that and gives just purchase data
                $results = getReportInventoryPurchaseOnly($week);
                return $results;
            }
        }
        return $results;  
    }
    
    function getReportInventoryPurchaseOnly($week){
        global $db;
        
        $stmt=$db->prepare("SELECT inventory.`name`, SUM(purchases.amount) AS purchased, 0 AS sold, 0 -  (inventory.unitPrice * SUM(purchases.amount))  AS TotalProfit  FROM purchases INNER JOIN inventory ON purchases.idItem=inventory.idItem WHERE purchases.week = :week GROUP BY inventory.name ORDER BY totalProfit DESC;");
                
        $binds = array(
            ":week"=>$week
        );
        if($stmt->execute($binds) && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
    }
    
    function getProfitByWeek(){
        global $db;
        
        $stmt=$db->prepare("SELECT week, IFNULL(SUM(revenue), 0) - IFNULL(SUM(expense), 0) AS 'profit' FROM invoices GROUP BY week");
        
        $results= false;
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $results;
    }
    
    //Returns the Year to date profit/loss
    function getProfitYTD(){
        global $db;
        
        $stmt=$db->prepare("SELECT SUM(revenue) - SUM(expense) AS 'profit' FROM invoices");
        
        $results=false;
        if($stmt->execute() && $stmt->rowCount()>0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $results;
    }
    
   

    
?>