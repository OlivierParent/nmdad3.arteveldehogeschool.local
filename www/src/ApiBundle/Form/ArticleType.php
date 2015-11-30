<?php

namespace ApiBundle\Form;

use AppBundle\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', null, ['required' => false])
            ->add('title')
            ->add('body')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'csrf_protection' => false,
            'validation_groups' => [
                'Default',
            ],
        ]);
    }

    /**
     * JSON object name.
     *
     * { article: { … } }
     *
     * @return string
     */
    public function getName()
    {
        return 'article';
    }
}
