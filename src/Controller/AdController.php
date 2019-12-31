<?php

namespace App\Controller;


use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AdController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var AdRepository
     */
    private $adRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Environment $twig, AdRepository $adRepository ,EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->adRepository = $adRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/ads", name="ads-index")
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index( ): Response
    {
        //$repos = $this->getDoctrine()->getRepository(Ad::class);
        $html = $this->twig->render('ad/index.html.twig',[
            'ads'=> $this->adRepository->findAll()
        ]);
        return  new Response($html);
    }

    /**
     * Permet de créer une annonce
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(Request $request): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);


        if ( $form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image ){
                $image->setAd($ad);
                $this->entityManager->persist($image);
            }
            $ad->setAuthor($this->getUser());
            $this->entityManager->persist($ad);
           $this->entityManager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong>a bien été enregistrée ! "
            );

           return $this->redirectToRoute('ads_show', [
               'slug' => $ad->getSlug()
           ]);

        }

        $html = $this->twig->render('ad/new.html.twig', [
            'form' =>$form->createView()
        ]);
        return new Response($html);
    }

    /**
     *  Permet d'afficher le formulaire d'edition et d'editer une annonce
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()" ,message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifiée")
     * @param Request $request
     * @param Ad $ad
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit( Ad $ad ,Request $request): Response
    {
        $form = $this->createForm(AdType::class , $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image ){
                $image->setAd($ad);
                $this->entityManager->persist($image);
            }
            $this->entityManager->persist($ad);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Les modificatons de l'annonce <strong>{$ad->getTitle()}</strong>ont bien été modifiées ! "
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

       $html= $this->twig->render('ad/edit.html.twig',[
           'form'=> $form->createView(),
           'ad' =>$ad
       ]);
       return new Response($html);
    }

    /**
     * Permet d'afficher une seule annonce
     * @Route("/ads/{slug}", name="ads_show")
     * @param Ad $ad
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(Ad $ad): Response
    {
        //Je recupère l'annonce qui correspond au slug
        //$ad = $repos->findOneBySlug($slug);
        $html = $this->twig->render('ad/show.html.twig',[
            'ad' => $ad /*$this->adRepository->findOneBySlug($slug)*/
        ]);

       return new Response($html);
    }

    /**
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous n'avez pas le droit d'acceder à cette resource")
     * @param Ad $ad
     * @return Response
     */
    public function delete(Ad $ad): Response
    {
        $this->entityManager->remove($ad);
        $this->entityManager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée"
        );
       return $this->redirectToRoute('ads-index');

    }


}
