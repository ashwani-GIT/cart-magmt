<?php
include_once 'common.php';
include_once 'db.php';

$variation_id = $_POST['variation_id'];
$product_id = $_POST['product_id'];
$product_name = $_POST['name'];

$product_qty = $_POST['qty'];

$variation_id = mysqli_real_escape_string($conn, $variation_id);
$product_id = mysqli_real_escape_string($conn, $product_id);
$product_name = mysqli_real_escape_string($conn, $product_name);
$product_qty = mysqli_real_escape_string($conn, $product_qty);


$response  = [
    'status' => 'error',
    'msg' => 'not added'
];
$date = date('Y-m-d H:i:s');
$user_id = $_SESSION['user']['id'];
// is cart alreay exist


function recalculateCart($user_id, $conn)
{
}

function iscartexist($user_id, $conn)
{
    $sql2 = "select id from cart where user_id =$user_id and active=1 ";
    $r3 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($r3) > 0) {
        $row = mysqli_fetch_assoc($r3);
        return $row['id'];
        return true;
    }
    return false;
}

function product_variation($variation_id, $conn)
{
    $sql = "select * from product_variation where id=$variation_id";
    $r1 = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($r1);
    return $row;
}

function iscartitemexist($user_id, $variation_id, $product_id, $conn)
{
    // check alredy into cart than update
    $sql2 = "select  * from cart inner join cart_items on cart.id = cart_items.cart_id where varient_id = '$variation_id' and product_id='$product_id' and user_id =$user_id ";
    $r3 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($r3) > 0) {
        $row = mysqli_fetch_assoc($r3);
        return $row;
    }
    return false;
}

$cart_id = iscartexist($user_id, $conn);
if (!$cart_id) {
    $isql = "Insert into cart (`item_count`, `active`, `created_at`,`user_id`) VALUES(0,1,'$date',$user_id)";
    $r = mysqli_query($conn, $isql);
    $cart_id = mysqli_insert_id($conn);
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = ['id' => $cart_id, 'count' => 0];
}

$cart_items = iscartitemexist($user_id, $variation_id, $product_id, $conn);
if ($cart_items) {
    $cart_item_id = $cart_items['id'];
    $new_qty = $cart_items['qty'] + $product_qty;
    $amount = $cart_items['price'] * $new_qty;
    //here update qty
    $isql2 = "Update cart_items set qty = $new_qty , amount=$amount where id=$cart_item_id ";
    $r3 = mysqli_query($conn, $isql2);
    $response  = [
        'status' => 'success',
        'msg' => 'added'
    ];
} else {
    $sql = "select * from product_variation where id=$variation_id";
    $r1 = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($r1);
    $variation = $row['varients'];
    $price = $row['price'];
    $amount = $price * $product_qty;
    //here insert into the cart_items
    $isql2 = "Insert into cart_items (`cart_id`,`product_id` ,`varient_id`, `item_name`, `item_variation`,`price`,`qty`,`amount`) VALUES($cart_id,$product_id,$variation_id,'$product_name','$variation',$price,$product_qty,$amount)";
    $r3 = mysqli_query($conn, $isql2);
    $response  = [
        'status' => 'success',
        'msg' => 'added'
    ];
}
echo json_encode($response);
exit;
