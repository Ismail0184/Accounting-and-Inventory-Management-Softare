<?php
$a=1;
$b=2.39;
$c=2.034594854580;
$d="Hello";
$e="GM20";

//is_array and is_iterable = array("red", "green", "blue"); array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43"); $d = [1, 2, 3];
//is_bool = $c = true; $d = false;
//is_double and is_real and is_float = $c = 32.5; $g = 1.e3;
//is_int  and is_integer and is_long= $a = 32; $b = 0;
//is_null = $b = null; $d = NULL;
// is_numeric = $a = 32; $b = 0; $c = 32.5; $d = "32";
//is_scalar = $a = "Hello"; $b = 0;$c = 32;
//is_string $a= "";



if (is_float($a)) {
    echo $b;
} else {
    echo "is not float\n";
}



if(is_numeric($a)){
  echo $a."<br>";
} else { echo 'Ismail Hossain'.'<br>';}

if(is_double($b)){
  echo $b."<br>";
} else { echo 'B'.'<br>';}

if(is_int($c)){
  echo $c."<br>";
} else { echo 'c'.'<br>';}

if(is_string($d)){
  echo $d."<br>";
} else { echo 'D'.'<br>';}

if(is_scalar($e)){
  echo $e."<br>";
} else { echo 'E'.'<br>';}
?>
