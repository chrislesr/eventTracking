<?php 
	$UIDresult = $_POST['UIDresult'];
	if(empty($UIDresult)){
		$UIDresult = '====';
	}
	$Write="<?php $" . "UIDresult='" . $UIDresult . "'; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('DatacontentUID.php',$Write);
?>