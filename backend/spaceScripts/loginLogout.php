<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Palmo\repository\impl\UserRepo;

    session_start();
    $_SESSION['currentDir'] = basename(__DIR__);
    $loginError = "";
    $titleLogInOut = isset($_SESSION['userLoggedIn']) && $_SESSION['userLoggedIn'] ? "Logout" : "Login";

    $email = "";
    $password = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['login'])) {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $userRepo = new UserRepo();
            $user = $userRepo->getUserByEmail($email, true);

            if ($user != null && password_verify($password, $user->getPasswordHash())) {
                $_SESSION['userLoggedIn'] = true;
                $_SESSION['userName'] = $user->getUserName();
                $_SESSION['userId'] = $user->getUserId();
                $_SESSION['isAdmin'] = $user->isAdmin();
                $_SESSION['isQuestionnaireFinished'] = true;
                setcookie('userId', $user->getUserId(), time() + (86400 * 2), "/");
                header("Location: ../index.php");
                exit;
            } else {
                $loginError = "You entered an incorrect email and password combination";
            }
        }

        if (isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            setcookie('userId', 1, time() -30, "/");
            header("Location: ../index.php");
            exit;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
    <body>
        <?php
            require __DIR__ . '/header.php';
        ?>
        <section class="vh-100">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-lg-12 col-xl-11">
                        <div class="card text-black" style="border-radius: 25px; padding-top: 50px; padding-bottom: 50px">
                            <div class="container-fluid h-custom">
                                <div class="row d-flex justify-content-center align-items-center h-100">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4"><?php echo htmlspecialchars($titleLogInOut); ?></p>
                                    <div class="col-md-9 col-lg-6 col-xl-5">
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                                             class="img-fluid" alt="Sample image">
                                    </div>
                                    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                            <?php if (!isset($_SESSION['userLoggedIn']) || !$_SESSION['userLoggedIn']): ?>
                                                <!-- Email input -->
                                                <div class="form-outline mb-4">
                                                    <input type="email" id="form3Example3" class="form-control form-control-lg"
                                                           placeholder="Enter a valid email address" name="email" value="<?php echo htmlspecialchars($email); ?>" required/>
                                                    <label class="form-label" for="form3Example3">Email address</label>
                                                </div>

                                                <!-- Password input -->
                                                <div class="form-outline mb-3">
                                                    <input type="password" id="form3Example4" class="form-control form-control-lg"
                                                           placeholder="Enter password" name="password" required/>
                                                    <label class="form-label" for="form3Example4">Password</label>
                                                </div>
                                                <p class="small fw-bold text-danger"><?php echo htmlspecialchars($loginError); ?></p>
                                            <?php endif; ?>
                                            <div class="text-center text-lg-start mt-4 pt-2">
                                                <?php if (!isset($_SESSION['userLoggedIn']) || !$_SESSION['userLoggedIn']): ?>
                                                    <button type="submit" name="login" class="btn btn-primary btn-lg"
                                                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                                                    <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="register.php"
                                                                                                                      class="link-danger">Register</a></p>
                                                <?php else: ?>
                                                    <button type="submit" name="logout" class="btn btn-primary btn-lg"
                                                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Logout</button>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </body>
</html>