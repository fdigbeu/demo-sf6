<?php

namespace App\Controller;

use App\Entity\User;
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
        $tabAdmin = ["admin@gmail.com", "admin@yahoo.com", "admin@laposte.com", "admin@voila.com", "admin@sfr.com"];
        $tabUser = ["MARTINE@gmail.com", "CHAPPUIS@yahoo.com", "CHAPOLARD@laposte.com", "CHANU@voila.com", "BAG@sfr.com"];
        $tabPwd = ["MARTINE", "CHAPPUIS", "CHAPOLARD", "CHANU", "BAG"];
        for($i=0; $i<count($tabAdmin); $i++){
            $admin = new User();
            $admin->setEmail($tabAdmin[$i]);
            $admin->setPassword($asher->hashPassword($admin, "admin"));
            $admin->setRoles(["ROLE_ADMIN"]);
            $entityManager->persist($admin);
            //--
            $user = new User(); 
            $user->setEmail(strtolower($tabUser[$i]));
            $user->setPassword($asher->hashPassword($user, strtolower($tabPwd[$i])));
            $entityManager->persist($user);
        }
        $entityManager->flush();
        $this->addFlash('info', 'Vos données ont été enregistrées avec succès.');
        return $this->redirectToRoute('app_login');
    }
}
