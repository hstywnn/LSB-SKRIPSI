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
  // $msg = "pesan stego dummy! tambah lagi heh";
  $msg = $_POST['form_pesan'];
  $pin = $_POST['form_PIN'];
  // $dst = "assets/image1.png";
  $dst = $upload_directory."testEncode - ".$file_name;

  $t_info = getimagesize( $dst );
  $img_w = $t_info[0];
  $img_h = $t_info[1];
  $img_t = $t_info['mime'];
  $img_size = $img_w*$img_h;
  $line_length = ($img_w*3) / 8;
  $max_length = $line_length * ($img_h);
  
  // if( $max_length < (strlen($msg)+3) || $line_length < strlen($meta) ) {
  //   echo( 'message is too long or image is too small' );
  // }
  
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
  // inject( $img, $meta, 0 );
  if ($max_length >= (strlen($msg)+3)) {
    if (inject( $img, $msg, $pin, 0 ) == "success_input") {
      var_dump("success");
    }
  }else{
    echo( 'message is too long or image is too small' );
    echo($max_length." -- ". strlen($msg)+3);
  }
  // png output because of the quality of the renderer image
  // imagepng( $img, "assets/hasil-".time().".png" );
  imagepng( $img, "assets/hasil.png" );
  
  function _encryptmsg($msg, $pin){
    $msg_length = strlen($msg);
    $pin_iteration = 0;
    for ($i=0; $i < $msg_length; $i++) { 
      $msg[$i] = chr(ord($msg[$i])+$pin[$pin_iteration++]);
      if ($pin_iteration >= 4) {
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
    // var_dump($full_msg);
    // var_dump(_encryptmsg($data, $pin));
    $msg_length = strlen( $full_msg );
    $color_space_list = ['r', 'g', 'b'];

    for( $i=0 ; $i<$msg_length ; $i++) { // convert message to binary
      // var_dump(ord($full_msg[$i]));
      $str .= sprintf( "%08b", ord($full_msg[$i]) );
    }
    //var_dump( $str );
  
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
            $to_var_dump = array(
              "ruang" => $color_space_list[$i],
              "bitcounter" => $bit_string_counter,
              "msg_length" => $msg_length,
              "j" => $j,
              "imgsize" => $img_size,
              "x" => $x,
              "y" => $y
            );
            print_r(json_encode($to_var_dump));
            echo "<br>";
  
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
  
  
  exit();
  
?>