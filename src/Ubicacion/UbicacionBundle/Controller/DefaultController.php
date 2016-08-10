<?php

namespace Ubicacion\UbicacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UbicacionBundle:Default:index.html.twig');
    }
}
