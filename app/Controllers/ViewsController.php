<?php

declare(strict_types=1);
namespace App\Controllers;

class ViewsController extends Controller
{

    function showIndex(){
        $this->template->twig->display('navigation.html', array());
    }
}