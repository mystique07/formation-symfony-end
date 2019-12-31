<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AccountController extends AbstractController
{
    /**
     * @var Environment
     */
    private  $twig;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager= $entityManager;

    }

    /**
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error =$authenticationUtils->getLastAuthenticationError();
        $username =$authenticationUtils->getLastUsername();


            $html = $this->twig->render('account/login.html.twig', [
                'hasError' => $error !== null,
                'username' => $username
            ]);

        return  new  Response($html);
    }

    /**
     * Permet de se connecter
     *
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
        //.. rien !
    }

    /**
     * Permet d'afficher le formulaire et d'enregistrer un utilisateur
     * @Route("/register", name="register_account")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function register(Request $request,  UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $hash =$encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
             $this->entityManager->persist($user);
           $this->entityManager->flush();


            $this->addFlash('success', 'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter .');

            return $this->redirectToRoute('account_login');
        }

        $html = $this->twig->render('account/register.html.twig', [
            'form' =>$form->createView()
        ]);

        return  new Response($html);
    }

    /**
     *  Permet d'afficher et de traiter le formulaire de modification de profile
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request):Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success','Les données du profil ont été enrégistree avec succès');
        }

        return  $this->render('account/profile.html.twig', [
            'form' =>$form->createView()
        ]);

    }

    /**
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $passwordUpdate = new PasswordUpdate();
        $user=$this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // 1. Verifier que l'ancien password du formulaire soit le meme que le password de l'utilisateur
            if ( !password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                    //Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe tapé n'est votre mot de passe"));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user,$newPassword );
                $user->setHash($hash);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', "Le mot de passe à été modifier avec succé");

                return $this->redirectToRoute('home');
            }

        }

        return $this->render('account/password.html.twig', [
            'form' =>$form->createView()
        ]);
    }


    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myAccount(): Response
    {
        return $this->render('user/index.html.twig', [
            'user' =>$this->getUser()
        ]);
    }
}
