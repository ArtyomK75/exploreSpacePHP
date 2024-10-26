<?php

namespace Palmo\entitys;

abstract class Question
{
    protected int $id;
    protected string $title;
    protected int $correctAnswer;

    public function __construct($title, $correctAnswer, $id) {
        $this->title = $title;
        $this->correctAnswer = $correctAnswer;
        $this->id = $id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getCorrectAnswer():int {
        return $this->correctAnswer;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setCorrectAnswer(int $correctAnswer): void
    {
        $this->correctAnswer = $correctAnswer;
    }



}