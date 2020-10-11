<?php

namespace App\Factory;

use function count;
use Symfony\Component\Serializer\SerializerInterface;

final class EntityFactory
{
    private SerializerInterface $serializer;

    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    public function createFromJson(string $data, string $class, array $groups = [], array $context = [])
    {
        return $this->create($data, $class, 'json', $groups, $context);
    }

    public function createFromArray(array $data, string $class, array $groups = [], array $context = [])
    {
        return $this->create($data, $class, 'array', $groups, $context);
    }

    private function create($data, string $class, string $format, array $groups = [], array $context = [])
    {
        if (count($groups)) {
            $context['groups'] = $groups;
        }

        return $this->serializer->deserialize($data, $class, $format, $context);
    }
}
