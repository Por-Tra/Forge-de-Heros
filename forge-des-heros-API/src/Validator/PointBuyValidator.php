<?php

namespace App\Validator;

use App\Entity\Character;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PointBuyValidator extends ConstraintValidator
{
    /**
     * Tableau de coûts des caractéristiques (8 -> 0, 9 -> 1, etc.)
     */
    private const COST_TABLE = [
        8  => 0,
        9  => 1,
        10 => 2,
        11 => 3,
        12 => 4,
        13 => 5,
        14 => 7,
        15 => 9,
    ];

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PointBuy) {
            throw new UnexpectedTypeException($constraint, PointBuy::class);
        }

        if (!$value instanceof Character) {
            throw new UnexpectedTypeException($value, Character::class);
        }

        $stats = [
            'Strength'     => $value->getStrength(),
            'Dexterity'    => $value->getDexterity(),
            'Constitution' => $value->getConstitution(),
            'Intelligence' => $value->getIntelligence(),
            'Wisdom'       => $value->getWisdom(),
            'Charisma'     => $value->getCharisma(),
        ];

        $totalCost = 0;
        foreach ($stats as $name => $stat) {
            if ($stat === null || !isset(self::COST_TABLE[$stat])) {
                // Si une stat n'est pas dans la table de coûts, c'est une erreur
                $this->context->buildViolation('Stat invalide : {{ stat }} pour {{ name }}')
                    ->setParameter('{{ stat }}', (string) $stat)
                    ->setParameter('{{ name }}', $name)
                    ->addViolation();
                return;
            }
            $totalCost += self::COST_TABLE[$stat];
        }

        if ($totalCost !== $constraint->budget) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ total }}', (string) $totalCost)
                ->addViolation();
        }
    }
}
