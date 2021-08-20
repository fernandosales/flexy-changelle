<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\Type\TagType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Service\FileUploader;

/**
 * @Route("/tag")
 */
class TagController extends Controller
{

    /**
     * @Route("/", name="tag.index")
     */
    public function indexAction(PaginatorInterface $paginator, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
            ->from(Tag::class, 'p', 'p.id')
            ->select('p')
            ->getQuery();

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            5
        );

        $repo = $this->getDoctrine()->getManager()->getRepository(Tag::class);
        $tenTagsMostUseds = $repo->find10TagsMostUseds();

        return $this->render('tag/index.html.twig', [
            'pagination' => $pagination,
            'tagsMostUsed' => $tenTagsMostUseds
        ]);
    }

    /**
     * @Route("/create", name="tag.create")
     */
    public function createAction(Request $request, FileUploader $fileUploader)
    {
        return $this->update(new Tag(), $request, $fileUploader);
    }

    /**
     * @Route("/update/{id}", name="tag.update", requirements={"id":"\d+"}, defaults={"id":0})
     */
    public function updateAction(Tag $tag, Request $request)
    {
        return $this->update($tag, $request);
    }

    private function update(Tag $tag, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->create(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $tag = $form->getData();
                $em->persist($tag);
                $em->flush();
                return $this->redirectToRoute('tag.view', ['id' => $tag->getId()]);
        }

        return $this->render('tag/update.html.twig', [
            'entity' => $tag,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="tag.view", requirements={"id"="\d+"})
     */
    public function viewAction(Tag $product)
    {
        return $this->render('tag/view.html.twig', [
            'entity' => $product,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="tag.delete")
     */
    public function deleteUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Tag::class);

        $user = $repo->find($id);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('tag.index');

    }

    /**
     * @Route("/get-tags", methods="GET", name="get_tags")
     */
    public function getAllTags(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Tag::class);
        $tags = $repo->find10TagsMostUseds();
        return $this->json([
           $tags
        ], 200);
    }

}