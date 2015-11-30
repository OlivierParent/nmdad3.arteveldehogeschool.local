<?php

namespace ApiBundle\Form;

use AppBundle\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
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
//            ->add('uri', null, ['required' => false])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'csrf_protection' => false,
            'validation_groups' => [
                'Default',
            ],
        ]);
    }

    /**
     * JSON object name.
     *
     * { image: { … } }
     *
     * @return string
     */
    public function getName()
    {
        return 'image';
    }
}
