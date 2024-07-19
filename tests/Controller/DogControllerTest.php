<?php


namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DogControllerTest extends WebTestCase {


    public function testGetAllDogsSuccess() {
        $client = static::createClient();
        $client->request('GET', '/api/dog');

        $this->assertResponseIsSuccessful();
        $json = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Rex', $json[1]['name']);
    }
}