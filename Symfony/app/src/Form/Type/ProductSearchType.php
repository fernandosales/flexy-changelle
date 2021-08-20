<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class ProductSearchType extends AbstractType
{
    private $router;
    public function __construct( RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getParent()
    {
        return TextType::class;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'Hmm, user not found!',
            'attr' => [
                'class' => 'search-autocomplete',
                'data-autocomplete-url' => $this->router->generate('product_search')
            ]
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];
        $class = isset($attr['class']) ? $attr['class'].' ' : '';
        $class .= 'search-autocomplete';
        $attr['class'] = $class;
        $attr['data-autocomplete-url'] = $this->router->generate('product_search');
        $view->vars['attr'] = $attr;
    }
}