<?php
  echo "<pre>";
  print_r($_FILES);
  print_r($_POST);
  echo "</pre>";

  // ambil data file
$namaFile = $_FILES['form_gambar']['name'];
$namaSementara = $_FILES['form_gambar']['tmp_name'];

// tentukan lokasi file akan dipindahkan
$dirUpload = "assets/";

// pindahkan file
move_uploaded_file($namaSementara, $dirUpload."test - ".$namaFile);
?>