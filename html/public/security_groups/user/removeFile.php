<?php

header('Content-Type: application/json');

$currentDir = preg_replace('/\/removeFile.php$/', '', $_SERVER['SCRIPT_FILENAME']);
$uploadDir = sprintf('%s/files', $currentDir);
$uploadedFileDir = sprintf('%s/%s', $uploadDir, $_GET['filename']);

if (!is_file($uploadedFileDir)) {
    echo json_encode(['error' => 'File not found!']);
    die();
} else if (!unlink($uploadedFileDir)) {
    echo json_encode(['error' => 'File can\'t be removed!']);
    die();
}

echo json_encode(['message' => 'File removed!']);
die();
