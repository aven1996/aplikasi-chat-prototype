<?php 
  // tambahkan koneksi database
  include "koneksi.php";
  session_start();
 
  // validasi status login
  if (isset($_SESSION['akun'])) {
      header("Location: index.php");
  }


  if(isset($_POST['login'])){
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // ambil data username dari database
    $data_db = mysqli_query($conn, "SELECT * FROM akun WHERE username = '$username' ");
    if(mysqli_num_rows($data_db) > 0){
      // cek kebenaran password
      $data_db = mysqli_fetch_assoc($data_db);
      $pass_db = $data_db['password'];
      $id_akun = $data_db['id'];

      if(password_verify($password, $pass_db)){
        session_start();
        $_SESSION['akun'] = $id_akun;
        header("Location: index.php");
      }else{
        echo "<script> alert('Password Salah!'); </script>";
      }
    }else{
      echo "<script> alert('Username Tidak Ditemukan!'); </script>";
    }
  }

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <!-- Font Google Icon-->
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <!-- favicon -->
    <link rel="shortcut icon" href="img/logo/icon.ico" type="image/x-icon">

    <title>CaChat-Alpha</title>

    <!-- style -->
    <style type="text/css">
      *{
        font-family:  'Open Sans', sans-serif;
      }
      .material-icons {
        font-family: 'Material Icons';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;  /* Preferred icon size */
        display: inline-block;
        line-height: 1;
        text-transform: none;
        letter-spacing: normal;
        word-wrap: normal;
        white-space: nowrap;
        direction: ltr;

        /* Support for all WebKit browsers. */
        -webkit-font-smoothing: antialiased;
        /* Support for Safari and Chrome. */
        text-rendering: optimizeLegibility;

        /* Support for Firefox. */
        -moz-osx-font-smoothing: grayscale;

        /* Support for IE. */
        font-feature-settings: 'liga';
      }
      .container.app{
        border: 0px solid black;
        height: 700px;
        margin-top: 70px;
        margin-bottom: 100px;
      }
      
      .cov-form{
        max-width: 450px;
        border-radius: 30px;
        margin: auto;
        box-sizing: border-box;
        overflow: hidden;
        position: relative;
        background: #f5f6fa;
      }
      .img-varian{
        position: absolute;
        width: 250px;
        right: -30px;
        top: -50px;
        filter: opacity(30%);
        animation: rotate 2s ease-out infinite alternate;
      }


      @keyframes rotate{
        from {
          transform: rotate(0deg) scale(1.0);
        }
        to {
          transform: rotate(5deg) scale(1.1);
        }
      }
    </style>

  </head>

  <body style="background-image: url('img/bg-blur1.jpg'); background-size: 1920px;">
    <!-- logo aplikasi -->
    <div class="container text-center pt-5">
      <img src="img/logo/logo-wide.png" width="200">
    </div>

    <!-- MAIN APP -->
    <div class="app container px-0">

      <!-- form login -->
      <div class="cov-form shadow-lg p-5">
        <!-- img varian pojok -->
        <img src="img/logo/icon.png" class="img-varian">
        <!-- title -->
        <h1 class="font-weight-bold mb-5">Log In</h1>
        <!-- form -->
        <form method="POST" action="">
          <!-- username -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <div class="input-group-text material-icons">person</div>
            </div>
            <input type="text" name="username" class="form-control" id="inlineFormInputGroup" placeholder="Username" required>
          </div>
          <!-- password -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <div class="input-group-text material-icons">vpn_key</div>
            </div>
            <input type="password" name="password" class="form-control" id="inlineFormInputGroup" placeholder="Password" required>
          </div>
          <!-- button -->
          <button type="submit" name="login" class="btn btn-primary mb-5">Log In</button>
          <!-- create account -->
          <h6>Belum punya akun? <a href="register.php">Create Account</a></h6>
        </form>
      </div>
    </div>

   



    <!-- javascript -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <script>
      $(function () {
          $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    
  </body>
</html>