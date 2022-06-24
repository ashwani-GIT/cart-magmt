<?php
include_once 'common.php';
include_once 'db.php';

//here get the list of prodcts from db
$sql = 'select * from products as p inner join product_variation as pv on p.id = pv.product_id';
$r = mysqli_query($conn, $sql);

?>
<!doctype html>
<html lang="en">

<head>
    <title>Products</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <table class="table" border="1">
            <thead>
                <tr>
                    <th>Sno.</th>
                    <th>Item Name</th>
                    <th>Varient</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $c = 1;

                $loginbutton = '<button type="button" class="btn btn-primary"><a href="login.php">login<a/></button>';

                while ($row = mysqli_fetch_assoc($r)) {
                    $item_name = $row['name'];
                    $item_price = $row['price'];
                    $item_qty = $row['qty'];
                    $item_varients = json_decode($row['varients'], 1);
                    $item_varient = '';
                    foreach ($item_varients as $key => $val) {
                        $item_varient = "$key : $val <br>";
                    }
                    $addtocartbutton = '';

                ?>

                    <tr>
                        <td scope="row"><?= $c; ?></td>
                        <td><?= $item_name ?></td>
                        <td><?= $item_varient ?></td>
                        <td><?= $item_price ?></td>
                        <td><?= $item_qty ?></td>
                        <td>
                            <?php
                            if (isUserLogin()) {
                                $form_id = "addtocart_form_$c";
                            ?>
                                <form action="addtocart.php" id="<?= $form_id ?>" >
                                    <input type="hidden" name="name" value="<?=$item_name ?>" class="d-none hidden"/>
                                    <input type="hidden" name="variation_id" value="<?= $row['id'] ?>" class="d-none hidden"/>
                                    <input name="product_id" value="<?= $row['product_id'] ?>" class="d-none hidden"/>
                                    <select name="qty" class="form-control">
                                        <?php
                                            for($i=1;$i<=$item_qty;$i++){
                                                echo "<option value='$i'>$i</option>";
                                            }
                                        ?>
                                    </select>
                                </form>
                                <button type="button" onclick="addtocart('<?= $form_id ?>');" class="btn btn-primary">Addtocart</button>
                            <?php
                            } else {
                                echo $loginbutton;
                            }
                            ?>


                        </td>

                    </tr>
                <?php
                    $c++;
                }
                ?>
            </tbody>
        </table>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
            function addtocart(formid) {
                var formelm = "#"+formid;
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
                            alert('added into cart success');
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