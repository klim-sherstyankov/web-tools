<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HashFormTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Текст',
                'required' => false,
            ])
            ->add('extension', ChoiceType::class, [
              'choices' => [
                'md5' => 'md5',
                'md2' => 'md2',
                'md4' => 'md4',
                'sha1' => 'sha1',
                'sha224' => 'sha224',
                'sha256' => 'sha256',
                'sha384' => 'sha384',
                'sha512' => 'sha512',
                'ripemd128' => 'ripemd128',
                'ripemd160' => 'ripemd160',
                'ripemd256' => 'ripemd256',
                'ripemd320' => 'ripemd320',
                'whirlpool' => 'whirlpool',
                'snefru' => 'snefru',
                'snefru256' => 'snefru256',
                'gost' => 'gost',
                'gost' => 'gost-crypto',
                'adler32' => 'adler32',
                'crc32' => 'crc32',
                'crc32b' => 'crc32b',
                'fnv132' => 'fnv132',
                'fnv1a32' => 'fnv1a32',
                'fnv164' => 'fnv164',
                'fnv1a64' => 'fnv1a64',
                'joaat' => 'joaat',
              ],
              'row_attr' => [
                'class' => 'form-extension'
              ],
              'mapped' => false,
              'label'  => 'Выберите необходимый формат'
            ])
            ->add('save', SubmitType::class, array('label' => 'Рассчитать Хэш текста'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
