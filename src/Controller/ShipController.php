<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:56
 */

namespace Oacc\Controller;


use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ShipController
{
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

}

