<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
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
       $answers=[
           'Make sure your cat is sitting purrfectly still xD',
           'Honestly , I like furry shoes better than MY cat',
           'Maybe ... you try saying the spell backwards???'
       ];
        return $this->render('question/show.html.twig',[
            'question' => ucwords(str_replace('-',' ',$slug)),
            'answers' => $answers,
        ]);

    }

}