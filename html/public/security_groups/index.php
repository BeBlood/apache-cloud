<?php

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
            section {
                display: inline-block;
                vertical-align: top;
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
                width: 70vw;
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
            <h2><a href="/">Home</a> > Groups</h2>
            <aside>
                <?php foreach ($groups as $group): ?>
                    <?php $group = explode(':', $group); ?>
                    <article>
                        <a href="/groups/<?= $group[0] ?>">
                            <img src="https://opengameart.org/sites/default/files/Flat%20Folder%20icon.png" alt="directory-icon">
                            <?= $group[0] ?>
                        </a>
                    </article>
                <?php endforeach; ?>
            </aside>
        </section>
    </body>
</html>
