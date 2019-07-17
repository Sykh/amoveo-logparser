<?php

// Scan through your dir, ignore . and .. and create the links to the array index
$logfiles = scandir("files");
for($i = 0; $i < count($logfiles); $i++) {
	$value = $logfiles[$i];
	if ($value == "." || $value == "..")
		continue;
	echo "<a href=\"?logfile=$i\">$value</a><br>";
}
if (!isset($_GET['logfile']))
	die("<h3>Please select a file to parse</h3>");

// select the specified array indexe filename, no direct filenames!
$fn = fopen("files/".$logfiles[$_GET['logfile']],"r");

$filter = false;
if (isset($_GET['filter']))
	$filter = $_GET['filter'];
if ($filter)
	echo "<h3>Filtering for $filter errors in ".$logfiles[$_GET['logfile']]."</h3>";
$i = 1;
$text = "";
$startTime = false;

// read the selected file
while(! feof($fn))  {
$result = fgets($fn);
if (strpos($result, "2019-") !== false) {
	$timestamp = substr($result, 0, 19);
	if (!$startTime)
		$startTime = $timestamp;
	$endTime = $timestamp;
	if ($text != "") {
		$errorTexts[$i] = $text;
		// find all .erl files in that line and create references to the text id in the $errors array
		preg_match_all("|\/([a-zA-Z_]+\.erl)|", $text, $matches);
		
		$types = $matches[1];
		$erlFiles = array_unique($types);
		foreach ($erlFiles as $types)
			$errors[$types][] = $i;
		$text = "";
	}
}
$text .= $result;	
$i++;
}
fclose($fn);

echo "<p>Logfile ".$logfiles[$_GET['logfile']]." from $startTime to till $endTime</p>";
foreach ($errors as $file => $liste) {
	echo count($liste)." errors in <a href=\"?logfile=".$_GET['logfile']."&filter=$file\">$file</a><br>";
}

if ($filter) {
	// reverse sort -> order by date desc
	rsort($errors[$filter]);
	foreach ($errors[$filter] as $errorId) {
		echo "<p>".nl2br($errorTexts[$errorId])."</p>";
	}
}
?>
