<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="admin/assets/img/icon.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
      integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
        .cstm {
            height: 100vh;
        }

        .card-cstm {
            width: 400px;
        }

        img {
            width: 40px;
        }

        .container-alert{
            background: #F1DEDD;
            font-family: verdana;
            text-align: center;
            color: grey;
            font-size: 15px;
            border-radius: 3px;
            padding: 5px;
        }
    </style>

</head>

<body>
<div class="container d-flex justify-content-center align-items-center cstm">
    <div class="row">
        <?php
            if (isset($_GET['aksi'])){
                if ($_GET['aksi']=='login'){
                    session_start();
                    include 'admin/config.php'; 
                    include 'admin/functions.php';

            $user = $_POST['user'];
            $pass = $_POST['pass'];

            $row = $db->get_row("SELECT * FROM tb_user WHERE user='$user' AND pass='$pass'");
            if ($row) {
                $_SESSION['index'] =  $row->user;
                header("location:admin/index.php");
            } else {
                header("location:index.php?pesan=gagal");
            } 
        }
    }
    ?>
    <div class="card p-3 card-cstm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Login</h4>
            <img src="admin/assets/img/icon.png"></img>
        </div>
        <?php 
        if(isset($_GET['pesan'])){
            if($_GET['pesan'] == "gagal"){
                echo "<div class='bg-danger text-white text-center p-2 rounded-2 mb-3'>Username dan Password Salah</div>";
            }
        }
        ?>
        <form class="form-signin" action="index.php?aksi=login" method="post">
            <div class="form-group mb-2">
                <input type="text" class="form-control" placeholder="Username" name="user" autofocus />
            </div>
            <div class="form-group mb-2">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pass" />
            </div>
            <button class="btn btn-primary float-end" type="submit"><span class="glyphicon glyphicon-log-in"></span> Masuk</button>
        </form>
    </div>
    </div>
</div>
</body>
</html>
