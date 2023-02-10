<?php

namespace App\Infrastructure\CQRS;

abstract class DomainCommand implements \JsonSerializable
{
    /** @var array<mixed> */
    protected array $metadata = [];

    /**
     * @param array<mixed> $metadata
     */
    public function setMetaData(array $metadata): void
    {
        $this->metadata = array_merge($this->metadata, $metadata);
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'commandName' => str_replace('\\', '.', static::class),
            'payload' => $this->getSerializablePayload(),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function getSerializablePayload(): array
    {
        $serializedPayload = [];
        foreach ((new \ReflectionClass($this))->getProperties() as $property) {
            $serializedPayload[$property->getName()] = $property->getValue($this);
        }

        return $serializedPayload;
    }
}
