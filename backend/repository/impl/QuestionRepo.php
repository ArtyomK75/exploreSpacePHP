<?php

namespace Palmo\repository\impl;

use Palmo\entitys\impl\AskedQuestion;
use Palmo\repository\Repo;
use Palmo\repository\Repository;
use PDO;
use PDOException;

class QuestionRepo extends Repository implements Repo
{
    public function getNotAnsweredQuestionsByUserId($userId): array
    {
        $currentQuestions = [];
        try {

            $stmt = $this->dbh->prepare("SELECT q.id FROM questions AS q 
                                        LEFT JOIN answers AS a
                                        ON q.id = a.question_id
                                             AND a.user_id = ?
                                    WHERE a.id IS NULL");
            $stmt->execute([$userId]);
            $notAnswered = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($notAnswered) <= 6) {
                $ids = array_column($notAnswered, 'id');
            } else {
                $ids = [];
                while (count($ids) < 6) {
                    $currentInt = mt_rand(0, count($notAnswered) - 1);
                    $questionId = $notAnswered[$currentInt]['id'];
                    if (!in_array($questionId, $ids)) {
                        $ids[] = $questionId;
                    }
                }
            }

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $stmt = $this->dbh->prepare("SELECT * FROM questions AS q WHERE q.id IN ({$placeholders})");
            $stmt->execute($ids);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $currentQuestions[] = new AskedQuestion($row['title'], $row['correctAnswer'],
                    $row['id'], $row['answer1'], $row['answer2'], $row['answer3'], $row['answer4']);
            }
        } catch (PDOException $e) {
            echo "Error connecting to the database: " . $e->getMessage();
        }
        return $currentQuestions;
    }

    public function saveData($entity): string
    {
        try {
            $stmt = $this->dbh->prepare("INSERT INTO questions (title, answer1, answer2, answer3, answer4, correctAnswer) 
                                   VALUES (:title, :answer1, :answer2, :answer3, :answer4, :correctAnswer)");
            $stmt->execute([
                'title' => $entity->getTitle(),
                'answer1' => $entity->getAnswer1(),
                'answer2' => $entity->getAnswer2(),
                'answer3' => $entity->getAnswer3(),
                'answer4' => $entity->getAnswer4(),
                'correctAnswer' => $entity->getCorrectAnswer(),
            ]);
            return '';
        } catch (PDOException $e) {
            return 'Error saving the question: ' . $e->getMessage();
        }
    }
}