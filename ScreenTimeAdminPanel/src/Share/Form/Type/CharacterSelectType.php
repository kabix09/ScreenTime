<?php
declare(strict_types=1);

namespace App\Share\Form\Type;

use App\Actor\Entity\Actor;
use App\Character\Entity\Character;
use App\Character\Repository\CharacterRepository;
use App\Share\Form\Mapper\CharacterSelectTypeMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterSelectType extends AbstractType
{
    private CharacterRepository $repository;

    /**
     * @param CharacterRepository $repository
     */
    public function __construct(CharacterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Actor $actor */
        $actor = $options['model'];

        $builder
            ->add('role_name',  EntityType::class, [
                'class' => Character::class,
                'choices' => $this->generateOptions($actor ? $actor->getCharacters()->toArray() : []),
            ])
            ->setDataMapper(new CharacterSelectTypeMapper())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
            'model' => null, // my additional option to fetch passed data which are other type than declared in 'data_class'
        ]);
    }

    // TODO this stil dot prevent situation where we have many assigned characters, in each field wil be displayed every one currently assigned to actor :c
    private function generateOptions(array $currentOptions)
    {
        return array_merge(
            $currentOptions,
            $this->repository->getCharactersUnassignedToAnyActor()
        );
    }
}