<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FileType::class, array('label' => 'Выберите файл',
                 'row_attr' => [
                  'class' => 'form-name'
                ],
            ))
            ->add('extension', ChoiceType::class, [
                'choices' => [
                    'webp'   => 'webp',
                    'jpg'    => 'jpg',
                    'jpeg'   => 'jpeg',
                    'png'    => 'png',
                    'gif'    => 'gif',
                    'hdr'    => 'hdr',
                    'png00'  => 'png00',
                    'png8'   => 'png8',
                    'png24'  => 'png24',
                    'png32'  => 'png32',
                    'png48'  => 'png48',
                    'png64'  => 'png64',
                    'ppm'    => 'ppm',
                    'raf'    => 'raf',
                    'rgb'    => 'rgb',
                    'wpg'    => 'wpg',
                    'aai'    => 'aai',
                    'art'    => 'art',
                    'avi'    => 'avi',
                    'avs'    => 'avs',
                    'bmp'    => 'bmp',
                    'brf'    => 'brf',
                    'cip'    => 'cip',
                    'crw'    => 'crw',
                    'dcr'    => 'dcr',
                    'dds'    => 'dds',
                    'dng'    => 'dng',
                    'epi'    => 'epi',
                    'isobrl' => 'isobrl',
                    'jbig'   => 'jbig',
                    'jng'    => 'jng',
                ],
                'row_attr' => [
                  'class' => 'form-extension'
                ],
                'mapped' => false,
                'label'  => 'Выберите необходимый формат'
            ])
            ->add('range', RangeType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 100
                ],
                'row_attr' => [
                  'class' => 'form-range'
                ],
                'mapped' => false,
                'label' => 'Размер файла'
            ])
            ->add('save', SubmitType::class, array('label' => 'Конвертировать файл'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
