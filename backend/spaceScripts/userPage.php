<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Palmo\repository\impl\AnswerRepo;

    session_start();
    $_SESSION['currentDir'] = basename(__DIR__);

    if (!isset($_SESSION['userLoggedIn']) || !$_SESSION['userLoggedIn']) {
        header("Location: ../index.php");
        exit;
    }

    if (isset($_POST['refreshAnswers']) && $_POST['refreshAnswers']) {
        $_SESSION['getNewAnswers'] = true;
    }

    function getAnswers() {

        if (isset($_SESSION['getNewAnswers']) && $_SESSION['getNewAnswers']) {
            $_SESSION['currentAnswers'] = null;
            $_SESSION['getNewAnswers'] = false;
        }

        if (isset($_SESSION['currentAnswers'])) {
            return $_SESSION['currentAnswers'];
        }
        $answerRepo = new AnswerRepo();
        $currentAnswers = $answerRepo->readData($_SESSION['userId']);
        $_SESSION['currentAnswers'] = $currentAnswers;
        return $currentAnswers;
    }

    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $questionsPerPage = 5;
    $answers = getAnswers();
    $totalAnswers = count($answers);
    $totalPages = ceil($totalAnswers / $questionsPerPage);
    $startIndex = ($currentPage - 1) * $questionsPerPage;
    $currentAnswers = array_slice($answers, $startIndex, $questionsPerPage);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
    <body>
        <?php
            require __DIR__ . '/header.php';
        ?>
        <div class="container vh-100 justify-content-center align-items-center" style="margin-top: 10px">
            <div class="row w-100">
                <div class="col-lg-12 col-xl-12">
                    <?php foreach ($currentAnswers as $index => $answer): ?>
                        <div class="card mb-3" style="background-color: <?=  $answer->getCorrectAnswer() === $answer->getSelectedAnswer() ? '#bdffda' : '#ffc1b8'; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $answer->getTitle() ?></h5>
                                <div class="form-group">
                                    <?php if($answer->getCorrectAnswer() === $answer->getSelectedAnswer()): ?>
                                        <div class="form-check">
                                            <input id="<?= $answer->getId().'_'.$answer->getSelectedAnswer()?>" class="form-check-input" type="radio"
                                                   name="answers[<?= $answer->getId() ?>]" value="<?= $answer->getSelectedAnswer() ?>" checked disabled>
                                            <label for="<?= $answer->getId().'_'.$answer->getSelectedAnswer()?>" class="form-check-label"><?= $answer->getTextOfCorrectAnswer() ?></label>
                                        </div>
                                    <?php else: ?>
                                        <div class="form-check">
                                            <input id="<?= $answer->getId().'_'.$answer->getSelectedAnswer()?>" class="form-check-input" type="radio"
                                                   name="answers[<?= $answer->getId() ?>]" value="<?= $answer->getSelectedAnswer() ?>" checked disabled>
                                            <label for="<?= $answer->getId().'_'.$answer->getSelectedAnswer()?>" class="form-check-label"><?= $answer->getTextOfIncorrectAnswer() ?></label>
                                        </div>
                                        <div class="form-check">
                                            <input id="<?= $answer->getId().'_'.$answer->getCorrectAnswer()?>" class="form-check-input" type="radio"
                                                   name="answers[<?= $answer->getId() ?>]" value="<?= $answer->getCorrectAnswer() ?>" disabled>
                                            <label for="<?= $answer->getId().'_'.$answer->getCorrectAnswer()?>" class="form-check-label" style="background-color: #bdffda;">
                                                <?= $answer->getTextOfCorrectAnswer() ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <form method="POST" style="margin-top: 10px; margin-bottom: 10px">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="refreshAnswers" value="<?= true ?>">
                                Refresh answers</button>
                        </div>
                    </form>


                    <div class="text-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>