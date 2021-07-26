<?php


namespace App\Controller;


use App\Service\MarkdownHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class QuestionController extends AbstractController
{
    private $debugger;
    private $isDebug;

    public function __construct(LoggerInterface $debugger , bool $isDebug){
        $this->debugger = $debugger;
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        /*fancy way of using Twig service
        $html=$twigEnvironment->render('question/homepage.html.twig');
        return new Response($html);
        */
        return $this->render('question/homepage.html.twig');
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show($slug, MarkdownHelper $markdownHelper ,
                        bool $isDebug){
        if($this->isDebug)
            $this->debugger->info("We are in debug mode");

        $questionText='I\'ve been turned into a cat, any *thoughts* on how to turn back? While I\'m **adorable**, I don\'t really care for cat food.';
        dump($isDebug);
       $answers=[
           'Make sure your cat is sitting `purrfectly` still xD',
           'Honestly , I like furry shoes better than MY cat',
           'Maybe ... you try saying the spell backwards???'
       ];

        $parsedQuestionText=$markdownHelper->parse($questionText);

        return $this->render('question/show.html.twig',[
            'question' => ucwords(str_replace('-',' ',$slug)),
            'questionText' => $parsedQuestionText,
            'answers' => $answers,
        ]);

    }

}