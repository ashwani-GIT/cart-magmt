<?php

include_once 'common.php';
include_once 'db.php';

$user_id = $_SESSION['user']['id'];
//here get the list of prodcts from db
$sql2 = "select  * from cart inner join cart_items on cart.id = cart_items.cart_id where user_id =$user_id and active=1";
$r3 = mysqli_query($conn, $sql2);
$grand_total = 0;
if(mysqli_num_rows($r3)<=0){
header('location:index.php');
}
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
        <h1>Shopping Cart</h1>
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
                        <td>
                            <?php
                            $form_id = "updatecartitem_form_$c";
                            ?>
                            <form action="updatecartitem.php" id="<?= $form_id ?>">
                                <input type="hidden" name="varient_id" value="<?= $row['varient_id'] ?>" class="d-none hidden" />
                                <input type="hidden" name="cart_item_id" value="<?= $cart_item_id ?>" class="d-none hidden" />
                                <input name="product_id" value="<?= $row['product_id'] ?>" class="d-none hidden" />
                                <input name="qty" type="number" value="<?= $item_qty ?>" />

                            </form>
                            <button type="button" onclick="removecartitem('<?= $cart_item_id ?>');" class="btn btn-secondary">Remove</button>
                            <button type="button" onclick="updatecartitem('<?= $form_id ?>');" class="btn btn-primary">Update</button>


                        </td>

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
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
            function updatecartitem(formid) {
                var formelm = "#" + formid;
                event.preventDefault();
                var action = $(formelm).attr('action');
                var formdata = $(formelm).serialize();
                $.ajax({
                    url: action,
                    data: formdata,
                    method: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            alert('updated');
                        } else {
                            alert(response.msg);
                        }
                    }
                });
                return false;
            }

            function removecartitem(cart_item_id) {
                event.preventDefault();
                var action = 'removeitem.php'
                $.ajax({
                    url: action,
                    data: {
                        cart_item_id: cart_item_id
                    },
                    method: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            alert('removed from cart');
                        } else {
                            alert(response.msg);
                        }
                    }
                });
                return false;
            }
        </script>
</body>

</html>