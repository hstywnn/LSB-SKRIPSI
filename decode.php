<?php
// echo "<pre>";
// print_r($_FILES);
// print_r($_POST);
// echo "</pre>";

// ambil data file
$file_name = $_FILES['form_gambar']['name'];
$file_tmp_name = $_FILES['form_gambar']['tmp_name'];

// tentukan lokasi file akan dipindahkan
$upload_directory = "assets/";

// pindahkan file
move_uploaded_file($file_tmp_name, $upload_directory."testDecode - ".$file_name);


$src = $upload_directory."testDecode - ".$file_name;
$pin = $_POST['form_PIN'];

// init
$t_info = getimagesize( $src );
$img_w = $t_info[0];
$img_h = $t_info[1];
$img_size = $img_w*$img_h;
$img_t = $t_info['mime'];

switch( $img_t )
{
  case 'image/jpeg':
    $img = imagecreatefromjpeg( $src );
    break;
  case 'imge/gif':
    $img = imagecreatefromgif( $src );
    break;
  case 'image/png':
    $img = imagecreatefrompng( $src );
    break;
  default:
    usage();
}


// run
$data = _extract( $img, $pin, 0, $img_h );
echo $data;


// functions
function _extract( $img, $pin, $start_line, $end_line )
{
  global $img_w, $img_size;

  $str = '';
  $bit_string_counter = 0;
  $string_container = '';
  $color_space_list = ['r', 'g', 'b'];
  $stop_status = 0;


  for ($i=0; $i < 3 ;$i++) {
    for( $y=$start_line ; $y<$end_line ; $y++ ) {
      for( $x=0 ; $x<$img_w ; $x++ ) {
        $rgb = _imagecolorat( $img, $x, $y );

        $color_space = decbin( $rgb[$color_space_list[$i]] );
        $str .= $color_space[strlen($color_space)-1];
        $bit_string_counter++;
        if ($bit_string_counter % 8 == 0) {
          $string_container .= chr(bindec($str));
          $str = '';
        }

        // ###KEBUTUHAN DEBUG###
        // ----------------------------------
        // $to_var_dump = array(
        //   "ruang" => $color_space_list[$i],
        //   "bitcounter" => $bit_string_counter,
        //   "string_container" => $string_container
        // );
        // print_r(json_encode($to_var_dump));
        // echo "<br>";

        if (strpos($string_container,'!#$')) {
          $stop_status = 1;
          break;
        }
      }
      if ($stop_status != 0) {
        break;
      }
    }
    if ($stop_status != 0) {
      break;
    }
  }
  $string_container = str_replace("!#$","",$string_container);

  return _decryptmsg($string_container, $pin);
}

function _decryptmsg($msg, $pin){
  $msg_length = strlen($msg);
  $pin_length = strlen($pin);
  $pin_iteration = 0;
  for ($i=0; $i < $msg_length; $i++) { 
    $msg[$i] = chr(ord($msg[$i])-$pin[$pin_iteration++]);
    if ($pin_iteration >= $pin_length) {
      $pin_iteration = 0;
    }
  }
  return $msg;
}

function _imagecolorat( $img, $x, $y ) {
  $rgb = imagecolorat( $img, $x, $y );
  return array( 'r'=>($rgb>>16)&0xFF, 'g'=>($rgb>>8)&0xFF, 'b' => $rgb&0xFF );
}


exit();

?>