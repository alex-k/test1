<?php
use Guzzle\Http\Client;
class FooTest extends PHPUnit_Framework_TestCase
{
    public function testStatus()
    {
        $client = new Client('http://localhost:8000');
        $request = $client->get('index/list.json');
        $response = $request->send();
        $decodedResponse = $response->json();
        var_dump($decodedResponse);
        $this->assertEquals($decodedResponse['message'], 'index page');
    }
    
}