<?php 

function generate_salted_hash($inText, $saltHash=NULL, $mode='sha1')
{ 
	// hash the text
	$textHash = hash($mode, $inText); 
	// set where salt will appear in hash
	$saltStart = strlen($inText); 
	// if no salt given create random one
	if ($saltHash == NULL)
	{
		$saltHash = hash($mode, uniqid(rand(), true)); 
	}
	// add salt into text hash at pass length position and hash it
	if ($saltStart > 0 && $saltStart < strlen($saltHash))
	{ 
		$textHashStart = substr($textHash,0,$saltStart); 
		$textHashEnd = substr($textHash,$saltStart,strlen($saltHash)); 
		$outHash = hash($mode, $textHashEnd.$saltHash.$textHashStart); 
	}
	elseif ($saltStart > (strlen($saltHash)-1))
	{ 
		$outHash = hash($mode, $textHash.$saltHash); 
	}
	else
	{
		$outHash = hash($mode, $saltHash.$textHash); 
	}
	// put salt at front of hash
	$output = $saltHash.$outHash; 
	return $output; 
}

	echo generate_salted_hash("temppass");

?>