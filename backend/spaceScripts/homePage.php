<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Palmo\Source\Db;
    $dbh = (new Db())->getHandler();
    $pictures = $dbh->query("
                    SELECT day, title, path
                    FROM pictureOfDay")->fetchAll(PDO::FETCH_ASSOC);

    if (!isset($_SESSION['current_index'])) {
        $index = array_search(date("d"), array_column($pictures, 'day'));
        $_SESSION['current_index'] = $index - 1;
    }

    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'prev') {
            if ($_SESSION['current_index'] === 0) {
                $_SESSION['current_index'] = count($pictures) - 1;
            } else {
                $_SESSION['current_index'] = max(0, $_SESSION['current_index'] - 1);
            }
        } elseif ($_GET['action'] === 'next') {
            if ($_SESSION['current_index'] === count($pictures) - 1) {
                $_SESSION['current_index'] = 0;
            } else {
                $_SESSION['current_index'] = min(count($pictures) - 1, $_SESSION['current_index'] + 1);
            }
        }
    }

    function getCurrentPicture() {
        $day = $_SESSION['current_index'] + 1;
        try {
            $dbh = (new Db())->getHandler();
            $pictures = $dbh->query("
                    SELECT path
                    FROM pictureOfDay
                    WHERE day = '{$day}'")->fetchAll(PDO::FETCH_COLUMN);
            return 'pictureOfDay/' . $pictures[0];

        } catch (PDOException $e) {
            //echo "Error: " . $e->getMessage();
            return '';
        }

    }

    function getCurrentTitle() {
        $day = $_SESSION['current_index'] + 1;
        try {
            $dbh = (new Db())->getHandler();
            $pictures = $dbh->query("
                    SELECT title
                    FROM pictureOfDay
                    WHERE day = '{$day}'")->fetchAll(PDO::FETCH_COLUMN);
            return $pictures[0];
        } catch (PDOException $e) {
            //echo "Error: " . $e->getMessage();
            return '';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Picture of the Day</title>
</head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <h4>Picture of the Day</h4>
            </div>
            <div class="row justify-content-center align-items-center">
                <button class="btn btn-secondary mr-2" onclick="prevPic()">&lt;</button>
                <div class="card" style="min-width: 850px;">
                    <img
                        src="<?php echo htmlspecialchars(getCurrentPicture()); ?>"
                        class="card-img-top"
                        alt="Picture of the Day"
                        style="max-width: 850px;"
                    >
                    <div class="card-body">
                        <h6 class="card-title text-center"><?php echo htmlspecialchars(getCurrentTitle()); ?></h6>
                    </div>
                </div>
                <button class="btn btn-secondary ml-2" onclick="nextPic()">&gt;</button>
            </div>
        </div>

        <script>
            function prevPic() {
                window.location.href = "?action=prev";
            }

            function nextPic() {
                window.location.href = "?action=next";
            }
        </script>
    </body>
</html>