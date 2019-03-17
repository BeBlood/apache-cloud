<?php
    $files = scandir(__DIR__ . '/files');

    $deletedValues = ['.', '..', 'index.php', '.htaccess'];
    foreach($deletedValues as $deletedValue) {
        unset($files[array_search($deletedValue, $files)]);
    }

    exec('cat /var/www/html/users', $users);
    exec('cat /var/www/html/groups', $groups);

?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
        <style media="screen">
            * {
                font-family: 'Roboto', 'Helvetica';
                box-sizing: border-box;
            }
            html.hover {
                background-color: rgba(1, 2, 3, 0.4);
            }
            body {
                height: 100vh;
                margin: 0;
            }
            section {
                display: inline-block;
                vertical-align: top;
                height: 100%;
            }
            section#tree {
                width: 25vw;
            }
            section#tree ul {
                margin: 0px 0px 0px 20px;
                list-style: none;
                line-height: 2em;
                font-family: Arial;
            }
            section#tree ul li {
                font-size: 16px;
                position: relative;
            }
            section#tree ul li:before {
                position: absolute;
                left: -15px;
                top: 0px;
                content: '';
                display: block;
                border-left: 1px solid #ddd;
                height: 1em;
                border-bottom: 1px solid #ddd;
                width: 10px;
            }
            section#tree ul li:after {
                position: absolute;
                left: -15px;
                bottom: -7px;
                content: '';
                display: block;
                border-left: 1px solid #ddd;
                height: 100%;
            }
            section#tree ul li.root {
                margin: 0px 0px 0px -20px;
            }
            section#tree ul li.root:before {
                display: none;
            }
            section#tree ul li.root:after {
                display: none;
            }
            section#tree ul li:last-child:after {
                display: none;
            }
            section#files {
                width: 73vw;
            }
            aside {
                display: grid;
                grid-template-columns: repeat(8, 1fr);
            }
            article {
                width: 100%;
                text-align: center;
                cursor: pointer;
            }
            article img {
                width: 100%;
                opacity: 0.5;
                transition: all 1s;
            }
            article img:hover {
                opacity: 1;
            }
            a {
                text-decoration: none;
                color: black;
            }
        </style>
    </head>
    <body>
        <section id="tree">
            <ul>
                <li class="root">
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="/users">Users</a>
                    <ul>
                        <?php foreach ($users as $user): ?>
                            <?php $user = explode(':', $user); ?>
                            <li><a href="/users/<?= $user[0] ?>"><?= $user[0] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li>
                    <a href="/groups">Groups</a>
                    <ul>
                        <?php foreach ($groups as $group): ?>
                            <?php $group = explode(':', $group); ?>
                            <li><a href="/groups/<?= $group[0] ?>"><?= $group[0] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </section>
        <section id="files">
            <h2><a href="/">Home</a> > Users</h2>
            <aside>
                <?php foreach ($files as $file): ?>
                    <article>
                        <a href="<?= $_SERVER['SCRIPT_URL'] ?>/file/<?= $file ?>">
                            <?php if (strpos($file, '.')): ?>
                                <img src="http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/file-text-icon.png" alt="file-icon">
                            <?php else: ?>
                                <img src="https://opengameart.org/sites/default/files/Flat%20Folder%20icon.png" alt="directory-icon">
                            <?php endif; ?>
                            <?= $file ?>
                        </a>
                    </article>
                <?php endforeach; ?>
            </aside>
        </section>
        <footer>
            <script src="/assets/js/dropzone.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7"></script>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function () {
                    var myDropzone = new Dropzone("html", { url: "<?= $_SERVER['SCRIPT_URL'] ?>/file"});
                    myDropzone.on('success', function (event) {
                        location.reload();
                    })

                    document.querySelectorAll('article').forEach(function (article) {
                        article.addEventListener('contextmenu', function (event) {
                            Swal.fire({
                                title: 'Are your sure you want to remove this file ?',
                                text: "You won't be able to revert this!",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.value) {
                                    fetch(this.querySelector('a').href, {
                                        headers: {
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        },
                                        method: "DELETE"
                                    })
                                    .then((response) => {
                                        return response.json();
                                    })
                                    .then((response) => {
                                        if (response.message) {
                                            this.parentNode.removeChild(this);
                                            Swal.fire(
                                                'Deleted!',
                                                'Your file has been deleted.',
                                                'success'
                                            )
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
                            event.preventDefault();
                        })
                    })
                });
            </script>
        </footer>
    </body>
</html>
