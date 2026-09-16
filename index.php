<?php 
  // tambahkan koneksi
  include "koneksi.php";
  session_start();

  // cel apakah sudah terbuat session login apa belum
  if(isset($_SESSION['akun'])){
    $id_akun = $_SESSION['akun'];
  }else{
    header("Location: login.php");
  }

  // jika tombol simpan ditekan
  if(isset($_POST['simpan_photo'])){
    $photo = upload_poto();

    // kita cek photonya kosong atau tidak
    if(!empty($photo)){
      $query = mysqli_query($conn, "UPDATE akun SET photo = '$photo' WHERE id = '$id_akun' ");
      // mengarah ke index.php
      header("Location: index.php");
      
    }
    
  }


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


  // ambil data akun 
  $akun = mysqli_query($conn, "SELECT * FROM akun WHERE id = '$id_akun' ");
  $akun = mysqli_fetch_assoc($akun);




  // proses kirim pesan utk pertama kalinya
  if(isset($_POST['kirim_pesan'])){
    $id_penerima = $_POST['id_penerima'];
    $isi_pesan = htmlspecialchars($_POST['isi_pesan']);
    // insert ke tabel log_aktivitas
    mysqli_query($conn, "INSERT INTO log_aktivitas VALUES(
                          '',
                          NOW()
                        ) ");
    // jika berhasil, langkah selanjutnya adalah insert tabel log_kontak
    if(mysqli_affected_rows($conn) > 0){
      $id_log_terbaru = mysqli_query($conn, "SELECT MAX(id) FROM log_aktivitas");
      $id_log_terbaru = mysqli_fetch_assoc($id_log_terbaru);
      $id_log_terbaru = $id_log_terbaru['MAX(id)'];
      // tambah log_kontak dengan id akun
      mysqli_query($conn, "INSERT INTO log_kontak VALUES(
                            '',
                            '$id_log_terbaru',
                            '$id_akun'
                            )");
      // tambah log_kontak dengan id akun
      mysqli_query($conn, "INSERT INTO log_kontak VALUES(
                            '',
                            '$id_log_terbaru',
                            '$id_penerima'
                            )");
      // tambah ke tabel pesan
      mysqli_query($conn, "INSERT INTO pesan VALUES(
                              '',
                              '$id_akun',
                              '$id_log_terbaru',
                              '$isi_pesan',
                              NOW(),
                              'terkirim'
                            )");

      header("Location: ?id=$id_penerima&id_log=$id_log_terbaru");
    }
 
  }


  // kirim pesan untuk kesekian kalinya atau kirim pesan untuk membalas
  if(isset($_POST['kirim_pesan_lagi'])){
    $id_penerima = $_POST['id_penerima'];
    $isi_pesan = htmlspecialchars($_POST['isi_pesan']);
    $id_log = htmlspecialchars($_POST['id_log']);

    // tambah ke tabel pesan
      mysqli_query($conn, "INSERT INTO pesan VALUES(
                              '',
                              '$id_akun',
                              '$id_log',
                              '$isi_pesan',
                              NOW(),
                              'terkirim'
                            )");

      // update waktu di tabel log_aktivitas untuk mengubah urutan kolom kontak
      mysqli_query($conn, "UPDATE log_aktivitas SET tgl_waktu = NOW() WHERE id = '$id_log' ");

      header("Location: ?id=$id_penerima&id_log=$id_log");
  }

  // update pesan menjadi status terbaca
  if(isset($_GET['id']) AND isset($_GET['id_log'])){
    $id_penerima_update = $_GET['id'];
    $id_log_update = $_GET['id_log'];
    mysqli_query($conn, "UPDATE pesan SET status = 'terbaca' WHERE id_akun = '$id_penerima_update' AND id_log_aktivitas = '$id_log_update' ");
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

    <!-- additional css -->
    <link rel="stylesheet" href="style.css">

    
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
      .panel{
        height: 100%;
        width: 80px;
        background: rgba(255, 255, 255, 0.4);
        border-top-left-radius: 30px;
        border-bottom-left-radius: 30px;
        box-sizing: border-box;
      }
      .panel > a{
        text-decoration:  none;
        border-radius: 15px;
      }
      .base{
        height: 100%;
        width: 100%;
        background: rgba(255, 255, 255, 0.2);
        border-top-right-radius: 30px;
        border-bottom-right-radius: 30px;
        box-sizing: border-box;
      } 
      .menu-chat{
        width: 38%;
        height: 120%;
        margin-right: 20px;
        border-radius: 30px;
        position: relative;
        top: -60px;
        background: rgb(210, 243, 255);
        box-sizing: border-box;
      }
      .chat{
        width: 60%;
        height: 100%;
        border-radius: 20px;
      }

      .head-menu-chat{
        border: 0px solid black;
        height: 70px;
        padding: 10px 20px;
      }
      .label-head-menu-chat > h4{
        color: black;
        margin: 0;
      }
      .label-head-menu-chat > span{
        font-size: 9pt;
        color: gray;
      }
      .form-cari{
        position: relative;
      }
      .form-cari > .icon-cari{
        position: absolute;
        right: 10px;
        top:  7px;
      }
      .form-cari > input{
        padding-right: 35px;
      }
      .cover-kontak-chat{
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        overflow-y: auto;
        background: #f5f6fa;
      }
      .cover-kontak-chat > a{
        text-decoration: none;
        color: black;
      }
      .kontak-chat{
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        background: #f5f6fa;
      }
      .kontak-chat:hover{
        background: white;
      }
      .form-send{
        position: relative;
      }
      .form-send > input{
        padding-right: 50px;
        padding-left: 20px;
      }
      .btn-send{
        position: absolute;
        right: 25px;
        top: 7px;
        background: none;
        border: none;
      }
      .mrbo {
        width: 101px;
        height: 165px;
        margin: 0 auto;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        animation: mrbo_move 3s ease infinite;
        transform: scale(1.5);
      }
      .mrbo img{
        animation: mrbo 1.7s steps(17) alternate;
        animation-delay: 4s;
        animation-fill-mode: forwards;
        animation-direction: normal;
        position: absolute;
        left: 0;
      }
      @keyframes mrbo {
        0% {
          left: 0px;
        }
        99.9% {
          left: -1717px;
        }
        100% {
          left: -1616px;
        }
      }
      @keyframes mrbo_move {
        0% {
          top : 0px;
        }
        50% {
          top: 10px;
        }
        100% {
          top: 0px;
        }
      }
      .text-sambutan{
        font-size: 12pt;
        padding: 10px 100px;
        filter: opacity(0%);
        animation: textFade 2s ease forwards;
        animation-delay: 4.5s;
      }
      @keyframes textFade {
        from{
          filter: opacity(0%);


        }
        to{
          filter: opacity(100%);
        }
      }
      
    </style>

  </head>

  <body style="background-image: url('img/bg-blur4.jpg'); background-size: 1920px;">
    <!-- logo aplikasi -->
    <div class="container text-center pt-5">
      <img src="img/logo/logo-wide.png" width="200">
    </div>

    <!-- MAIN APP -->
    <div class="app container px-0 d-flex">

      <!-- PANEL -->
      <div class="panel p-2 d-inline-flex flex-column align-items-center">
        <!-- poto profilemu -->
        <a href="#" class="mt-2 rounded-circle d-flex justify-content-center align-items-center" data-toggle="modal" data-target="#edit_photo" style="width: 50px; height: 50px; overflow: hidden;">
          <img src="img/photos/<?= $akun['photo']; ?>" class="w-100">
        </a>

        <!-- tombol chat -->
        <a href="#" class="mt-4 material-icons text-primary p-3 bg-white shadow-lg" style="font-size: 22pt;" data-toggle="tooltip" data-placement="left" title="Kirim pesan">textsms</a>
        <!-- tombol call -->
        <a href="#" class="mt-4 material-icons text-secondary" style="font-size: 22pt;" data-toggle="tooltip" data-placement="left" title="Buat Panggilan">add_ic_call</a>
        <!-- tombol star chat -->
        <a href="#" class="mt-4 material-icons text-secondary" style="font-size: 22pt;" data-toggle="tooltip" data-placement="left" title="Pesan Berbintang">star</a>
        <!-- tombol setting -->
        <a href="#" class="mt-4 material-icons text-secondary" style="font-size: 22pt;" data-toggle="tooltip" data-placement="left" title="Pengaturan">settings</a>
        <!-- tombol logout -->
        <a href="logout.php" class="mt-4 material-icons text-danger" style="font-size: 22pt;" data-toggle="tooltip" data-placement="left" title="Keluar">logout</a>
      </div>

      <div class="base d-flex p-3">
        <!-- CHAT KONTAK -->
        <div class="menu-chat bg-white shadow-lg p-2 d-flex flex-column">
          <!-- Title -->
          <div class="head-menu-chat d-flex align-items-center my-2">
            <!-- icon -->
            <span class="material-icons text-primary" style="font-size: 28pt;">textsms</span>
            <!-- label -->
            <div class="label-head-menu-chat d-flex flex-column pl-3">
              <h4 class="font-weight-bold">Messages</h4>
              <span>Kirim pesan kepada semua orang</span>
            </div>
          </div>

          <!-- cari chat/kontak -->
          <form method="" action="">
              <div class="form-group form-cari mb-2">
                  <span class="icon-cari material-icons text-secondary">search</span>
                  <input type="text" name="cari" class="form-control" placeholder="Cari kontak" autocomplete="off">
              </div>
          </form>

          <!-- popup hasil pencarian kontak -->
          <?php if(isset($_GET['cari'])): 
                  $key = htmlspecialchars($_GET['cari']);
                  $cari_akun = mysqli_query($conn, "SELECT * FROM akun WHERE username LIKE '%$key%' AND id != '$id_akun' ");
                  if(mysqli_num_rows($cari_akun)):
            ?>
                <div class="bg-white shadow-lg p-2 rounded w-100" style="position: absolute; top: 130px; max-height:400px; overflow:auto;">
                  <span class="py-2 d-inline-block" style="font-size:10pt;">Hasil pencarian <b>"<?= $key; ?>"</b></span>
                  <?php while ($akun = mysqli_fetch_assoc($cari_akun)) :?>
                    
                    <a href="?id=<?= $akun['id']; ?>" style="text-decoration: none; color: black;">
                      <div class="kontak-chat text-info rounded d-flex flex-row align-items-center p-1">
                        <div class="d-flex justify-content-center align-items-center rounded-circle m-1 overflow-hidden" style="width: 50px; height: 50px;">
                          <img src="img/photos/<?= $akun['photo']; ?>" class="w-100" >
                        </div>
                        <div class="p-2" style="width: 80%; box-sizing: border-box;">
                          <h6 class="font-weight-bold m-0 mb-1"><?= $akun['username']; ?></h6>
                        </div>
                      </div>
                    </a>

                  <?php endwhile; ?>
                </div>
            <?php else: ?>
              <div class="bg-white shadow-lg p-2 rounded w-100" style="position: absolute; top: 130px; max-height:400px; overflow:auto;">
                  <span class="py-2 d-block" style="font-size:10pt;">Hasil pencarian <b>"<?= $key; ?>"</b></span>
                  <span class="py-2 d-block text-center rounded" style="border: 1px solid rgba(0, 0, 0, 0.1); font-size: small; background: lemonchiffon;">Maaf, kontak tidak ditemukan</span>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <!-- kontak chat -->
          <div class="cover-kontak-chat h-100">
            <?php 
                $q_kolom = mysqli_query($conn, "SELECT * FROM log_kontak INNER JOIN log_aktivitas ON log_kontak.id_log_aktivitas = log_aktivitas.id WHERE id_akun = '$id_akun' ORDER BY tgl_waktu DESC");
                if(mysqli_num_rows($q_kolom) > 0):
                  while($kolom = mysqli_fetch_assoc($q_kolom)):
                    $id_log = $kolom['id_log_aktivitas'];
                    $q_penerima = mysqli_query($conn, "SELECT * FROM log_kontak INNER JOIN akun ON log_kontak.id_akun = akun.id WHERE id_log_aktivitas = '$id_log' AND id_akun != '$id_akun'");
                    $penerima = mysqli_fetch_assoc($q_penerima);
                    $id_penerima_kolom = $penerima['id_akun'];

                    // query ambil pesan terbaru berdasarkan id log
                    $q_pesan = mysqli_query($conn, "SELECT * FROM pesan WHERE id_log_aktivitas = '$id_log' ORDER BY tgl_waktu DESC LIMIT 1");
                    $pesan = mysqli_fetch_assoc($q_pesan);
             ?>

             <!-- slot kontak saat pertama kali dimuat -->
             <?php if(!isset($_GET['id'])): ?>
              <a href="?id=<?= $penerima['id_akun']; ?>&id_log=<?= $id_log ?>">
              <div class="kontak-chat d-flex flex-row align-items-center p-1">
                  <div class="d-flex justify-content-center align-items-center overflow-hidden rounded-circle" style="width: 50px; height: 50px;">
                    <img src="img/photos/<?= $penerima['photo']; ?>" class="w-100">
                  </div>
                  
                  <div class="p-2" style="width: 80%;">
                    <h6 class="font-weight-bold m-0 mb-1"><?= $penerima['username']; ?></h6>
                    <p class="text-secondary m-0" style="font-size: 10pt; line-height: 16px;">
                      <?= substr($pesan['isi_pesan'],0,40); ?> 
                    </p>
                  </div>
                  <div class="d-flex flex-column align-items-start" style="width: 10%;">
                    <span class="text-secondary mb-2" style="font-size: 8pt;"><?= substr($pesan['tgl_waktu'],11,5); ?></span>
                    <!-- indikator jml pesan baru -->
                    <?php
                      $jml_pesan_baru = mysqli_query($conn, "SELECT * FROM pesan WHERE id_akun = '$id_penerima_kolom' AND id_log_aktivitas = '$id_log' AND status = 'terkirim' ");
                      if(mysqli_num_rows($jml_pesan_baru) > 0):
                    ?>
                        <b class="d-flex justify-content-center align-items-center bg-danger text-white rounded-circle mr-2" style="font-size: 9pt; width: 20px; height: 20px;">
                          <?= mysqli_num_rows($jml_pesan_baru); ?>
                        </b>
                    <?php endif; ?>
                  </div>
              </div>
              </a>

            <!-- slot kontak chat yang tidak aktif -->
            <?php elseif($penerima['id_akun'] != $_GET['id']) :?>
            <a href="?id=<?= $penerima['id_akun']; ?>&id_log=<?= $id_log ?>">
              <div class="kontak-chat d-flex flex-row align-items-center p-1">
                  <div class="d-flex justify-content-center align-items-center overflow-hidden rounded-circle" style="width: 50px; height: 50px;">
                    <img src="img/photos/<?= $penerima['photo']; ?>" class="w-100">
                  </div>
                  
                  <div class="p-2" style="width: 80%;">
                    <h6 class="font-weight-bold m-0 mb-1"><?= $penerima['username']; ?></h6>
                    <p class="text-secondary m-0" style="font-size: 10pt; line-height: 16px;">
                      <?= substr($pesan['isi_pesan'],0,40); ?> 
                    </p>
                  </div>
                  <div class="d-flex flex-column align-items-start" style="width: 10%;">
                    <span class="text-secondary mb-2" style="font-size: 8pt;"><?= substr($pesan['tgl_waktu'],11,5); ?></span>
                    <!-- indikator jml pesan baru -->
                    <?php
                      $jml_pesan_baru = mysqli_query($conn, "SELECT * FROM pesan WHERE id_akun = '$id_penerima_kolom' AND id_log_aktivitas = '$id_log' AND status = 'terkirim' ");
                      if(mysqli_num_rows($jml_pesan_baru) > 0):
                    ?>
                        <b class="d-flex justify-content-center align-items-center bg-danger text-white rounded-circle mr-2" style="font-size: 9pt; width: 20px; height: 20px;">
                          <?= mysqli_num_rows($jml_pesan_baru); ?>
                        </b>
                    <?php endif; ?>
                  </div>
              </div>
            </a>

          <?php else: ?>
            <!-- slot kontak chat yang ACTIVE-->
            <a href="?id=<?= $penerima['id_akun']; ?>&id_log=<?= $id_log ?>">
              <div class="kontak-chat bg-white shadow-lg d-flex flex-row align-items-center p-1 position-relative" style="z-index: 999;">
                  <div class="d-flex justify-content-center align-items-center overflow-hidden rounded-circle" style="width: 50px; height: 50px;">
                    <img src="img/photos/<?= $penerima['photo']; ?>" class="w-100">
                  </div>
                  <div class="p-2" style="width: 80%;">
                    <h6 class="font-weight-bold m-0 mb-1"><?= $penerima['username']; ?></h6>
                    <p class="text-secondary m-0" style="font-size: 10pt; line-height: 16px;">
                      <?= substr($pesan['isi_pesan'],0,40); ?>
                    </p>
                  </div>
                  <div class="d-flex flex-column align-items-start" style="width: 10%;">
                    <span class="text-secondary mb-2" style="font-size: 8pt;"><?= substr($pesan['tgl_waktu'],11,5); ?></span>
                    <!-- indikator jml pesan baru -->
                    <?php
                      $jml_pesan_baru = mysqli_query($conn, "SELECT * FROM pesan WHERE id_akun = '$id_penerima_kolom' AND id_log_aktivitas = '$id_log' AND status = 'terkirim' ");
                      if(mysqli_num_rows($jml_pesan_baru) > 0):
                    ?>
                        <b class="d-flex justify-content-center align-items-center bg-danger text-white rounded-circle mr-2" style="font-size: 9pt; width: 20px; height: 20px;">
                          <?= mysqli_num_rows($jml_pesan_baru); ?>
                        </b>
                    <?php endif; ?>
                  </div>
              </div>
            </a>
          <?php endif; ?>

            
            <?php endwhile; ?>
          <?php else: ?>

            <!-- jika belum ada chat -->
            <div class="p-2" style="box-sizing: border-box;">
              <h6 class="p-4 text-center rounded" style="border: 1px solid rgba(0, 0, 0, 0.1); font-size: small; background: lemonchiffon;">Tambah pesan baru dengan memilih kontak pada kolom "Cari Kontak"</h6>
            </div>
          <?php endif; ?>            
          </div>
        </div>


        <?php if(isset($_GET['id']) OR isset($_GET['id_log'])): 
            $id = htmlspecialchars($_GET['id']);
            $q_data_penerima = mysqli_query($conn, "SELECT * FROM akun WHERE id = '$id' ");
            $data_penerima = mysqli_fetch_assoc($q_data_penerima);

          ?>
        <!-- ISI CHAT -->
        <div class="chat bg-white d-flex flex-column overflow-hidden">
          <!-- head -->
          <div class="d-flex p-3 align-items-center">

            <div class="d-flex justify-content-center align-items-center rounded-circle overflow-hidden" style="width: 50px; height: 50px;">
              <img src="img/photos/<?= $data_penerima['photo']; ?>" class="w-100">
            </div>

            <div class="p-2 pl-3" style="width: 85%;">
              <h6 class="font-weight-bold m-0" style="font-size: 16pt;"><?= $data_penerima['username']; ?></h6>
              <p class="text-secondary m-0" style="font-size: 10pt;">Description profile</p>
            </div>

            <button type="button" class="material-icons text-secondary" style="background: none; border: none; ">menu</button>

          </div>

          <!-- isi chat -->
          <div class="d-inline-flex flex-column-reverse p-3 justify-content-start" style="background: url('img/pattern1.png'); border-top: 1px solid rgba(0, 0, 0, 0.1); height: 100%; overflow-y: auto;">

            <?php if(isset($_GET['id_log'])): 
                $id_log = $_GET['id_log'];
                $q_pesan = mysqli_query($conn, "SELECT * FROM pesan WHERE id_log_aktivitas = '$id_log' ORDER BY tgl_waktu DESC");
                while($pesan = mysqli_fetch_assoc($q_pesan)):
              ?>
              <!-- pesan dari pengirim -->
              <?php if($id_akun == $pesan['id_akun']): ?>
              <div class="d-inline-flex flex-column align-items-end mb-2" >
                <div class="bg-info text-white w-50 p-3 shadow" style="border-radius: 15px; border-bottom-right-radius:0;">
                  <p class="m-0 mb-2" style="font-size: 11pt; "><?= $pesan['isi_pesan']; ?></p>
                  <div class="d-flex justify-content-end align-items-center">
                    <!-- indikator pesan terbaca -->
                    <?php if($pesan['status'] == 'terbaca'): ?>
                      <span class="material-icons mr-2 text-warning" style="font-size: 12pt;">done_all</span>
                    <?php else: ?>
                      <span class="material-icons mr-2 text-light" style="font-size: 12pt;">done_all</span>
                    <?php endif; ?>
                    <div class="text-right" style="font-size: 8pt;"><?= $pesan['tgl_waktu']; ?></div>  
                  </div>
                </div>
              </div>

              <!-- pesan dari penerima -->
            <?php else: ?>
              <div class="d-inline-flex flex-column align-items-start mb-2">
                <div class="bg-white text-black w-50 p-3 shadow" style="border-radius: 15px; border-bottom-left-radius:0;">
                  <p class="m-0 mb-2" style="font-size: 11pt; "><?= $pesan['isi_pesan']; ?></p>
                  <div class="text-right" style="font-size: 8pt;"><?= $pesan['tgl_waktu']; ?></div>
                </div>
              </div>
            <?php endif; ?>

            <?php endwhile; ?>
            <?php endif; ?>
            
          </div>


          <!-- form chat -->
          <!-- jika pesan baru pertama kali -->
          <?php if(!isset($_GET['id_log'])): ?>
            <div class="bg-white" style="height: 15%; border-top: 1px solid rgba(0, 0, 0, 0.1);">
              <form method="POST" action="">
                <div class="form-send d-flex align-items-center form-group px-3 mt-3" style="box-sizing: border-box;">
                    <label for="file" class="material-icons text-success m-0 mr-2" style="font-size: 22pt; ">add_circle_outline</label>
                    <input type="file" name="lampiran" id="file" hidden>

                    <input type="hidden" name="id_penerima" value="<?= $data_penerima['id']; ?>">
                    <input type="text" name="isi_pesan" class="form-control w-100 rounded-pill bg-light" autocomplete="off">
                    <button type="submit" name="kirim_pesan" class="btn-send material-icons text-primary">send</button>
                </div>
              </form>
            </div>

          <?php else: ?>
            <!-- jika pesan lama -->
            <div class="bg-white" style="height: 15%; border-top: 1px solid rgba(0, 0, 0, 0.1);">
              <form method="POST" action="">
                <div class="form-send d-flex align-items-center form-group px-3 mt-3" style="box-sizing: border-box;">
                    <label for="file" class="material-icons text-success m-0 mr-2" style="font-size: 22pt; ">add_circle_outline</label>
                    <input type="file" name="lampiran" id="file" hidden>
                    <input type="hidden" name="id_log" value="<?= $_GET['id_log']; ?>">
                    <input type="hidden" name="id_penerima" value="<?= $data_penerima['id']; ?>">
                    <input type="text" name="isi_pesan" class="form-control w-100 rounded-pill bg-light" autocomplete="off">
                    <button type="submit" name="kirim_pesan_lagi" class="btn-send material-icons text-primary">send</button>
                </div>
              </form>
            </div>

          <?php endif; ?>

        </div>

      <?php else: ?>

        <!-- ISI CHAT JIKA KONTAK BELUM DIPILIH -->
        <div class="chat d-flex flex-column justify-content-center align-items-center overflow-hidden p-3" style="background: #f5f6fa;">
          <!-- gambar animasi -->
          <div class="mrbo">
            <img id="imgbo" src="img/mrBO.png">
          </div>

          <!-- teks sambutan -->
          <h4 class="text-sambutan text-center mt-5">Hallo, silahkan pilih kontak di samping untuk menampilkan pesan</h4>
        </div>

      <?php endif; ?>


      </div>
    </div>


    <!-- Modal Edit Photo -->
    <div class="modal fade" id="edit_photo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Photo Profil</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <!-- current photo preview -->
            <div class="mb-3">
              <img id="img_photo" src="img/photos/<?= $akun['photo']; ?>" style="width: 100%;">
            </div>

            <!-- form upload photo -->
            <form method="POST" action="" enctype="multipart/form-data">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text material-icons">photo_camera</span>
                </div>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="inputPhotoProfil" name="file_photo" required>
                  <label class="custom-file-label" for="inputPhotoProfil">Choose file</label>
                </div>
              </div>
            
          </div>

            <div class="modal-footer">
              <button type="button" class="btn-reset btn btn-secondary" data-dismiss="modal">Tutup</button>
              <button type="submit" name="simpan_photo" class="btn btn-primary">Simpan</button>
            </div>

          </form>
        </div>
      </div>
    </div>
   



    <!-- javascript dan JQuery-->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    <!-- memunculkan tooltip -->
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
                      $(placeImage).attr("src", e.target.result);
                  }

                  reader.readAsDataURL(input.files[i]);
              }
            }
        }
        // saat input file diisi atau diubah isinya
        $("#inputPhotoProfil").change(function(){
            readURL(this, '#img_photo');
            $(".custom-file-label").html(this.value);
        });

        // saat form ditutup
        $(".btn-reset").click(function(){
            location.reload();
        });
        
    </script>
    
  </body>
</html>