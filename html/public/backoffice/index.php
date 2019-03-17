<?php

    exec('cat /var/www/html/users', $rawUsers);
    $rawGroups = file_get_contents('/var/www/html/groups');

    $groups = [];
    foreach(explode("\n", $rawGroups) as $group) {
        $group = explode(":", $group);
        if (!empty($group[0])) {
            $groups[$group[0]] = array_filter(explode(" ", $group[1]));
        }
    }

    $users = [];
    foreach($rawUsers as $user) {
        $user = explode(":", $user);
        $users[$user[0]] = $user[1];
    }

?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/backoffice/style.css">
        <style media="screen">
            * {
                font-family: 'Roboto', 'Helvetica';
            }
        </style>
    </head>
    <body>
        <div id="permissionWrapper" class="permissionWrapper">
          <label for="inputFilterPermission" class="listFilterLabel">Filter</label>
          <input id="inputFilterPermission" type="text" class="listFilterInput"/>
          <a id="addUser" href="#" class="iconAdd" title="Add new user"></a>
          <div class="clear"></div>
          <table id="listFilterPermission" class="listFilterContainer permissionsTable" cellspacing="0" cellpadding="0">
            <thead id="permissionsHead">
              <tr class="doNotFilter">
                <th>Username</th>
                <th>Password</th>
                <?php foreach ($groups as $name => $groupUsers): ?>
                    <th><div id="<?= $name ?>" class="permissionTag" data-perm="<?= $name ?>"><?= ucfirst($name) ?><a class="deleteGroup" data-action="/groups" data-group="<?= $name ?>">&times;</a></div></th>
                <?php endforeach; ?>
                <th></th>
              </tr>
            </thead>
            <tbody id="permissionsBody">
                <?php foreach ($users as $username => $password): ?>
                    <tr>
                        <td><span class="iconUser"></span><span contenteditable="true" class="userName"><?= $username ?></span></td>
                        <td><span contenteditable="false" class="userName"><?= $password ?></span></td>
                        <?php foreach ($groups as $groupname => $users): ?>
                        <td><div class="addToGroup permissionTag <?= in_array($username, $users) ? 'active' : '' ?>" data-perm="<?= $groupname ?>" data-action="/users/<?= $username ?>/group"><?= ucfirst($groupname) ?></div></td>
                        <?php endforeach; ?>
                        <td><a href="#" class="iconRemove deleteUser" title="Remove this user" data-action="/users" data-user="<?= $username ?>"></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <footer>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script src="/backoffice/main.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7"></script>
            <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                addUser.addEventListener('click', function () {
                    Swal.mixin({
                        input: 'text',
                        confirmButtonText: 'Next &rarr;',
                        showCancelButton: true,
                        progressSteps: ['1', '2']
                    }).queue([
                        {
                            title: 'What type ?',
                            text: '"group" or "user" only !'
                        },
                        'What is the name you want to give ?'
                    ]).then((result) => {
                        if (result.value) {
                            if (result.value[0] !== 'group' && result.value[0] !== 'user') {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'The type should only be "user" or "group" you given: ' + result.value[0] + '!'
                                })
                                return;
                            }

                            fetch('/users', {
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                method: "POST",
                                body: JSON.stringify({type: result.value[0], name: result.value[1]})
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(response) {
                                if (response.message) {
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success !',
                                        text: response.message
                                    }).then((result) => {
                                        location.reload();
                                    })
                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: response.error
                                    }).then((result) => {
                                        location.reload();
                                    })
                                }
                            })
                        }
                    })
                })
                document.querySelectorAll('.deleteUser').forEach(function (button) {
                    button.addEventListener('mousedown', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.value) {
                                fetch(this.getAttribute('data-action'), {
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    method: 'delete',
                                    body: JSON.stringify({name: this.getAttribute('data-user')})
                                })
                                .then((response) => {
                                    return response.json();
                                })
                                .then((response) => {
                                    if (response.message) {
                                        Swal.fire({
                                            type: 'success',
                                            title: 'Success !',
                                            text: response.message
                                        })

                                        if (this.localName === 'form') {
                                            this.submit();
                                        } else {
                                            this.click();
                                        }
                                    } else {
                                        Swal.fire({
                                            type: 'error',
                                            title: 'Oops...',
                                            text: response.error
                                        })
                                    }
                                })
                            }
                        })
                    })
                })
                document.querySelectorAll('.deleteGroup').forEach(function (button) {
                    button.addEventListener('click', function (evt) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.value) {
                                fetch(this.getAttribute('data-action'), {
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    method: 'delete',
                                    body: JSON.stringify({name: this.getAttribute('data-group')})
                                })
                                .then(function(response) {
                                    return response.json();
                                })
                                .then(function(response) {
                                    if (response.message) {
                                        Swal.fire({
                                            type: 'success',
                                            title: 'Success !',
                                            text: response.message
                                        }).then((result) => {
                                            location.reload();
                                        })
                                    } else {
                                        Swal.fire({
                                            type: 'error',
                                            title: 'Oops...',
                                            text: response.error
                                        }).then((result) => {
                                            location.reload();
                                        })
                                    }
                                })
                            }
                        })
                    })
                })
                document.querySelectorAll('.addToGroup').forEach(function (button) {
                    button.addEventListener('mousedown', function () {
                        if (!button.classList.contains('active')) {
                            fetch(this.getAttribute('data-action'), {
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                method: 'put',
                                body: JSON.stringify({name: button.getAttribute('data-perm')})
                            })
                            .then((response) => {
                                return response.json();
                            })
                            .then((response) => {
                                if (response.error) {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: response.error
                                    }).then((result) => {
                                        location.reload();
                                    })
                                }
                            })
                        } else {
                            fetch(this.getAttribute('data-action'), {
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                method: 'delete',
                                body: JSON.stringify({name: button.getAttribute('data-perm')})
                            })
                            .then((response) => {
                                return response.json();
                            })
                            .then((response) => {
                                if (response.error) {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: response.error
                                    }).then((result) => {
                                        location.reload();
                                    })
                                }
                            })
                        }
                    })
                })
            })
            </script>
        </footer>
    </body>
</html>
