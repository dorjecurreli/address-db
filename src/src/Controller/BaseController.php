<?php

namespace App\Controller;

use App\Traits\Messages;
use App\Traits\Filter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    use Messages;

    use Filter;
}
