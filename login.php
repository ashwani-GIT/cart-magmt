<?php
include_once 'common.php';
if(isUserLogin()){
    header("location:index.php");
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <form id="login" action="loginpost.php" method="post" onsubmit="return false;">
            <div class="form-group">
                <label for="">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="">
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="">
            </div>
            <button type="submit" onclick="loginSubmit();" class="btn btn-primary">Submit</button>
        </form>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function loginSubmit() {
            var formelm = '#login';
            event.preventDefault();
            var action = $(formelm).attr('action');
            var formdata = $(formelm).serialize();
            $.ajax({
                url: action,
                data: formdata,
                method:'post',
                dataType: 'json',
                success: function(response) {
                    if(response.status == 'success'){
                        alert('login success');
                    }else{
                        alert(response.msg);
                    }
                    

                }
            });
            return false;
        }
    </script>
</body>

</html>