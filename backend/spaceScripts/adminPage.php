<?php
require __DIR__ . '/../vendor/autoload.php';
use Palmo\repository\impl\QuestionRepo;
use \Palmo\entitys\impl\AskedQuestion;
session_start();
if (!(isset($_SESSION['userLoggedIn']) && $_SESSION['userLoggedIn']
    && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
    header("Location: ../index.php");
    exit;
}

$_SESSION['currentDir'] = basename(__DIR__);
$errors = [];
$successMessage = '';
$question = new AskedQuestion(
    "",
    0,
    -1,
    "",
    "",
    "",
    "",
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question->setTitle(trim($_POST['question']));
    $question->setCorrectAnswer(isset($_POST['numberOfCorrectAnswer']) ? (int)$_POST['numberOfCorrectAnswer'] : 0);
    $question->setAnswer1(trim($_POST['answer1']));
    $question->setAnswer2(trim($_POST['answer2']));
    $question->setAnswer3(trim($_POST['answer3']));
    $question->setAnswer4(trim($_POST['answer4']));

    if (empty($question->getTitle())) {
        $errors[] = 'You need to specify a question.';
    }
    if (empty($question->getAnswer1())) {
        $errors[] = 'You need to specify a first answer.';
    }
    if (empty($question->getAnswer2())) {
        $errors[] = 'You need to specify a second answer.';
    }
    if (empty($question->getAnswer3())) {
        $errors[] = 'You need to specify a third answer.';
    }
    if (empty($question->getAnswer4())) {
        $errors[] = 'You need to specify a fourth answer.';
    }
    if ($question->getCorrectAnswer() === 0) {
        $errors[] = 'You need to specify number of correct answer.';
    }


    if (empty($errors)) {
        $questionsRepo = new QuestionRepo();
        $saveResult = $questionsRepo->saveData($question);
        if (empty($saveResult)) {
            $successMessage = 'Question was successfully recorded in db.';
        } else {
            $errors[] = $saveResult;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
    <body>
        <?php
            require __DIR__ . '/header.php';
        ?>
        <div class="container vh-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-12 col-xl-12">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Enter question for test</p>

                                    <?php if (!empty($successMessage)): ?>
                                        <div class="alert alert-success">
                                            <?= $successMessage ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($errors)): ?>
                                        <div class="alert alert-danger">
                                            <?= implode('<br>', $errors) ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST" id="inputQuestionForm" novalidate>
                                        <div class="form-group mb-4">
                                            <label for="question">Enter question here</label>
                                            <textarea class="form-control" id="question" name="question" rows="3"><?= htmlspecialchars($question->getTitle()) ?></textarea>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="answer1">Enter answer 1</label>
                                            <input type="radio" name="numberOfCorrectAnswer" value="1" <?= $question->getCorrectAnswer() === 1 ? 'checked' : '' ?>>
                                            <input type="text" class="form-control" id="answer1" name="answer1" value="<?= htmlspecialchars($question->getAnswer1()) ?>">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="answer2">Enter answer 2</label>
                                            <input type="radio" name="numberOfCorrectAnswer" value="2" <?= $question->getCorrectAnswer() === 2 ? 'checked' : '' ?>>
                                            <input type="text" class="form-control" id="answer2" name="answer2" value="<?= htmlspecialchars($question->getAnswer2()) ?>">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="answer3">Enter answer 3</label>
                                            <input type="radio" name="numberOfCorrectAnswer" value="3" <?= $question->getCorrectAnswer() === 3 ? 'checked' : '' ?>>
                                            <input type="text" class="form-control" id="answer3" name="answer3" value="<?= htmlspecialchars($question->getAnswer3()) ?>">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="answer4">Enter answer 4</label>
                                            <input type="radio" name="numberOfCorrectAnswer" value="4" <?= $question->getCorrectAnswer() === 4 ? 'checked' : '' ?>>
                                            <input type="text" class="form-control" id="answer4" name="answer4" value="<?= htmlspecialchars($question->getAnswer4()) ?>">
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-lg">Save question</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

