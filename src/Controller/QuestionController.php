<?php


namespace App\Controller;


use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
//    /**
//     * @Route ("/questions/new-question")
//     */
//    public function newQuestion(EntityManagerInterface $entityManager)
//    {}



    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(QuestionRepository $repository)
    {
       $questions = $repository->findAllAskedOrderedByNewest();

        return $this->render('question/homepage.html.twig',[
            'questions'=>$questions,
        ]);
    }





    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show(Question $question){

        if($this->isDebug)
            $this->debugger->info("We are in debug mode");

        $answers=[
            'Make sure your cat is sitting `purrfectly` still xD',
            'Honestly , I like furry shoes better than MY cat',
            'Maybe ... you try saying the spell backwards???'
        ];

        //dump($question);
        return $this->render('question/show.html.twig',[
            'question' => $question,
            'answers' => $answers,
        ]);

    }

    /**
     * @Route ("/question/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request,
                EntityManagerInterface $entityManager,
    )
    {
        $direction=$request->request->get('direction');
        if($direction==='up')
            $question->upVote();
        elseif($direction==='down')
            $question->downVote();

        $entityManager->flush();

        $answers=[
            'Make sure your cat is sitting `purrfectly` still xD',
            'Honestly , I like furry shoes better than MY cat',
            'Maybe ... you try saying the spell backwards???'
        ];

        return $this->redirectToRoute('app_question_show',[
            'slug'=>$question->getSlug()
        ]);
    }
}