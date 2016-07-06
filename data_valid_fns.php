<?php

function filled_out($form_vars) {
  // test that each variable has a value
  foreach ($form_vars as $key => $value)   {
     if (!isset($key) || ($value === '')) {
        return false;
     }
  }
  return true;
}

function clean($string) {
  $string = trim($string);
  $string = unescape($string);
  //$string = htmlentities($string,ENT_QUOTES,'UTF-8');
  $string = htmlentities($string);
  $string = strip_tags($string);
  if (!get_magic_quotes_gpc()) {
    $string = addslashes($string);}
  return $string;
}

function clean_all($form_vars) {
  foreach ($form_vars as $key => $value)   {
     $form_vars[$key] = clean($value);
  }
  return $form_vars;
}

function unescape($str){   
  $ret = '';
  $len = strlen($str);
  for ($i = 0; $i < $len; $i++){
    if ($str[$i] == '%' && $str[$i+1] == 'u'){
      $val = hexdec(substr($str, $i+2, 4));
      if ($val < 0x7f) $ret .= chr($val);
      else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
      else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
      $i += 5;
    } else if ($str[$i] == '%'){
      $ret .= urldecode(substr($str, $i, 3));   
      $i += 2;
    } else $ret .= $str[$i];
  }
  return $ret;
}
?>

