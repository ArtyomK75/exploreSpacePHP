<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Palmo\repository\impl\UserRepo;

    session_start();

    if (!(isset($_SESSION['userLoggedIn']) && $_SESSION['userLoggedIn']
        && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
        header("Location: ../index.php");
        exit;
    }
    $_SESSION['currentDir'] = basename(__DIR__);

    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";
    $searchField = $_GET['field'] ?? "username";
    $filterRole = $_GET['role'] ?? "";
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $userRepo = new UserRepo();
    $totalUsers = $userRepo->countUsers($searchQuery, $searchField, $filterRole);
    $users = $userRepo->getUsers($searchQuery, $searchField, $filterRole, $limit, $offset);

    $totalPages = ceil($totalUsers / $limit);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <title>User List</title>
    </head>
    <body>
        <?php
        require __DIR__ . '/header.php';
        ?>
        <div class="container mt-5">
            <h2 class="mb-4">User List</h2>

            <form method="get" class="form-inline mb-3">
                <select name="field" class="form-control mr-2">
                    <option value="username" <?php if ($searchField === 'username') echo 'selected'; ?>>Username</option>
                    <option value="email" <?php if ($searchField === 'email') echo 'selected'; ?>>Email</option>
                </select>

                <input type="text" name="search" class="form-control mr-2" placeholder="Search" value="<?php echo htmlspecialchars($searchQuery); ?>">

                <select name="role" class="form-control mr-2">
                    <option value="">All Roles</option>
                    <option value="admin" <?php if ($filterRole === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="user" <?php if ($filterRole === 'user') echo 'selected'; ?>>User</option>
                </select>

                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <a class="nav-link" href="<?= "editUser.php?userId=".$user->getUserId(); ?>"><?php echo $user->getUserId(); ?></a>
                        </td>
                        <td><?php echo htmlspecialchars($user->getUserName()); ?></td>
                        <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                        <td><?php echo htmlspecialchars($user->getRole()); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchQuery); ?>&field=<?php echo $searchField; ?>&role=<?php echo $filterRole; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </body>
</html>