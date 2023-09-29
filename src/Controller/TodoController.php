<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if(!$session->has('todos')){
            $todos = [
                "achat" => "Acheter clé usb",
                "cours" => "Finaliser mon cours",
                "correction" => "Corriger mes examens"
            ];
            $session->set('todos', $todos);
        }
        
        return $this->render('todo/index.html.twig', []);
    }

    #[Route('/add/{key}/{value}', name: 'app_todo_add')]
    public function addTodo(Request $request, $key, $value): RedirectResponse
    {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(!isset($todos[$key])){
                $todos[$key] = $value;
                $session->set('todos', $todos);
                $this->addFlash("success", "La valeur « $key » a été ajoutée avec succès !");
            }
            else{
                $this->addFlash("danger", "La valeur « $key » existe déjà !");
            }
        }
        else{
            $this->addFlash("info", "Veuillez initialiser le tableau todo");
        }
        
        return $this->redirectToRoute('app_todo', []);
    }

    #[Route('/update/{key}/{value}', name: 'app_todo_update')]
    public function updateTodo(Request $request, $key, $value): RedirectResponse
    {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(isset($todos[$key])){
                $todos[$key] = $value;
                $session->set('todos', $todos);
                $this->addFlash("success", "La valeur « $key » a été modifiée avec succès !");
            }
            else{
                $this->addFlash("danger", "La valeur « $key » n'existe pas !");
            }
        }
        else{
            $this->addFlash("info", "Veuillez initialiser le tableau todo");
        }
        
        return $this->redirectToRoute('app_todo', []);
    }

    #[Route('/delete/{key}', name: 'app_todo_delete')]
    public function deleteTodo(Request $request, $key): RedirectResponse
    {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(isset($todos[$key])){
                unset($todos[$key]);
                $session->set('todos', $todos);
                $this->addFlash("success", "La valeur « $key » a été supprimée avec succès !");
            }
            else{
                $this->addFlash("danger", "La valeur « $key » n'existe pas !");
            }
        }
        else{
            $this->addFlash("info", "Veuillez initialiser le tableau todo");
        }
        
        return $this->redirectToRoute('app_todo', []);
    }

    #[Route('/multi/{number1?0<\d*>}/{number2?0<\d*>}', name: 'app_todo_multi')]
    public function multi($number1, $number2): Response
    {
        $result = $number1*$number2;
        return $this->render('todo/index.html.twig', [
            'number1' => $number1,
            'number2' => $number2,
            'result' => $result
        ]);
    }
}
