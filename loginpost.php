<?php
include_once 'common.php';
include_once 'db.php';

$username = trim($_POST['username']);
$pass = trim($_POST['password']);

$response  = [
    'status' => 'error',
    'msg' => 'Username and password not exist'
];
//here get the user if username and password exist
$username = mysqli_real_escape_string($conn, $username);
$pass = mysqli_real_escape_string($conn, $pass);
$sql = "select * from users where username='$username' and password='$pass'";
$r = mysqli_query($conn, $sql);

if (mysqli_num_rows($r) > 0) {
    $data = mysqli_fetch_assoc($r);
    $response  = [
        'status' => 'success',
        'msg' => 'login success'
    ];
    $_SESSION['user'] = ['id'=>$data['id'],'name'=>$data['username']];
}

echo json_encode($response);
exit;
