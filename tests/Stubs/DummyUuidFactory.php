<?php

namespace AlbertCht\NotionAi\Tests\Stubs;

use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

class DummyUuidFactory extends UuidFactory
{
    public UuidInterface $uuid4;

    public function uuid4(): UuidInterface
    {
        return $this->uuid4;
    }
}
