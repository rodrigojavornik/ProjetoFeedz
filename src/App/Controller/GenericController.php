<?php

namespace App\Controller;


class GenericController
{
    protected $view;

    public function __construct($app)
    {
        $this->view = $app->getContainer()['view'];
    }
}