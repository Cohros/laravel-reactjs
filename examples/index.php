<?php require 'backend.php'; ?>
<!doctype html>
<html lang="pt-br">
    <head>
        <title>title</title>
        <meta charset="utf-8" />

        <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/app.css" />
    </head>
    <body>
        <div class="container">
            <div class="container-contacts-list">
                <?= $rjs->markup(); ?>
            </div>
        </div>

        <script type="text/javascript" src="js/bundle.js"></script>
        <script type="text/javascript">
            <?= $rjs->js('.container-contacts-list'); ?>
        </script>
    </body>
</html>
