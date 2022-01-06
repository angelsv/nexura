<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Controllers\TemplatesController;

class Controller
{
    protected $template;
    
    function __construct(){
        $this->template = new TemplatesController;
    }
}