<?php

// function usage( $err=null ) {
//   echo 'Usage: '.$_SERVER['argv'][0]." <file hosting the message JPEG|GIF|PNG>\n";
//   if( $err ) {
//     echo 'Error: '.$err."\n";
//   }
//   exit();
// }

// if( $_SERVER['argc'] != 2 ) {
//   usage();
// }

// $src = $_SERVER['argv'][1];
// if( !is_file($src) ) {
//   usage( 'cannot find image source file !' );
// }

// $meta = "checkstego";
// $msg = "checkstego*";
$src = "out.png";
$pin = "1212";


// init
$t_info = getimagesize( $src );
$img_w = $t_info[0];
$img_h = $t_info[1];
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
// $meta = _extract( $img, 0, 1 );
// var_dump($meta);
$data = _extract( $img, $pin, 1, $img_h );
var_dump($data);
// file_put_contents( $meta, $data );


// functions
function _extract( $img, $pin, $start_line, $end_line )
{
  global $img_w;

  $str = '';
  for( $y=$start_line ; $y<$end_line ; $y++ ) {
    for( $x=0 ; $x<$img_w ; $x++ ) {
      $rgb = _imagecolorat( $img, $x, $y );
      //var_dump( $rgb );
      $red = decbin( $rgb['r'] );
      $str .= $red[strlen($red)-1];
      $green = decbin( $rgb['g'] );
      $str .= $green[strlen($green)-1];
      $blue = decbin( $rgb['b'] );
      $str .= $blue[strlen($blue)-1];
    }
  }
  //var_dump( $str );

  $final = '';
  $t_str = str_split( $str, 8 );
  //var_dump( $t_str );
  $l = count( $t_str );
  for( $i=0 ; $i<$l ; $i++ ) {
    $c = chr( bindec($t_str[$i]) );
    var_dump($c);
    if( _checklimiter($t_str, $i)) {
      break;
    } else {
      $final .= $c;
    }
  }

  // var_dump( $final );
  return _decryptmsg($final, $pin);
}

function _decryptmsg($msg, $pin){
  $msg_length = strlen($msg);
  $pin_iteration = 0;
  for ($i=0; $i < $msg_length; $i++) { 
    $msg[$i] = chr(ord($msg[$i])-$pin[$pin_iteration++]);
    if ($pin_iteration >= 4) {
      $pin_iteration = 0;
    }
  }
  return $msg;
}

function _checklimiter($t_str, $iteration){
  if (chr(bindec($t_str[$iteration])) == '!' && chr(bindec($t_str[$iteration+1])) == '#' && chr(bindec($t_str[$iteration+2])) == '$') {
    return true;
  } else {
    return false;
  }
}


function _imagecolorat( $img, $x, $y ) {
  $rgb = imagecolorat( $img, $x, $y );
  return array( 'r'=>($rgb>>16)&0xFF, 'g'=>($rgb>>8)&0xFF, 'b' => $rgb&0xFF );
}


exit();

?>