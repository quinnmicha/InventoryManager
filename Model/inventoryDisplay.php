<div class="row" style="margin-top: 2%;">
                <table class="table" id="invTable">
                    <thead class="thead-light-blue">
                        <tr>
                            <th style="text-align: center; display:none;">ID</th>
                            <th></th>
                            <th class="sort-btn">Name</th>
                            <th class="sort-btn">Unit Price</th>
                            <th class="sort-btn">Sales Price</th>
                            <th class="sort-btn">Par Amount</th>
                            <th class="sort-btn">Current Amount</th>
                            <th id="numSelectTh">Purchase Amount</th>
                            <th id="delSelectTh">

                            </th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php foreach ($inventory as $item): ?>
                            <?php
                            //To set proper row colors
                            $color = ''; //default nothing if amount above par
                            if ($item['amount'] < $item['parAmount']) {
                                if ($item['amount'] === 0) {//This is to catch error
                                    $color = 'table-danger'; //bootstrap background color red
                                } else if (($item['amount'] / $item['parAmount'] * 100) < 50) {//Yellow if 50% or above, Red if bellow 50%
                                    $color = 'table-danger'; //bootstrap background color red
                                } else {
                                    $color = 'table-warning'; //boostrap background color yellow
                                }
                            }
                            ?>
                            <tr id="colorRow" class="<?php echo $color; ?>">
                                <td><input type="hidden" name="i-d" value="<?php echo $item['idItem'] ?>" /></td>
                                <td style="text-align: left;">
                                    <!-- Trigger the modal with a button -->
                                    <button type="button" id="editBtn" class="reg-btn editBtn" data-id-item="<?php echo $item['idItem'] ?>" data-name="<?php echo$item['name'] ?>" data-sales-price="<?php echo number_format($item['salesPrice'], 2); ?>" data-unit-price="<?php echo number_format($item['unitPrice'], 2) ?>" data-current-amount="<?php echo$item['amount'] ?>" data-par-amount="<?php echo$item['parAmount'] ?>" onclick="editFunction()"><?php echo$item['name'] ?></button>
                                    <script>
                                                //editFunction Script
                                                $(".editBtn").click(function () {
                                        id = $(this).data('idItem');
                                                name = $(this).data("name");
                                                unitCost = $(this).data("unitPrice");
                                                salesCost = $(this).data("salesPrice");
                                                currentAmount = $(this).data("currentAmount");
                                                parAmount = $(this).data("parAmount");
                                                console.log($(this).data("name"));
                                                //Sets the modal info with the current info
                                                $("#idEdit").val(id);
                                                $("#itemNameEdit").val(name);
                                                $("#amountEdit").val(currentAmount);
                                                $("#unitCostEdit").val(unitCost);
                                                $("#salesPriceEdit").val(salesCost);
                                                $("#parAmountEdit").val(parAmount);
                                        });                                    </script>
                                    <!-- Modal -->
                                    <div class="modal" id="editModal">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div>
                                                <span class="close">&times;</span>
                                            </div>
                                            <form action="manager_home.php" method="POST">
                                                <div class="modal-body container-fluid">
                                                    <div class="form-group">
                                                        <div class="form-row">
                                                            <input type="hidden" name="action" value ="editItem">
                                                            <input type="hidden" id="idEdit" name="idEdit" value="">
                                                            <label class="control-label" for="itemNameEdit">Item Name:</label>
                                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="itemNameEdit" value=""  name="itemNameEdit" >
                                                            <div class="invalid-feedback">Please type the item's name.</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-row">
                                                            <label class="control-label" for="amountEdit">Current Amount in Inventory:</label>
                                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="amountEdit"  value=""  name="amountEdit" >
                                                            <div class="invalid-feedback">Please enter your Current Inventory Amount as a whole number.</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-row">
                                                            <label class="control-label" for="unitCostEdit">Unit Cost:</label>
                                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="unitCostEdit"  name="unitCostEdit" >
                                                            <div class="invalid-feedback">Please enter a unit price. Only use numbers and one decimal point</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-row">
                                                            <label class="control-label" for="salesPriceEdit">Sales Price:</label>
                                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="salesPriceEdit" name="salesPriceEdit" >
                                                            <div class="invalid-feedback">Please enter a sales price. Only use numbers and one decimal point</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-row">
                                                            <label class="control-label" for="parAmountEdit">Par Amount:</label>
                                                            <input type="text" class="form-control" style="border-color: #5380b7;" id="parAmountEdit" name="parAmountEdit" >
                                                            <div class="invalid-feedback">Please enter your Par Amount as a whole number.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success" onclick='return checkDataEdit()' id="submitEdit">Submit Changes</button>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['unitPrice'], 2) ?></td>
                                <td>$<?php echo number_format($item['salesPrice'], 2) ?></td>
                                <td><?php echo$item['parAmount'] ?></td>
                                <td><?php echo$item['amount'] ?></td>
                                <td class="numSelectTd">
                                    <input class="d-block m-auto" type="number" data-id-item="<?php echo $item['idItem'] ?>" data-name="<?php echo $item['name'] ?>" data-unit-price="<?php echo number_format($item['unitPrice'], 2) ?>" data-current-amount="<?php echo floatval($item['amount']) ?>" id="quantity" name="quantity" min="1" max="25">


                                    <div id="confirmOrderModal" class="modal">

                                        <div class="modal-content">
                                            <form action="manager_home.php" method="POST">
                                                <input type="hidden" name="action" value ="purchaseItem">
                                                <table class="table" id="invTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Purchase</th>
                                                            <th>Cost</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="purchaseConfirmOutput">

                                                    </tbody>

                                                </table>
                                                <div style="text-align: center;">
                                                    <div>
                                                        <button type="submit">Confirm</button>
                                                        <button type='button' onclick='closeOrderModal()'>Cancel</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>

                                    </div>
                                </td>

                                <td class="delSelectTd">
                                    <button type="button" id="delIcon" class="delBtn far fa-trash-alt" style="color:#5380b7; border-color: #5380b7; border-radius: 10%; background-color: white;" data-id-item="<?php echo $item['idItem'] ?>" data-name="<?php echo$item['name'] ?>" onclick="confirmDel()" ></button>

                                    <div id="confirmDelModal" class="modal">

                                        <div class="modal-content">
                                            <div>
                                                <span class="close">&times;</span>
                                            </div>

                                            <form action="manager_home.php" method="POST">
                                                <input type="hidden" name="action" value ="delItem">
                                                <table class="table" id="invTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                    <input type="hidden" id="idDel" name="idDel">
                                                    <td id="nameDel"></td>
                                                    </tr>

                                                    </tbody>

                                                </table>
                                                <div style="text-align: center;">
                                                    <div>
                                                        <button type="submit">Confirm</button>
                                                        <button type='button' onclick='closeDelModal()'>Cancel</button>
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