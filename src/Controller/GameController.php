<?php
/**
 * Created by PhpStorm.
 * User: HP-DEV3
 * Date: 18/07/2017
 * Time: 13:41
 */

namespace Oacc\Controller;

use Doctrine\ORM\EntityManager;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;


class GameController
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var Guard
     */
    private $csrf;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GameController constructor.
     * @param Twig $view
     * @param Guard $csrf
     * @param EntityManager $em
     */
    public function __construct(Twig $view, Guard $csrf, EntityManager $em)
    {
        $this->view = $view;
        $this->csrf = $csrf;
        $this->em = $em;
    }

    public function gameAction(Request $request, Response $response, $args)
    {
        $squad_one_id = $request->getParam('team_one');
        $squad_two_id = $request->getParam('team_two');
        $squadron_one = $this->em->getRepository('Oacc\Entity\Squadron')->findOneBy($squad_one_id);
        $squadron_two = $this->em->getRepository('Oacc\Entity\Squadron')->findOneBy($squad_two_id);

        return $this->view->render(
            $response,
            'ship-ai.twig',
            [
                'squadron_one' => $squadron_one,
                'squadron_two' => $squadron_two,
            ]
        );
    }

}