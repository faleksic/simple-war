<?php

namespace App\Controller;

use App\Service\WarExecutor;
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
     * @return Response
     */
    public function home(Request $request, WarExecutor $executor)
    {
        $armyOne = (int)$request->query->get('army1');
        $armyTwo = (int)$request->query->get('army2');
        if ($armyOne <= 0 || $armyTwo <= 0) {
            throw new BadRequestHttpException('Missing or incorrect parameters!');
        }

        $executor->executeBattle($armyOne, $armyTwo);

        return new Response($armyOne . " " . $armyTwo);
    }
}
