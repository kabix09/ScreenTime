<?php
declare(strict_types=1);

namespace App\Share\Form\Type;

use App\Character\Entity\Character;
use App\Movie\Entity\MovieCharacter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MovieCharacterType extends AbstractType
{
    public const MIN_TIME_ON_SCENE = 0;
    public const MAX_TIME_ON_SCENE = 18000; // 60 * 60 * 5 -> 5h

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('character', EntityType::class, [
                'label' => 'Character',
                'class' => Character::class,
                'choice_label' => function(Character $character) {
                    return sprintf('%s', $character->getRoleName());
                },
                'choice_value' => 'id',
            ])
            ->add('timeOnScene', IntegerType::class, [
                'label' => 'Time On Scene',
                'help' => 'Time On Scene (in minutes)',
                'attr' => [
                    'min' => self::MIN_TIME_ON_SCENE,
                    'max' => self::MAX_TIME_ON_SCENE,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MovieCharacter::class
        ]);
    }
}