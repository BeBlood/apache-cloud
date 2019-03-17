<?php

$body = file_get_contents('php://input');
if (strlen($body) > 0) {
    $json = json_decode($body, true);

    header('Content-Type: application/json');
    if (
        !isset($json['type']) || empty($json['type']) ||
        !isset($json['name']) || empty($json['name']) ||
        ($json['type'] !== 'group' && $json['type'] !== 'user')
    ) {
        echo json_encode(['error' => 'Error some parameters are mising or empty', 'data' => $json]);
        die();
    }

    try {
        $apacheRoot = '/var/www/html';
        $documentRoot = sprintf('%s/public', $apacheRoot);
        $path = sprintf('%s/security_%ss/%s', $documentRoot, $json['type'], $json['name']);

        if (file_exists($path) && is_dir($path)) {
            echo json_encode(['error' => sprintf('The %s "%s" already exists', $json['type'], $json['name'])]);
            die();
        }

        mkdir($path);
        mkdir(sprintf('%s/%s', $path, 'files'));
        copy(sprintf('%s/index.php', $apacheRoot), sprintf('%s/index.php', $path));
        copy(sprintf('%s/uploadFile.php', $apacheRoot), sprintf('%s/uploadFile.php', $path));
        copy(sprintf('%s/removeFile.php', $apacheRoot), sprintf('%s/removeFile.php', $path));

        file_put_contents(sprintf('%s/.htaccess', $path), sprintf('Require %s %s', $json['type'], $json['name']));

        if ($json['type'] === 'user') {
            exec(sprintf('htpasswd -nb %s %s', $json['name'], $json['name']), $lines);
            file_put_contents(sprintf("%s/users", $apacheRoot), $lines[0] . "\n", FILE_APPEND);
        } else {
            file_put_contents(sprintf('%s/groups', $apacheRoot), sprintf("%s:\n", $json['name']), FILE_APPEND);
        }

        echo json_encode(['message' => sprintf('The %s "%s" was successfully created !', $json['type'], $json['name'])]);
        die();
    } catch(Exception $e) {
        echo json_encode(['error' => sprintf('Can\'t create the %s "%s"', $json['type'], $json['name'])]);
        die();
    }
}
