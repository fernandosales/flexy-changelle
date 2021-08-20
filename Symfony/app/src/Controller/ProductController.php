<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\Type\ProductSearchType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Form\Type\ProductType;
use App\Service\FileUploader;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{

    /**
     * @Route("/", name="product.index")
     */
    public function indexAction(PaginatorInterface $paginator, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $form = $this->get('form.factory')->create(ProductSearchType::class);

        $qb->from(Product::class, 'p', 'p.id')
            ->select('p');

        if ($form->isSubmitted() && $form->isValid()) {
            $word = $form->getData()['search'];

            $qb->where('p.title LIKE :word')
                ->orWhere('p.discription LIKE :word')
                ->setParameter('word', $word);
        }

        $query = $qb->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );



        return $this->render('product/index.html.twig', [
            'pagination' => $pagination,
            'search' => $form->createView()
        ]);
    }

    /**
     * @Route("/create", name="product.create")
     */
    public function createAction(Request $request, FileUploader $fileUploader)
    {
        return $this->update(new Product(), $request, $fileUploader);
    }

    /**
     * @Route("/update/{id}", name="product.update", requirements={"id":"\d+"}, defaults={"id":0})
     */
    public function updateAction(Product $product, Request $request, FileUploader $fileUploader)
    {
        return $this->update($product, $request, $fileUploader);
    }

    private function update(Product $product, Request $request, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->create(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $product = $form->getData();

                //Prepare upload file
                /** @var Image[] $images */
                $images = $product->getImages();
                foreach($images as $image){
                    $fileName = $fileUploader->upload($image->getFile());
                    $image->setPath($fileName);
                }

                $em->persist($product);
                $em->flush();
                return $this->redirectToRoute('product.view', ['id' => $product->getId()]);
        }

        return $this->render('product/update.html.twig', [
            'entity' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="product.view", requirements={"id"="\d+"})
     */
    public function viewAction(Product $product)
    {

        return $this->render('product/view.html.twig', [
            'entity' => $product,
        ]);
    }

    /**
     * @Route("/get-products", methods="GET", name="product_search")
     */
    public function getProductsApi(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Product::class);
        $products = $repo->findAllMatching($request->query->get('query'));
        return $this->json([
            'products' => $products
        ], 200, [], ['groups' => ['main']]);
    }

    /**
     * @Route("/delete/{id}", name="product.delete")
     */
    public function deleteUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Product::class);

        $user = $repo->find($id);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('product.index');

    }

}