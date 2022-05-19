<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class ToggleController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/toggle", name="toggle", methods={"POST"})
     */
    public function checkToggle(Request $request) {

        $input = array();
        $input['value'] = $request->get('value');
        // TODO: validate input

        //$assertBool = new Assert\Type('boolean');

       // $validated = $request->validated();

        $value = $input['value'];

        if ($value == 'false') {
            $toggle = false;
        } else if ($value == 'true') {
            $toggle = true;
        } else {
            $toggle = NULL;
        }



        $session = $request->getSession();
        $session->set('toggled', $toggle);


        return $this->redirect($request->headers->get('Referer'));

    }
}
