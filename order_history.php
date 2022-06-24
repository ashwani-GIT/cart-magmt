<?php

include_once 'common.php';
include_once 'db.php';

$user_id = $_SESSION['user']['id'];
//here get the list of prodcts from db
$sql2 = "select  * from `order` as odr_tbl inner join order_items on odr_tbl.id = order_items.order_id where user_id =$user_id ";
$r3 = mysqli_query($conn, $sql2);
$grand_total = 0;
?>
<!doctype html>
<html lang="en">

<head>
    <title>Cart</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>History</h1>
        <table class="table" border="1">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $c = 1;

                while ($row = mysqli_fetch_assoc($r3)) {
                    $item_name = $row['item_name'];
                    $item_price = $row['price'];
                    $item_total = $row['amount'];
                    $item_qty = $row['qty'];
                    $grand_total += $item_total;

                    $item_varients = json_decode($row['item_variation'], 1);
                    $item_varient = '';

                    foreach ($item_varients as $key => $val) {
                        $item_varient = "$key : $val <br>";
                    }
                    $cart_item_id = $row['id'];
                    $addtocartbutton = '';

                ?>

                    <tr>
                        <td><?= $item_name ?><br><?= $item_varient ?></td>
                        <td><?= $item_price ?></td>
                        <td><?= $item_total ?></td>

                    </tr>
                <?php
                    $c++;
                }
                ?>
            </tbody>
        </table>

        <div>
            <table>
                <thead>
                    <tr>
                        <th>Total </th>
                        <td> <?= $grand_total ?></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> <button type="button" class="btn btn-primary"><a href="place_order.php">checkout</a></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->

</body>

</html>