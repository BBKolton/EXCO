<?php
	function randomString($length = 20) {
		$chars = 'abcdefghijklmnopqrstuvwxyz1234567890-';
		$charsLeng = strlen($chars);
		$result = '';
		for ($i = 0; $i < $length; $i++) {
			$result = $result . $chars[rand(0, $charsLeng - 1)];
		}
		return $result;
	}

	echo randomString();
	echo '<br>';
	if (1 === "1") {
		echo 'fuck me';
	}
?>