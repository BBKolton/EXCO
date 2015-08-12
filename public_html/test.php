<?php 

	function randomString($length = 80) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-';
		$charsLeng = strlen($chars);
		$result = '';
		for ($i = 0; $i < $length; $i++) {
			$result = $result . $chars[rand(0, $charsLeng - 1)];
		}
		return $result;
	}

	echo randomString();

?>