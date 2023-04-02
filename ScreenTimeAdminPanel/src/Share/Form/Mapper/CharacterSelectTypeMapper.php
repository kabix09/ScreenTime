<?php
declare(strict_types=1);

namespace App\Share\Form\Mapper;

use App\Character\Entity\Character;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CharacterSelectTypeMapper implements DataMapperInterface
{

    /**
     * @inheritDoc
     */
    public function mapDataToForms(mixed $viewData, \Traversable $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof Character) {
            throw new UnexpectedTypeException($viewData, Character::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        $forms['role_name']->setData($viewData);
    }

    /**
     * @param Character $viewData
     * @inheritDoc
     */
    public function mapFormsToData(\Traversable $forms, mixed &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        // as data is passed by reference, overriding it will change it in
        // the form object as well
        // beware of type inconsistency, see caution below
        $viewData = $forms['role_name']->getData();
    }
}