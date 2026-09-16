<?php
 
  // kita include file koneksi.php
  include "koneksi.php";

  // jika tombol create ditekan
  if(isset($_POST['create'])){
    // ambil username
    $username = htmlspecialchars($_POST['username']);
    // cek kesamaan username
    $query_username =  mysqli_query($conn, "SELECT username FROM akun WHERE username = '$username'");
    if(mysqli_num_rows($query_username) == 0){
      // ambil password
      $password = htmlspecialchars($_POST['password']);
      // lakukan upload photo
      $photo = upload_poto();

      // kita cek photonya kosong atau tidak
      if(!empty($photo)){
        // enkripsi password
        $password = password_hash($password, PASSWORD_DEFAULT);
        // tambahkan ke database
        $query = mysqli_query($conn, "INSERT INTO akun VALUES('','$username','$password','$photo') ");
        // cek apakah input data berhasil?
        if(mysqli_affected_rows($conn) > 0){
          // ambil id akun yang baru aja dibuat
          $id_akun = mysqli_query($conn, "SELECT MAX(id) FROM akun");
          $id_akun = mysqli_fetch_assoc($id_akun);
          $id_akun = $id_akun['MAX(id)'];
          // membuat session
          session_start();
          $_SESSION['akun'] = $id_akun;
          // mengarah ke index.php
          header("Location: index.php");
        }
        // end affecter rows
      }
      // end if empty photo
    }else{
      echo "<script> alert('Username tidak tersedia!'); </script>";
    }
    // end cek kesamaan username
  }
  // end isset create


  function upload_poto(){
    global $conn;

    $nama_poto = $_FILES['file_photo']['name'];
    $size_poto = $_FILES['file_photo']['size'];
    $error_poto = $_FILES['file_photo']['error'];
    $lok_smt = $_FILES['file_photo']['tmp_name'];

    // ekstensi file yang diperbolehkan
    $ekstensi_yang_boleh = ['jpg','jpeg','png','JPG','JPEG','PNG'];

    // cari extensi file yang diupload
    // nama poto kita pecah menjadi array
    $nama_poto_array = explode(".",$nama_poto);
    // ambil isi array pada index terakhir
    $ekstensi_file_yang_diupload = end($nama_poto_array);

    // cek error atau tidak
    if($error_poto === 4){
      echo "<script> alert('File yang kamu error'); </script>";
    // cek format file sesuai atau tidak
    }elseif(!in_array($ekstensi_file_yang_diupload, $ekstensi_yang_boleh)){
      echo "<script> alert('File yang kamu upload bukan gambar'); </script>";
    // cek size poto apakah lebih besar dari 2 mb
    }elseif($size_poto > 2000000){
      echo "<script> alert('File yang kamu harus kurang 2 MB'); </script>";
    }else{
      // cek kesamaan nama
      // ambil nama poto dari database
      $poto_db = mysqli_query($conn, "SELECT * FROM akun WHERE photo = '$nama_poto'");
      // cek ada yang sama gak?
      if(mysqli_num_rows($poto_db) > 0){
        // jika ada yang sama kita cari tau index terakhir (index yang berisikan ekstensi)
        $index_ekstensi = array_search($ekstensi_file_yang_diupload, $nama_poto_array);
        // lakukan hapus nilai array berdasarkan indexnya
        unset($nama_poto_array[$index_ekstensi]);
        // berikan angka random dalam nama yang baru
        $nama_poto = implode(".", $nama_poto_array)."_".rand(1, 1000).".".$ekstensi_file_yang_diupload;
      }

      // pindahkan file
      move_uploaded_file($lok_smt, "img/photos/".$nama_poto);
      return $nama_poto;
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

      <!-- form register -->
      <div class="cov-form shadow-lg p-5">
        <!-- img varian pojok -->
        <img src="img/logo/icon.png" class="img-varian">
        <!-- title -->
        <h1 class="font-weight-bold position-relative" style="z-index: 999;">Create Account</h1>
        <!-- create account -->
        <h6 class="d-block mb-5 mt-2">Sudah punya akun? <a href="login.php">Log In</a></h6>

        <!-- form -->
        <form method="POST" action="" enctype="multipart/form-data">
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

          <!-- photo profil -->
          <div class="cov_photo input-group">
          </div>

          <!-- upload poto -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text material-icons">photo_camera</span>
            </div>
            <div class="custom-file">
              <input type="file" name="file_photo" class="custom-file-input" id="inputPhotoProfil" required>
              <label class="custom-file-label" for="inputPhotoProfil">Choose file</label>
            </div>
          </div>

          <!-- button -->
          <button type="submit" name="create" class="btn btn-primary">Create</button>
          
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

    <!-- Preview gambar sebelum diupload -->
    <script>
        function readURL(input, placeImage)
        {
            if(input.files )
            {
              var jmlFile = input.files.length;
              for(i = 0; i < jmlFile; i++)
              {
                  var reader = new FileReader();

                  reader.onload = (e)=>{
                      $($.parseHTML("<img style='max-width:100px;' class='mb-3'>")).attr('src', e.target.result).appendTo(placeImage);
                  }

                  reader.readAsDataURL(input.files[i]);
              }
            }
        }
        $("#inputPhotoProfil").change(function(){
            readURL(this, '.cov_photo');
            $(".custom-file-label").html(this.value);
        });

        
    </script>
    
  </body>
</html>