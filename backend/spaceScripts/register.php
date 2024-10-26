<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Palmo\entitys\User;
    use Palmo\repository\impl\UserRepo;
    use Palmo\validators\TypeValidator;
    use Palmo\validators\ValidatorFactory;
    use Palmo\validators\impl\EmailValidator;
    use Palmo\validators\impl\PasswordValidator;
    use Palmo\validators\impl\UserNameValidator;

    session_start();
    $_SESSION['currentDir'] = basename(__DIR__);

    if (isset($_SESSION['userLoggedIn']) && $_SESSION['userLoggedIn']) {
        header("Location: ../index.php");
        exit;
    }

    $userNameError = $emailError = $passwordError = $repeatPasswordError = "";
    $regUserName = $email = $password = $confirmPassword = "";

    function getValidator(TypeValidator $typeValidator): EmailValidator | PasswordValidator | UserNameValidator
    {
        $factory = new ValidatorFactory($typeValidator);
        return $factory->getValidator();
    }
    function validateUserName($userName): bool {
        return getValidator(TypeValidator::UserName)->isValid($userName);
    }

    function validateEmail($email): bool {
        return getValidator(TypeValidator::Email)->isValid($email);
    }

    function validatePassword($password): bool {
        return getValidator(TypeValidator::Password)->isValid($password);
    }

    function validateConfirmPassword($password, $confirmPassword): bool {
        return $password === $confirmPassword;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $regUserName = trim($_POST['userName']);
        $email = trim($_POST['email']);

        $user = new User($regUserName, $email);
        $user->setPassword(trim($_POST['password']));
        $user->setConfirmPassword(trim($_POST['confirmPassword']));

        if (!validateUserName($user->getUserName())) {
            $userNameError = "Invalid user name";
        }

        if (!validateEmail($user->getEmail())) {
            $emailError = "Invalid email";
        }

        if (!validatePassword($user->getPassword())) {
            $passwordError = "Password must be at least 6 characters long";
        }

        if (!validateConfirmPassword($user->getPassword(), $user->getConfirmPassword())) {
            $repeatPasswordError = "The repeated password does not match the password";
        }
        if (empty($userNameError) && empty($emailError) && empty($passwordError) && empty($repeatPasswordError)) {
            $userRepo = new UserRepo();
            $emailError = $userRepo->saveData($user);
        }
        if (empty($emailError)) {
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
    <title>Sign Up</title>
</head>
    <body>
        <?php
            require __DIR__ . '/header.php';
        ?>
        <section class="vh-100">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-lg-12 col-xl-11">
                        <div class="card text-black" style="border-radius: 25px;">
                            <div class="card-body p-md-5">
                                <div class="row justify-content-center">
                                    <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>

                                        <form class="mx-1 mx-md-4" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="text" id="form3Example1c" class="form-control" name="userName" value="<?php echo htmlspecialchars($regUserName); ?>" required/>
                                                    <label class="form-label" for="form3Example1c">User name</label>
                                                    <p class="small fw-bold text-danger"><?php echo $userNameError; ?></p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="email" id="form3Example2c" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required/>
                                                    <label class="form-label" for="form3Example2c">Your Email</label>
                                                    <p class="small fw-bold text-danger"><?php echo $emailError; ?></p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="password" id="form3Example3c" class="form-control" name="password" required/>
                                                    <label class="form-label" for="form3Example3c">Password</label>
                                                    <p class="small fw-bold text-danger"><?php echo $passwordError; ?></p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="password" id="form3Example4c" class="form-control" name="confirmPassword" required/>
                                                    <label class="form-label" for="form3Example4c">Repeat your password</label>
                                                    <p class="small fw-bold text-danger"><?php echo $repeatPasswordError; ?></p>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                                <button type="submit" class="btn btn-primary btn-lg">Sign in</button>
                                            </div>

                                        </form>

                                    </div>
                                    <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp"
                                             class="img-fluid" alt="Sample image">
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
