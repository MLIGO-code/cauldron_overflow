<?php


namespace App\Controller;


use App\Entity\Question;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;
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
     * @Route ("/questions/new-question")
     */
    public function newQuestion(EntityManagerInterface $entityManager)
    {
        $questionDetails = new Question();
        $questionDetails->setName("Missing Pants")
            ->setSlug('missing-pants-'.rand(0,1000))
            ->setQuestion(<<<EOF
Hi! So... I'm having a *weird* day. Yesterday, I cast a spell
to make my dishes wash themselves. But while I was casting it,
I slipped a little and I think `I also hit my pants with the spell`.
When I woke up this morning, I caught a quick glimpse of my pants
opening the front door and walking out! I've been out all afternoon
(with no pants mind you) searching for them.
Does anyone have a spell to call your pants back?
EOF
            );
        if(rand(0,10)>2) $questionDetails
            ->setAskedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1,100))));
        $entityManager ->persist($questionDetails);
        $entityManager -> flush();
        return new Response(sprintf('Well hello , the shiny new question is id #%d, slug %s',
        $questionDetails->getId(),
        $questionDetails->getSlug(),
        ));
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
                        HubInterface $sentryHub){

        if($this->isDebug)
            $this->debugger->info("We are in debug mode");

        $questionText='I\'ve been turned into a cat, any *thoughts* on how to turn back? While I\'m **adorable**, I don\'t really care for cat food.';

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