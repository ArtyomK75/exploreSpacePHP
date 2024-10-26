<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Palmo\repository\impl\UserRepo;

    session_start();

    if (!(isset($_SESSION['userLoggedIn']) && $_SESSION['userLoggedIn']
        && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']
        && isset($_GET['userId']))) {
        header("Location: ../index.php");
        exit;
    }
    $_SESSION['currentDir'] = basename(__DIR__);

    $userRepo = new UserRepo();
    $user = $userRepo->getUserById($_GET['userId']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $user->setUserName($username);
    $user->setEmail($email);
    $user->setRole($role);

    $userRepo->updateUser($user);

    header("Location: userList.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit User</title>
</head>
    <body>
        <?php
        require __DIR__ . '/header.php';
        ?>
        <div class="container mt-5">
            <h2 class="mb-4">Edit User</h2>

            <form method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                           value="<?php echo htmlspecialchars($user->getUserName()); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role">
                        <option value="user" <?php if ($user->getRole() === 'User') echo 'selected'; ?>>User</option>
                        <option value="admin" <?php if ($user->getRole() === 'Admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="userList.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </body>
</html>