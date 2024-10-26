<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Palmo\repository\impl\QuestionRepo;
    use Palmo\repository\impl\AnswerRepo;
    use Palmo\entitys\impl\AnsweredQuestion;

    session_start();
    $_SESSION['currentDir'] = basename(__DIR__);
    if (!isset($_SESSION['userLoggedIn']) || !$_SESSION['userLoggedIn']) {
        header("Location: ../index.php");
        exit;
    }

    if (isset($_POST['getNewTest']) && $_POST['getNewTest']) {
        $_SESSION['getNewTest'] = true;
        $_SESSION['isQuestionnaireFinished'] = false;
        $_SESSION['answers'] = [];
        $isQuestionnaireFinished = false;
    }
    function getQuestions() {
        $needNewTest = array_key_exists('getNewTest', $_SESSION) ? $_SESSION['getNewTest'] : null;

        if (isset($needNewTest) && !$needNewTest) {
            return $_SESSION['currentQuestions'];
        }
        $_SESSION['getNewTest'] = false;
        $_SESSION['isQuestionnaireFinished'] = false;
        $questionsRepo = new QuestionRepo();
        $currentQuestions = $questionsRepo->getNotAnsweredQuestionsByUserId($_SESSION['userId']);
        $_SESSION['currentQuestions'] = $currentQuestions;
        return $currentQuestions;
    }

    function saveAnswers(): void
    {

        foreach ($_SESSION['answers'] as $questionId => $answer) {
            $currentQuestion = null;
            foreach ($_SESSION['currentQuestions'] as $question) {
                if ($question->getId() === $questionId) {
                    $currentQuestion = $question;
                    break;
                }
            }
            $answeredQuestion = new AnsweredQuestion(0, '', $currentQuestion->getCorrectAnswer(), $answer);
            $answeredQuestion->setUserId($_SESSION['userId']);
            $answeredQuestion->setQuestionId($questionId);
            $answerRepo = new AnswerRepo();
            $answerRepo->saveData($answeredQuestion);
        }
    }

    $currentPage = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $questionsPerPage = 3;
    $questions = getQuestions();
    $totalQuestions = count($questions);
    $totalPages = ceil($totalQuestions / $questionsPerPage);
    $startIndex = ($currentPage - 1) * $questionsPerPage;

    $currentQuestions = array_slice($questions, $startIndex, $questionsPerPage);
    $isQuestionnaireFinished = $_SESSION['isQuestionnaireFinished'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['answers'])) {
            foreach ($_POST['answers'] as $questionId => $answer) {
                $_SESSION['answers'][$questionId] = $answer;
            }
        }
        if (isset($_POST['saveAnswers']) && $_POST['saveAnswers']){
            $_SESSION['isQuestionnaireFinished'] = true;
            $isQuestionnaireFinished = true;
            saveAnswers();
        }
    }
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
                    <form method="POST">
                        <?php foreach ($currentQuestions as $index => $question): ?>
                            <div class="card mb-3" style="background-color: <?= isset($isQuestionnaireFinished) && $isQuestionnaireFinished ? ($question->getCorrectAnswer() == ($_SESSION['answers'][$question->getId()] ?? null) ? '#bdffda' : '#ffc1b8') : 'white'; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $question->getTitle() ?></h5>
                                    <div class="form-group">
                                        <?php for ($i = 1; $i <= 4; $i++): ?>
                                            <div class="form-check">
                                                <input id="<?= $question->getId().'_'.$i?>" class="form-check-input" type="radio" name="answers[<?= $question->getId() ?>]" value="<?= $i ?>"
                                                    <?= isset($_SESSION['answers'][$question->getId()]) && $_SESSION['answers'][$question->getId()] == $i ? 'checked' : ''?>
                                                    <?= isset($isQuestionnaireFinished) && $isQuestionnaireFinished ? ' disabled' : ''?>>
                                                <label for="<?= $question->getId().'_'.$i?>" class="form-check-label"><?= $question->getAnswerByIndex($i) ?></label>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>


                        <input type="hidden" name="page" id="pageInput" value="<?= $currentPage ?>">
                        <input type="hidden" name="getNewTest" id="getNewTestInput" value="<?= false ?>">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="saveAnswers" value="<?= true ?>"
                                <?= isset($isQuestionnaireFinished) && $isQuestionnaireFinished ? ' disabled' : ''?>>
                            Save answer options</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"
                                onclick="event.preventDefault();
                                        document.getElementById('getNewTestInput').value = <?= true ?>;
                                        document.forms[0].submit();">
                        Get new test</button>
                    </div>
                    <div class="text-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="#"
                                           onclick="event.preventDefault();
                                                   document.getElementById('pageInput').value = <?= $i ?>;
                                                   document.forms[0].submit();">
                                            <?= $i ?>
                                        </a>
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