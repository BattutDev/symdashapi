<?php

namespace App\Tests\Functionnal;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class BasicTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $response = static::createClient()->request('GET', '/api/');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['message' => 'Hello World']);
    }
}
