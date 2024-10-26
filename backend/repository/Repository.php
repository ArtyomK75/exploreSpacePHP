<?php

namespace Palmo\repository;
use Palmo\Source\Db;

abstract class Repository
{
    protected $dbh;
    public function __construct() {
        $this->dbh = (new Db())->getHandler();
    }
}