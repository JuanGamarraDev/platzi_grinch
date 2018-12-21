<?php

function getDataFile($fileName) {
	$fileData = [];
	/* Open File */
	if (($file = fopen($fileName, "r")) !== false) {
	  while (($data = fgetcsv($file, 1000, ",")) !== false) {
	    /* Add data in Array */
	    $fileData[] = $data;
	  }
	  fclose($file);
	}

	$existData = true;
	/* Iterating data */
	while ($existData) {
	  for($i = 0;  $i < count($fileData) - 1; $i++) {
	    if ($fileData[$i][1] < $fileData[$i+1][1] ) {
	      $fileData[$i] = $fileData[$i+1];
	      $fileData[$i+1] = $fileData[$i];
	    } else {
	    	$existData = false;
	    }
	  }
	}

	return $fileData;
}

function newCsv($data, $fileName, $del) {
	$tempMemory = fopen('php://memory', 'w');
	/* Iterating data */
	foreach ($data as $line) {
	  fputcsv($tempMemory, $line, $del);
	}
	fseek($tempMemory, 0);
	/* Header for Csv file */
	header('Content-Type: application/csv');
	header('Content-Disposition: attachement; filename="' . $fileName . '";');
	/* Download file */
	fpassthru($tempMemory);
}

$dataToCsv = getDataFile('casa.csv');
newCsv($dataToCsv, 'Casa_Ordenada.csv', ',');
