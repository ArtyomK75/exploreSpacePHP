<?php
    require __DIR__ . '/vendor/autoload.php';
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Backend</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="./favicon.ico">
    </head>
    <body>
        <form method="POST" action="faker/db_seeder.php">

            <label for="questions_amount">Questions amount:</label>
            <input type="number" min="0" id="questions_amount" name="questions_amount" required><br>

            <input type="submit" class="btn btn-primary" value="Make faker questions data">
        </form>
        <form method="POST" action="faker/db_seeder.php">

            <label for="users_amount">Questions amount:</label>
            <input type="number" min="0" id="users_amount" name="users_amount" required><br>

            <input type="submit" class="btn btn-primary" value="Make faker users data">
        </form>
    </body>
</html>
