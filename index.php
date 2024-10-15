<!DOCTYPE html>
<html>
<head>
    <title>Sistem Pendukung Keputusan</title>
	<link rel="icon" href="assets/img/icon.png" />
    <style>
        .body{
            background: #e4e9f7;
        }

        .container{
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
        }
        
        .box{
            background: #fdfdfd;
            display: flex;
            flex-direction: column;
            padding: 25px 25px;
            border-radius: 20px;
            box-shadow: 0 0 128px 0 rgba(0,0,0,0.1),
                        0 32px 64px -48px rgba(0,0,0,0.5);
        }

        .form-box{
            width: 275px;
        }

        .form-box header{
            font-family: verdana;
            font-size: 25px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #e6e6e6;
            margin-bottom: 10px;
        }

        .form-signin{
            display: flex;
            margin-bottom: 10px;
            flex-direction: column;
        }

        .form-group{
            margin-bottom: 12px;
        }

        .form-control{
            height: 30px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }

        .btn{
            height: 35px;
            background: #337AB7;
            border: 0;
            border-radius: 5px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
            transition-duration: 0.4s;
        }

        .btn1{
            background: #337AB7;
        }
        
        .btn1:hover{
            background: #2A6693;
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
<div class="container">
    <div class="box form-box">
        <?php
            if (isset($_GET['aksi'])){
                if ($_GET['aksi']=='login'){
                    session_start();
                    include 'config.php'; 
                    include 'functions.php';

            $user = $_POST['user'];
            $pass = $_POST['pass'];

            $row = $db->get_row("SELECT * FROM tb_user WHERE user='$user' AND pass='$pass'");
            if ($row) {
                $_SESSION['index'] =  $row->user;
                header("location:header.php");
            } else {
                header("location:header.php?pesan=gagal");
            } 
        }
    }
    ?>
    <div>
        <header>Login<img src="assets/img/icon.png" style="float: right; width:15%"></img></header>
    </div>
        <form class="form-signin" action="index.php?aksi=login" method="post">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" name="user" autofocus />
            </div>
            <div class="form-group">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pass" />
            </div>
            <button class="btn btn1" type="submit"><span class="glyphicon glyphicon-log-in"></span> Masuk</button>
        </form>
        <?php 
        if(isset($_GET['pesan'])){
            if($_GET['pesan'] == "gagal"){
                echo "<div class='container-alert'>Username dan Password Salah</div>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
