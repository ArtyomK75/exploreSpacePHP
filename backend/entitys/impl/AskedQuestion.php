<?php

namespace Palmo\entitys\impl;

use Palmo\entitys\Question;

class AskedQuestion extends Question
{
    private string $answer1;
    private string $answer2;
    private string $answer3;
    private string $answer4;
    private int $selectedAnswer;


    public function __construct($title, $correctAnswer, $id, $answer1, $answer2, $answer3, $answer4)
    {
        parent::__construct($title, $correctAnswer, $id);
        $this->answer1 = $answer1;
        $this->answer2 = $answer2;
        $this->answer3 = $answer3;
        $this->answer4 = $answer4;
        $this->selectedAnswer = 0;
    }

    public function getAnswer1(): string
    {
        return $this->answer1;
    }

    public function getAnswer2(): string
    {
        return $this->answer2;
    }

    public function getAnswer3(): string
    {
        return $this->answer3;
    }

    public function getAnswer4(): string
    {
        return $this->answer4;
    }

    public function getAnswerByIndex(int $index): string
    {
        return match ($index) {
            1 => $this->answer1,
            2 => $this->answer2,
            3 => $this->answer3,
            4 => $this->answer4,
        };
    }

    public function setSelectedAnswer(int $selectedAnswer): void
    {
        $this->selectedAnswer = $selectedAnswer;
    }

    public function getSelectedAnswer(): int
    {
        return $this->selectedAnswer;
    }

    public function setAnswer1(string $answer1): void
    {
        $this->answer1 = $answer1;
    }

    public function setAnswer2(string $answer2): void
    {
        $this->answer2 = $answer2;
    }

    public function setAnswer3(string $answer3): void
    {
        $this->answer3 = $answer3;
    }

    public function setAnswer4(string $answer4): void
    {
        $this->answer4 = $answer4;
    }



}