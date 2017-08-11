<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:57
 */

namespace Oacc\Controller;

use Doctrine\ORM\EntityManager;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class MenuController
{
    /**
     * @var Twig
     */
    private $view;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Guard
     */
    private $csrf;

    /**
     * MenuController constructor.
     * @param Twig $view
     * @param Guard $csrf
     * @param EntityManager $em
     */
    public function __construct(Twig $view, Guard $csrf, EntityManager $em)
    {
        $this->view = $view;
        $this->em = $em;
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, Response $response, $args = []) {
        return $this->view->render(
            $response,
            'index.twig'
        );
    }

    public function indexAction(Request $request, Response $response, $args = []) {
        return $this->view->render(
            $response,
            'index.twig'
        );
    }

    public function selectSquadronsAction(Request $request, Response $response, $args = []) {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $squadrons = $this->em->getRepository('Oacc\Entity\Squadron')->findAll();

        return $this->view->render($response,'select-squadrons.twig', [
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $name,
            'value' => $value,
            'squadrons' => $squadrons
        ]);
    }
}