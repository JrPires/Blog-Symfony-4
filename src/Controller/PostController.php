<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
/**
 * @Route("/posts", name="post_")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create()
    {
        return $this->render('post/create.html.twig');

    }

    /**
     * @Route("/save", name="save")
     */
    public function save(Request $request)
    {
        $data = $request->request->all();

        $post = new Post();

        $post->setTitulo($data['titulo']);
        $post->setDescription($data['description']);
        $post->setContent($data['content']);
        $post->SetSlug($data['slug']);
        $post->setCreated_at(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $post->setUpdated_at(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($post);
        $doctrine->flush();

        $this->addFlash('success','Post criado com sucesso !!');

        return $this->redirectToRoute('post_index');

    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        return $this->render('post/edit.html.twig', [
            'post' => $post
        ]);

    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id)
    {
        $data = $request->request->all();

        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $post->setTitulo($data['titulo']);
        $post->setContent($data['content']);
        $post->setDescription($data['description']);
        $post->setSlug($data, ['slug']);
        $post->setCreatedAt($data['createad_at']);
        $post->setUpdatedAt($data, ['updated_at']);

        $doctrine = $this->getDoctrine()->getManeger();
        $doctrine->flush();

        $this->addFlash('success','Post atualizado com sucesso !!');

        return $this->redirectToRoute('post_index');

    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remover($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($post);
        $manager->flush();

        $this->addFlash('success', 'Post removido com sucesso !!');
        return $this->redirectToRoute('post_index');
    }
}

