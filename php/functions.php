<?php
include './config.php';
function generate_stocastic(){
  $db = new mysqli($db_host, $db_id, $db_pass, $db_name);
  $sql = "SELECT n1,n2,n3,n4,n5,n6,n7 FROM numbers";
  $result = mysqli_query($db, $sql);
  if (!$result) {
    printf("Error: %s\n", mysqli_error($db));
    exit();
  }
  //$stack = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]; //php 5.4 over
  $stack = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0); // php 5.3 under
  while($row = mysqli_fetch_array($result)){
    for($i=0;$i<7;$i++){
      $stack[$row[$i]] = $stack[$row[$i]] + 1;
      $stack[0]++;
    }
  }
  return $stack;
}

function generate_stc_good_is_good(){
  $db = new mysqli($db_host, $db_id, $db_pass, $db_name);
  $stack = generate_stocastic();
  $num_array = array(); // php 5.4 over
  for($j=0;$j<6;$j++){
    a:
    $now = rand(1,$stack[0]);
    for($i=1;$i<46;$i++){
      $now = $now - $stack[$i];
      if($now<=0) {
        break;
      }else{
        continue;
      }
    }
    $now = $i;
    if (in_array($now, $num_array)){
      goto a;
    }else{
      array_push($num_array, $now);
    }
  }
  sort($num_array);
	$sql = "INSERT INTO generated_nums (n1,n2,n3,n4,n5,n6) VALUES ('$num_array[0]','$num_array[1]','$num_array[2]','$num_array[3]','$num_array[4]','$num_array[5]')";
	$result = $db->query($sql);
	return $num_array;
}

function generate_stc_good_is_bad(){
  $db = new mysqli($db_host, $db_id, $db_pass, $db_name);
  $stack = generate_stocastic();
  $num_array = array();
  $real_array = array();
  for($j=0;$j<45;$j++){
    a:
    $now = rand(1,$stack[0]);
    for($i=1;$i<46;$i++){
      $now = $now - $stack[$i];
      if($now<=0) {
        break;
      }else{
        continue;
      }
    }
    $now = $i;
    if($j < 39){
      if (in_array($now, $num_array)){
        goto a;
      }else{
        array_push($num_array, $now);
      }
    }else{
      if (in_array($now, $num_array)){
        goto a;
      }else{
        array_push($num_array, $now);
        array_push($real_array, $now);
      }
    }
  }
  $num_array = $real_array;
  sort($num_array);
	$sql = "INSERT INTO generated_nums (n1,n2,n3,n4,n5,n6) VALUES ('$num_array[0]','$num_array[1]','$num_array[2]','$num_array[3]','$num_array[4]','$num_array[5]')";
	$result = $db->query($sql);
	return $num_array;
}

function generate_uniform(){
	$db = new mysqli($db_host, $db_id, $db_pass, $db_name);
	$num_array = array();
	for($i=0;$i<6;$i++){
		a:
		$now = rand(1,45);
		if (in_array($now, $num_array)){
			goto a;
		}else{
			array_push($num_array, $now);
		}
	}
	sort($num_array);
	$sql = "INSERT INTO generated_nums (n1,n2,n3,n4,n5,n6) VALUES ('$num_array[0]','$num_array[1]','$num_array[2]','$num_array[3]','$num_array[4]','$num_array[5]')";
	$result = $db->query($sql);
	return $num_array;
}

function normal_rand($mean, $sd){
		$db = new mysqli($db_host, $db_id, $db_pass, $db_name);
		$num_array = array();
		for($i=0;$i<6;$i++){
			a:
			$x = mt_rand()/mt_getrandmax();
	    $y = mt_rand()/mt_getrandmax();
	    $now = sqrt(-2*log($x))*cos(2*pi()*$y)*$sd + $mean;
			if (in_array((int)$now, $num_array)||(int)$now <= 0||(int)$now > 45){
				goto a;
			}else{
				array_push($num_array, (int)$now);
			}
		}
		sort($num_array);
		$sql = "INSERT INTO generated_nums (n1,n2,n3,n4,n5,n6) VALUES ('$num_array[0]','$num_array[1]','$num_array[2]','$num_array[3]','$num_array[4]','$num_array[5]')";
		$result = $db->query($sql);
		return $num_array;
}

$num1 = generate_uniform();
$num2 = generate_stc_good_is_bad();
$num3 = generate_stc_good_is_good();
$num4 = generate_uniform();
$num5 = normal_rand(5,20);
$num6 = normal_rand(15,20);
$num7 = normal_rand(25,20);
$num8 = normal_rand(35,20);
$num9 = normal_rand(42,20);
$num10 = normal_rand(15,10);
$num11 = normal_rand(30,10);

?>
