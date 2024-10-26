<?php

namespace Palmo\repository\impl;

use Palmo\entitys\impl\AnsweredQuestion;
use Palmo\entitys\Loggable;
use Palmo\repository\Repo;
use Palmo\repository\Repository;
use PDO;
use PDOException;
class AnswerRepo extends Repository implements Repo
{
    use Loggable;

    public function readData($userId): array
    {
        $currentAnswers = [];
        try {
            $stmt = $this->dbh->prepare("SELECT q.title, a.id, a.correct_answer,  a.selected_answer,
                                        CASE WHEN a.correct_answer = 1 THEN
                                            q.answer1
                                        WHEN a.correct_answer = 2 THEN
                                            q.answer2
                                        WHEN a.correct_answer = 3 THEN
                                            q.answer3
                                        WHEN a.correct_answer = 4 THEN
                                            q.answer4
                                        ELSE ''
                                        END AS  textOfCorrectAnswer,
                                        CASE WHEN a.selected_answer = 1 THEN
                                            q.answer1
                                        WHEN a.selected_answer = 2 THEN
                                            q.answer2
                                        WHEN a.selected_answer = 3 THEN
                                            q.answer3
                                        WHEN a.selected_answer = 4 THEN
                                            q.answer4
                                        ELSE ''
                                        END AS  textOfIncorrectAnswer
                                    FROM answers AS a
                                        INNER JOIN questions AS q
                                            ON a.question_id = q.id
                                    WHERE a.user_id = :userId
                                    ORDER BY 
                                        a.created_at");
            $stmt->execute(['userId' => $userId]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $currentAnswers[] = new AnsweredQuestion(
                    $row['id'],
                    $row['title'],
                    $row['correct_answer'],
                    $row['selected_answer'],
                    $row['textOfCorrectAnswer'],
                    $row['textOfIncorrectAnswer']
                );
            }
        } catch (PDOException $e) {
            echo "Error connecting to the database: " . $e->getMessage();
        }
        return $currentAnswers;
    }

    public function saveData($entity): void
    {
        try {
            $stmt = $this-> dbh->prepare("INSERT INTO answers (user_id, question_id, correct_answer, selected_answer)
                                            VALUES (:userId, :questionId, :correctAnswer, :selectedAnswer)");

            $stmt->execute(
                ['userId' => $entity->getuserId(),
                    'questionId' => $entity->getQuestionId(),
                    'correctAnswer' =>  $entity->getCorrectAnswer(),
                    'selectedAnswer' => $entity->getNumberOfAnswer()]);
            $this->log("The user with id {$entity->getUserId()}, was successfully saved answer to question with id {$entity->getQuestionId()}");
        } catch (PDOException $e) {
            echo "Error saving answers to the database: " . $e->getMessage();
        }
    }
}