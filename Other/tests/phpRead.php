<?php 
	$command = escapeshellcmd('./writePython.py');

	$output = shell_exec($command);
	echo $output;

	// file read code	
	/*$file = fopen("test.txt", "r") or die("Unable to open file");
	$output = fread($file, filesize("test.txt"));
	fclose($file);
	echo $output;*/
?>
