
<div class="row nav" style="margin-top: 1%;">

                <div class="nav-item col-sm-2" style="margin-top: 1%;">
                    <a href="manager_home.php"><b>Home</b></a>
                </div>
                <div class="nav-item col-sm-2" style="margin-top: 1%;">
                    <a href="register.php"><b>Add User</b></a>
                </div>
                <div class="form col-sm-4">
                    <form action="search.php" method="get">
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
                <div class="nav-item col-sm-2" style="text-align: right; margin-top: 1%;">
                    <a href="reports.php"><b>Reports</b></a>
                </div>
                <div class="nav-item col-sm-2" style="text-align: right; margin-top: 1%;">
                    <a href="index.php?action=false"><b>Log Out</b></a>
                </div>
            </div>

            <!--
            <div style="text-align: center; margin-top: 2%;">
                <h2>Liquor Store Inventory</h2>
            </div>
            -->
            <div class="row justify-content-around" style="margin-top: 6%;">
                <div class="col-sm-offset-1 col-sm-3">
                    <div class="card card-border">
                        <div class="card-header">
                            <h4>Top Selling</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item cardLists li-form">

                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $bestSelling[0]['name']; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $bestSelling[0]['name']; ?></button>
                                </form>
                            </li>
                            <li class="list-group-item cardLists li-form">
                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $bestSelling[1]['name']; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $bestSelling[1]['name']; ?></button>
                                </form>
                            </li>
                            <li class="list-group-item cardLists li-form">
                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $bestSelling[2]['name']; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $bestSelling[2]['name']; ?></button>
                                </form>

                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-offset-1 col-sm-3">

                    <div class="card card-border">
                        <div class="card-header">
                            <h4>Low Inventory</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item cardLists li-form">

                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $lowInventory[0]; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $lowInventory[0]; ?></button>
                                </form>
                            </li>
                            <li class="list-group-item cardLists li-form">
                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $lowInventory[1]; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $lowInventory[1]; ?></button>
                                </form>
                            </li>
                            <li class="list-group-item cardLists li-form">
                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $lowInventory[2]; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $lowInventory[2]; ?></button>
                                </form>

                            </li>
                        </ul>
                    </div>
                </div>
                <div class=" col-sm-offset-1 col-sm-3">

                    <div class="card card-border">
                        <div class="card-header">
                            <h4>Highest Profit</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item cardLists li-form" data-total-profit="<?php echo $highestProfit[0]['totalProfit']; ?>">
                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $highestProfit[0]['name']; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $highestProfit[0]['name']; ?></button>
                                </form>

                            </li>
                            <li class="list-group-item cardLists li-form" data-total-profit="<?php echo $highestProfit[1]['totalProfit']; ?>">
                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $highestProfit[1]['name']; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $highestProfit[1]['name']; ?></button>
                                </form>
                            </li>
                            <li class="list-group-item cardLists li-form" data-total-profit="<?php echo $highestProfit[2]['totalProfit']; ?>">
                                <form action="search.php" method="get">
                                    <input type="hidden" name="action" value="search"/>
                                    <input class="form-control" type="hidden" name="fieldValue" value="<?php echo $highestProfit[2]['name']; ?>" placeholder="Search Inventory" aria-label="Search">
                                    <button type="submit" class="reg-btn display"><?php echo $highestProfit[2]['name']; ?></button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
