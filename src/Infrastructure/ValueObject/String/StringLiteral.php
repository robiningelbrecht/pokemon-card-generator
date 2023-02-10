<?php

namespace App\Infrastructure\ValueObject\String;

interface StringLiteral
{
    public function __construct(string $string);
}
