<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        return new Response('What a bewiching controller we have conjured! ');
    }

    /**
     * @Route("/questions/{slug}")
     */

    public function show($slug){
        return new Response(sprintf(
            'I will put there picture of my foot after "%s"!',
            str_replace('-',' ',$slug)
        ));
    }

}