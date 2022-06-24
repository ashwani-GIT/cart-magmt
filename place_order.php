<?php
include_once 'common.php';
include_once 'db.php';

$response  = [
    'status' => 'error',
    'msg' => 'not added'
];
$date = date('Y-m-d H:i:s');
$user_id = $_SESSION['user']['id'];
$cart_id = $_SESSION['cart']['id'];
// is cart alreay exist


function setcartinactive($user_id, $cart_id, $conn)
{
    $sql2 = "Update cart set active = 0  where id=$cart_id and user_id=$user_id ";
    mysqli_query($conn, $sql2);
    if (mysqli_affected_rows($conn) > 0) {
        return true;
    }
    return false;
}

$sql2 = "select  * from cart inner join cart_items on cart.id = cart_items.cart_id where user_id =$user_id and active=1 ";
$r3 = mysqli_query($conn, $sql2);

$c = 0;
$order_id = '';
while ($row = mysqli_fetch_assoc($r3)) {
    if($c==0){
        $cart_id = $row['cart_id'];
        $isql = "Insert into `order` (`item_count`, `cart_id`, `created_at`,`user_id`) VALUES(0,$cart_id,'$date',$user_id)";
        $r = mysqli_query($conn2, $isql);
        $order_id = mysqli_insert_id($conn2);
    }
    $c++;
    $product_name = $row['item_name'];
    $price = $row['price'];
    $amount = $row['amount'];
    $product_qty = $row['qty'];
    $variation_id = $row['varient_id'];
    $variation = $row['item_variation'];
    $product_id = $row['product_id'];

    $isql2 = "Insert into order_items (`order_id`,`product_id` ,`varient_id`, `item_name`, `item_variation`,`price`,`qty`,`amount`) VALUES($order_id,$product_id,$variation_id,'$product_name','$variation',$price,$product_qty,$amount)";
    mysqli_query($conn2, $isql2);
    $response  = [
        'status' => 'success',
        'msg' => 'order'
    ];
}

setcartinactive($user_id,$cart_id,$conn2);

 
echo json_encode($response);
exit;
