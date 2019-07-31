<?php

namespace App\Controller;

use App\Service\WarExecutor;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param Request $request
     * @param WarExecutor $executor service for generating and executing war between armies
     * @return Response
     * @throws Exception
     */
    public function home(Request $request, WarExecutor $executor)
    {
        $armyOne = (int)$request->query->get('army1');
        $armyTwo = (int)$request->query->get('army2');
        if ($armyOne <= 0 || $armyTwo <= 0) {
            throw new BadRequestHttpException('Missing or incorrect parameters!');
        }

        return new Response($executor->executeBattle($armyOne, $armyTwo));
    }
}
