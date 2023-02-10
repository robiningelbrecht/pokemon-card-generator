<?php

namespace App\Infrastructure\Serialization;

use Safe\Exceptions\JsonException;

class Json
{
    public static function encode(mixed $value, int $options = 0, int $depth = 512): string
    {
        try {
            return \Safe\json_encode($value, $options, $depth);
        } catch (JsonException $exception) {
            throw new JsonException($exception->getMessage().': '.\var_export($value, true), $exception->getCode(), $exception->getPrevious());
        }
    }

    public static function decode(string $json, bool $assoc = true, int $depth = 512, int $options = 0): mixed
    {
        return \Safe\json_decode($json, $assoc, $depth, $options);
    }
}
