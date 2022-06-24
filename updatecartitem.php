<?php
include_once 'common.php';
include_once 'db.php';

$cart_item_id = $_POST['cart_item_id'];
$product_qty = $_POST['qty'];

$cart_item_id = mysqli_real_escape_string($conn, $cart_item_id);
$product_qty = mysqli_real_escape_string($conn, $product_qty);


$response  = [
    'status' => 'error',
    'msg' => 'not added'
];
$date = date('Y-m-d H:i:s');
$user_id = $_SESSION['user']['id'];
// is cart alreay exist


function iscartitemexist($user_id, $cart_item_id, $conn)
{
    // check alredy into cart than update
    $sql2 = "select  * from cart inner join cart_items on cart.id = cart_items.cart_id where cart_items.id = '$cart_item_id' and user_id =$user_id ";
    $r3 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($r3) > 0) {
        $row = mysqli_fetch_assoc($r3);
        return $row;
    }
    return false;
}

$cart_items = iscartitemexist($user_id, $cart_item_id, $conn);
if ($cart_items) {
    $new_qty = $product_qty;
    $amount = $cart_items['price'] * $new_qty;
    //here update qty
    $isql2 = "Update cart_items set qty = $new_qty , amount=$amount where id=$cart_item_id ";
    $r3 = mysqli_query($conn, $isql2);
    $response  = [
        'status' => 'success',
        'msg' => 'updated'
    ];
}
echo json_encode($response);
exit;
