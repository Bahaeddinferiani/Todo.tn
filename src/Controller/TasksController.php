<?php

namespace App\Controller;
use App\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class TasksController extends AbstractController
{
    public $data;
    #[Route('/task/create', name: 'create_task')]
    public function create(ManagerRegistry $doctrine,Request $request): Response
    {
        $user = $this->getUser();
        if( $user ==null){
            return $this->redirectToRoute('app_login');

        }
        $entityManager = $doctrine->getManager();

        $Task = new Task();
        $Task->setTITLE("");
        $Task->setDone(true);
        $Task->setDescription('');
        $Task->setOwneremail($user->getEmail());
 

        $form = $this->createFormBuilder($Task)
            ->add('title',TextType::class)
            ->add('description',TextType::class)
            ->add('Done', ChoiceType::class, [
    'choices'  => [
        'Yes' => true,
        'No' => false,
    ],
])
            ->add('save', SubmitType::class, ['label' =>'Add todo'])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $Task = $form->getData();

            $en = $entityManager;
            $en->persist($Task);
            $en->flush();

            return $this->redirectToRoute('task_show');
        }


        return $this->render('todo/create.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route('/task', name: 'task_show')]
    public function show(ManagerRegistry $doctrine,): Response
    {

        $user = $this->getUser();
        $tasks = $doctrine->getRepository(Task::class)->findBy(array('Owneremail' =>$user->getUserIdentifier()));
    
    return $this->render('todo/index.html.twig', [
            'todos' => $tasks,
        ]);

    }
      /**
     * @Route("/delete/{id}",name="delete")
     * @param $id
     */
    public function delete(ManagerRegistry $doctrine,$id){
        $entityManager = $doctrine->getManager();
        $todo = $entityManager->getRepository(Task::class)->find($id);

        if(!$todo){
            throw $this->createNotFoundException("Not found todo with id: " . $id);
        }
        $entityManager->remove($todo);
        $entityManager->flush();
        return $this->redirectToRoute('task_show');
    }
}
