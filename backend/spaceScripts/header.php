<?php
require __DIR__ . '/../vendor/autoload.php';
use Palmo\repository\impl\UserRepo;

$isLoggedIn = false;
$isAdmin = false;
$userName = "";

$isLoggedIn = array_key_exists('userLoggedIn', $_SESSION) ? $_SESSION['userLoggedIn'] : null;

$pathToSpaceScripts = '';
if ($_SESSION['currentDir'] != 'spaceScripts') {
    $pathToSpaceScripts = 'spaceScripts/';
}
$pathToRootScripts = '';
if ($_SESSION['currentDir'] === 'spaceScripts') {
    $pathToRootScripts = '../';
}


$currentDir = $_SESSION['currentDir'];

if (isset($isLoggedIn) && $isLoggedIn) {
    $isLoggedIn = true;
    $userName = $_SESSION['userName'];
    $isAdmin = $_SESSION['isAdmin'];
} else if (isset($_COOKIE['userId'])) {

    $userRepo = new UserRepo();
    $user = $userRepo->getUserById($_COOKIE['userId']);

    if ($user != null) {
        $_SESSION['userLoggedIn'] = true;
        $_SESSION['userName'] = $user->getUserName();
        $_SESSION['userId'] = $user->getUserId();
        $_SESSION['isAdmin'] = $user->isAdmin();
        $_SESSION['isQuestionnaireFinished'] = true;
        $isLoggedIn = true;
        $userName = $user->getUserName();
        $isAdmin = $user->isAdmin();
    }
}

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <h3 class="text-white">Explore Space</h3>
    </a>

    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= $pathToRootScripts."index.php"; ?>">Home</a>
            </li>
            <?php if ($isLoggedIn && !$isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $pathToSpaceScripts . "getQuestions.php"; ?>">Get test</a>
                </li>
            <?php endif; ?>
            <?php if ($isLoggedIn && $isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $pathToSpaceScripts . "adminPage.php"; ?>">Create tests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $pathToSpaceScripts . "userList.php"; ?>">User list</a>
                </li>
            <?php endif; ?>
        </ul>

        <ul class="navbar-nav ml-auto">
            <?php if (!$isLoggedIn): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $pathToSpaceScripts . "register.php"; ?>">Sign in</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="<?=$pathToSpaceScripts . "loginLogout.php"?>">
                    <?php echo $isLoggedIn ? 'Log out' : 'Log in'; ?>
                </a>
            </li>
            <?php if ($isLoggedIn && !$isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?=$pathToSpaceScripts . "userPage.php"?>">User page <?php echo $userName; ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
