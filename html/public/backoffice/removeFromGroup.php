<?php
    if (!isset($_GET['name']) && empty($_GET['name'])) {
        die();
    }

    $apacheRoot = '/var/www/html';
    $documentRoot = sprintf('%s/public', $apacheRoot);
    $groups = file_get_contents(sprintf('%s/groups', $apacheRoot));

    $body = file_get_contents('php://input');
    if (strlen($body) > 0) {
        $json = json_decode($body, true);

        header('Content-Type: application/json');
        if (!isset($json['name']) || empty($json['name'])) {
            echo json_encode(['error' => 'Error some parameters are mising or empty']);
            die();
        }

        try {
            $path = sprintf('%s/security_users/%s', $documentRoot, $_GET['name']);

            if (!file_exists($path) || !is_dir($path)) {
                echo json_encode(['error' => sprintf('The user "%s" doesn\'t exists', $_GET['name'])]);
                die();
            }

            preg_match(sprintf('/%s:.*/', $json['name']), $groups, $groupMatch);

            if (empty($groupMatch)) {
                echo json_encode(['error' => sprintf('The group "%s" doesn\'t exists', $json['name'])]);
                die();
            }

            preg_match(sprintf('/ %s/', $_GET['name']), $groupMatch[0], $matches);

            if (empty($matches)) {
                echo json_encode(['matches' => $groupMatch, 'error' => sprintf('The user "%s" is not part of "%s" group', $_GET['name'], $json['name'])]);
                die();
            }

            $newRoles = preg_replace(sprintf('/ %s/', $_GET['name']), '', $groupMatch[0]);
            $result = preg_replace(sprintf('/%s:.*/', $json['name']), $newRoles, $groups);

            file_put_contents(sprintf('%s/groups', $apacheRoot), $result);
            echo json_encode(['message' => sprintf('The user "%s" was successfully removed from the group "%s"', $_GET['name'], $json['name'])]);
            die();
        } catch (Exception $e) {
            echo json_encode(['error' => 'There was an error']);
            die();
        }
    }
