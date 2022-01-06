<?php

declare(strict_types=1);
namespace App\Controllers;

class TemplatesController
{
    public $loader;
    public $twig;

    function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader('../app/Views');
        $this->twig = new \Twig\Environment($this->loader, [
                        'cache' => '../cache',
                        'debug' => true,
                    ]);
        // $this->twig->addExtension(new \Twig\Twig_Extension_Debug());
    }
}