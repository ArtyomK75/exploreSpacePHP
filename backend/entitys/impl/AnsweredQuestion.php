<?php

namespace Palmo\entitys\impl;

use Palmo\entitys\Question;

class AnsweredQuestion extends Question
{
    private int $numberOfAnswer;
    private String $textOfCorrectAnswer;
    private String $textOfIncorrectAnswer;
    private int $questionId;
    private int $userId;

    public function __construct($id, $title, $correctAnswer, $numberOfAnswer, $textOfCorrectAnswer='', $textOfIncorrectAnswer='')
    {
        parent::__construct($title, $correctAnswer, $id);
        $this->numberOfAnswer = $numberOfAnswer;
        $this->textOfCorrectAnswer = $textOfCorrectAnswer;
        $this->textOfIncorrectAnswer = $textOfIncorrectAnswer;
    }
    public function getSelectedAnswer(): int
    {
        return $this->numberOfAnswer;
    }

    public function getTextOfCorrectAnswer(): string
    {
        return $this->textOfCorrectAnswer;
    }

    public function getTextOfIncorrectAnswer(): string
    {
        return $this->textOfIncorrectAnswer;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function setQuestionId(int $questionId): void
    {
        $this->questionId = $questionId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getNumberOfAnswer(): int
    {
        return $this->numberOfAnswer;
    }

}