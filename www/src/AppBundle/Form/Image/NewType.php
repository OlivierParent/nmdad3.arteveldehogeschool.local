<?php

namespace AppBundle\Form\Image;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('file')
            ->add('categories', 'entity', [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' =>  false,
                'multiple' => true,
            ]);
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }

    /**
     * Form name.
     *
     * @return string
     */
    public function getName()
    {
        return 'appbundle_image_new';
    }
}
