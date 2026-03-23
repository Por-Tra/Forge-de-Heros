<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class PointBuy extends Constraint
{
    public string $message = 'Les points de caractéristiques doivent totaliser exactement {{ total }} (budget de 27 points).';
    public int $budget = 27;

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
