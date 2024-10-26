<?php
    require __DIR__ . '/vendor/autoload.php';
    use Palmo\Source\Db;
    session_start();

    $isLoggedIn = false;
    $isAdmin = false;
    $userName = "";

    if(isset($_SESSION['userLoggedIn'])) {
        $isLoggedIn = true;
        $userName = $_SESSION['userName'];
        $isAdmin = $_SESSION['isAdmin'];
    }
    $_SESSION['currentDir'] = basename(__DIR__);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Backend</title>
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Explore Space</title>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 850px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="css/form-validation.css" rel="stylesheet">
</head>
<body>
<?php
// Вивід помилок
if(isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    echo "<p style='color:red;'>$error</p>";
}
require __DIR__ . '/spaceScripts/header.php';
?>

<main class="container mt-4">
    <div id="app-content">
        <?php
        require __DIR__ . '/spaceScripts/homePage.php';
        ?>
    </div>
</main>


</body>
</html>
