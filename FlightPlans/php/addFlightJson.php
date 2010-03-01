<?php

/**
 * Get file and append it to end
 * @param $file filename path
 * @param $newContent string json
 * @return string json format
 */
function combineContent($file, $newContent)
{
    $fileContent = json_decode(file_get_contents($file), true);
    $newContent = json_decode(stripslashes($newContent), true);
    
    if ( !empty($fileContent) ) {
    	  $fileContent['aaData'][] = array_values($newContent);
    } else {
        $fileContent = array('aaData' => array(array_values($newContent)));
    }
    
    return json_encode($fileContent);
}

/**
 * Save file to content
 * @param $file file path
 * @param $content to be write on a file
 */
function saveFile($file, $content)
{
    // open file handler
    $fileHandler = fopen($file, "w+");

    if ( $fileHandler ) {
        fwrite($fileHandler, $content);
    } else {
        "Ops something happen when writing a file";
    }
    fclose($fileHandler);
}

/** get today's date so we can make a new file with today's date
$today = date("j.n.Y");**/

$file = '../data/flights.txt';
/**$file = '../data/' + $today + '.txt';**/

// The new flight to add to the file
$content = $_POST["json"];

$allContent = combineContent($file, $content);
saveFile($file, $allContent);

?>
