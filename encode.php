<?php
  echo "<pre>";
  print_r($_FILES);
  print_r($_POST);
  echo "</pre>";

  // ambil data file
  $file_name = $_FILES['form_gambar']['name'];
  $file_tmp_name = $_FILES['form_gambar']['tmp_name'];

  // tentukan lokasi file akan dipindahkan
  $upload_directory = "assets/";

  // pindahkan file
  move_uploaded_file($file_tmp_name, $upload_directory."testEncode - ".$file_name);

  date_default_timezone_set("Asia/Jakarta");

  $meta = "checkstego";
  $msg = $_POST['form_pesan'];
  $pin = $_POST['form_PIN'];
  $dst = $upload_directory."testEncode - ".$file_name;

  $t_info = getimagesize( $dst );
  $img_w = $t_info[0];
  $img_h = $t_info[1];
  $img_t = $t_info['mime'];
  $img_size = $img_w*$img_h;
  $line_length = ($img_w*3) / 8;
  $max_length = $line_length * ($img_h);
  
  switch( $img_t )
  {
    case 'image/jpeg':
      $img = imagecreatefromjpeg( $dst );
      break;
    case 'imge/gif':
      $img = imagecreatefromgif( $dst );
      break;
    case 'image/png':
      $img = imagecreatefrompng( $dst );
      break;
    default:
      usage();
  }
  
  
  // run
  if ($max_length >= (strlen($msg)+3)) {
    if (inject( $img, $msg, $pin, 0 ) == "success_input") {
      imagepng( $img, "assets/hasil.png" );
      $image_comparation = _calculatePSNR('assets/hasil.png', $dst);
      echo "<pre>";
      print_r($image_comparation);
      echo "</pre>";
      var_dump("success encode");
    }
  }else{
    echo( 'message is too long or image is too small' );
    echo($max_length." -- ". strlen($msg)+3);
  }
  
  function _encryptmsg($msg, $pin){
    $msg_length = strlen($msg);
    $pin_length = strlen($pin);
    $pin_iteration = 0;
    for ($i=0; $i < $msg_length; $i++) { 
      $msg[$i] = chr(ord($msg[$i])+$pin[$pin_iteration++]);
      if ($pin_iteration >= $pin_length) {
        $pin_iteration = 0;
      }
    }
    return $msg;
  }
  
  function inject( $img, $data, $pin, $start_line )
  {
    global $img_w, $img_size;
  
    $str = '';
    $stopper = '!#$';
    $full_msg = _encryptmsg($data, $pin).$stopper;
    $msg_length = strlen( $full_msg );
    $color_space_list = ['r', 'g', 'b'];

    for( $i=0 ; $i<$msg_length ; $i++) { // convert message to binary
      $str .= sprintf( "%08b", ord($full_msg[$i]) );
    }
  
    $msg_length = strlen( $str );
    $bit_string_counter = 0;
  
    for ($i=0; $i < 3 ;$i++) { 
      $x = 0;
      $y = $start_line;
      if( $bit_string_counter < $msg_length ) {
        for( $j=0 ; $j<$img_size ; $j++)
        {
          if( $bit_string_counter < $msg_length ) {
            $rgb = _imagecolorat( $img, $x, $y );
  
            $color_space = decbin( $rgb[$color_space_list[$i]] );
            $color_space[strlen($color_space)-1] = $str[$bit_string_counter];
            $rgb[$color_space_list[$i]] = bindec( $color_space );
            $bit_string_counter++;

            // ###KEBUTUHAN DEBUG###
            // ----------------------------------
            // $to_var_dump = array(
            //   "ruang" => $color_space_list[$i],
            //   "bitcounter" => $bit_string_counter,
            //   "msg_length" => $msg_length,
            //   "j" => $j,
            //   "imgsize" => $img_size,
            //   "x" => $x,
            //   "y" => $y
            // );
            // print_r(json_encode($to_var_dump));
            // echo "<br>";
  
            _imagesetpixel( $img, $x, $y, $rgb );
  
            $x++;
            if( $x == $img_w ) {
              $x = 0;
              $y++;
            }
          }else{
            break;
          }
        }
      }else{
        break;
      }
    }

    return "success_input";
  }
  
  
  function _imagesetpixel( $img, $x, $y, $rgb ) {
    $color = imagecolorallocate( $img, $rgb['r'], $rgb['g'], $rgb['b'] );
    imagesetpixel( $img, $x, $y, $color );
    imagecolordeallocate( $img, $color );
  }
  
  
  function _imagecolorat( $img, $x, $y ) {
    $rgb = imagecolorat( $img, $x, $y );
    return array( 'r'=>($rgb>>16)&0xFF, 'g'=>($rgb>>8)&0xFF, 'b' => $rgb&0xFF );
  }
  
  function _calculatePSNR($img_stego, $img_cover) {
    $stego_dimension = getimagesize($img_stego);
    $cover_dimension = getimagesize($img_cover);
    $color_space_list = ['r', 'g', 'b'];

    $psnr = 0;
    $mse = 0;
    $sum_container = 0;
    if ($stego_dimension == $cover_dimension) {
      $img_stego_render = imagecreatefrompng($img_stego);
      $img_cover_render = imagecreatefrompng($img_cover);
      for ($i=0; $i < $stego_dimension[0] ; $i++) { 
        for ($j=0; $j < $stego_dimension[1]; $j++) { 
          $rgb_stego = _imagecolorat( $img_stego_render, $i, $j );
          $rgb_cover = _imagecolorat( $img_cover_render, $i, $j );
          
          for ($c=0; $c < 3; $c++) { 
            $compare_rgb[$color_space_list[$c]] = abs($rgb_stego[$color_space_list[$c]]-$rgb_cover[$color_space_list[$c]]);

            // ###KEBUTUHAN DEBUG###
            // ----------------------------------
            // print_r(
            //   array(
            //     "color_space" => $color_space_list[$c],
            //     "rgb_stego" => $rgb_stego[$color_space_list[$c]],
            //     "rgb_cover" => $rgb_cover[$color_space_list[$c]],
            //     "rgb_diff" => $compare_rgb[$color_space_list[$c]],
            //   )
            // );echo "<br>";

            if (isset($compare_rgb[$color_space_list[$c]])) {
              $sum_container = $sum_container + $compare_rgb[$color_space_list[$c]];
            }
          }
        }
      }
      $mse = pow($sum_container,2)/($stego_dimension[0]*$stego_dimension[1]);
    }else{
      $mse = abs(($stego_dimension[0]*$stego_dimension[1])-($cover_dimension[0]*$cover_dimension[1]));
    }
    if ($mse == 0) {
      $psnr = 100;
    }else{
      $PIXEL_MAX = 255.0;
      $psnr = round(20 * log(($PIXEL_MAX/sqrt($mse)), 10),2);
    }
    return array(
      'PSNR' => $psnr,
      'MSE' => $mse
    );

  }
  exit();
  
?>