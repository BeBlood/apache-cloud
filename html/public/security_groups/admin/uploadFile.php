<?php

header('Content-Type: application/json');

if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded!']);
    die();
}

$currentDir = preg_replace('/\/uploadFile.php$/', '', $_SERVER['SCRIPT_FILENAME']);
$uploadDir = sprintf('%s/files', $currentDir);
if (!is_dir($uploadDir)) {
    mkdir($uploadDir);
}

$file = $_FILES['file'];

$filename = htmlentities($file['name'], ENT_NOQUOTES, 'UTF-8');
$filename = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $filename);
$filename = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $filename); // pour les ligatures e.g. '&oelig;'
$filename = preg_replace('#&[^;]+;#', '', $filename); // supprime les autres caractères
$filename = ucfirst($filename);

$uploadedFileDir = sprintf('%s/%s', $uploadDir, $filename);
$path_parts = pathinfo($uploadedFileDir);

if($path_parts['extension'] === 'php') {
    http_response_code(400);
    echo json_encode(['error' => 'You can\'t upload this types of file!']);
    die();
} else if (file_exists($uploadedFileDir)) {
    http_response_code(400);
    echo json_encode(['error' => 'The file already exist!']);
    die();
} else if (!move_uploaded_file($file['tmp_name'], $uploadedFileDir)) {
    http_response_code(400);
    echo json_encode(['error' => 'Error when uploading file to the server!']);
    die();
}

echo json_encode(['error' => 'The file has been successfully uploaded!']);
die();
