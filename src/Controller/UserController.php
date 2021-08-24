<?php

namespace App\Controller;

use Cassandra\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('index.html.twig', ['users' => $users]);
    }
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $user = $this->getData();
            $user->getCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $user->getUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Usuário Criado com Sucesso');

            return $this->redirectToRoute('user_index');

        }

        return $this->render('create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, $id)
    {
        $user = $this->getDoctrine()->getDoctrine()->find($id);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $user = $this->getData();
            $user->getUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash('success', 'Usuário Editado com Sucesso');

            return $this->redirectToRoute('user_edit', ['id' => $id]);

        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'Usuário removido com sucesso !!');

        return $this->redirectToRoute('user_index');
    }
}
