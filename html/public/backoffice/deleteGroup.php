<?php
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    header('Content-Type: application/json');

    $body = file_get_contents('php://input');

    if ($_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded') {
        parse_str($body, $params);
    } else if ($_SERVER['CONTENT_TYPE'] === 'application/json'){
        $params = json_decode($body, true);
    } else if (strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== -1) {
        echo json_encode(['error' => sprintf('Content-Type "%s" not supported', $_SERVER['CONTENT_TYPE'])]);
        die();
    }

    if (
        (
            isset($_POST['_method']) && $_POST['_method'] === 'DELETE' &&
            isset($_POST['name']) && !empty($_POST['name'])
        ) || (
            $_SERVER['REQUEST_METHOD'] === 'DELETE' &&
            isset($params['name']) && !empty($params['name'])
        )
    ) {
        $name = isset($_POST['name']) ? $_POST['name'] : $params['name'];
        if ($name === 'admin' || $name === 'moderator') {
            echo json_encode(['error' => sprintf('The group %s can\'t be deleted', $name)]);
            die();
        }

        $apacheRoot = '/var/www/html';
        $documentRoot = sprintf('%s/public', $apacheRoot);
        $path = sprintf('%s/security_groups/%s', $documentRoot, $name);

        if (!file_exists($path) || !is_dir($path)) {
            echo json_encode(['error' => sprintf('No dir exists for the group %s', $name)]);
            die();
        }

        rrmdir($path);

        $content = file_get_contents(sprintf('%s/groups', $apacheRoot));
        $result = preg_replace(sprintf('/^.*(?:%s\:).*$(?:\r\n|\n)?/m', $name), '', $content);
        file_put_contents(sprintf('%s/groups', $apacheRoot), $result);

        echo json_encode(['message' => sprintf('The group "%s" was successfully deleted!', $name)]);
        die();
    }
?>
