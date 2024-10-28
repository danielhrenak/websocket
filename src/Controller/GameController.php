<?php

namespace App\Controller;

class GameController extends AppController
{
    public function index()
    {
        // Load the scores from sqlite database
        $db = new \PDO('sqlite:' . ROOT . '/sqlite/database.db');
        $stmt = $db->query("SELECT * FROM sessions");
        $scores = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->set(compact('scores'));
    }

    public function race()
    {

    }

    public function circle()
    {
        $this->viewBuilder()->disableAutoLayout();
    }

    public function fingers()
    {
        $this->viewBuilder()->disableAutoLayout();
    }

}
