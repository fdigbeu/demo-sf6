<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\InfosUser;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('app_infos_user');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/loaduser', name: 'app_load_user')]
    public function loaduser(UserPasswordHasherInterface $asher, ManagerRegistry $registry): RedirectResponse
    {
        $entityManager = $registry->getManager();

        $infosUsers = $registry->getRepository(InfosUser::class)->findAll();
        $tabEmails = ["@gmail.com", "@yahoo.com", "@laposte.com", "@voila.com", "@sfr.com", 
                        "@orange.fr", "@outlook.com", "@mailbox.org", "@hubspot.com", "@tutanota.org"];
        if($infosUsers && count($infosUsers) > 0){
            for($i=0; $i<count($infosUsers); $i++){
                $infosUser = $infosUsers[$i];
                if($infosUser && !$infosUser->getUser()){
                    $user = new User();
                    $user->setEmail(strtolower($infosUser->getLastname()).$tabEmails[rand(0, count($tabEmails)-1)]);
                    $user->setPassword($asher->hashPassword($user, $infosUser->getLastname()));
                    $user->setInfosUser($infosUser);
                    $entityManager->persist($user);
                }
            }
            $entityManager->flush();
            $this->addFlash('info', 'Vos données ont été enregistrées avec succès.');
        }
        return $this->redirectToRoute('app_login');
    }
}
