<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommentsControllerTest
 *
 * @author alexk
 */
use Guzzle\Http\Client;

class CommentsControllerTest extends \PHPUnit_Framework_TestCase {

    const URL_REGISTER = 'http://localhost:8000';
    const ENDPOINT  = '/comments/';
    private $endpoint;
    
    public function __construct($name = null, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->endpoint = self::ENDPOINT.'?url=test_'.time();
        
        $this->client = new Client(self::URL_REGISTER, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));
        
        $this->testList();
    }
    
    
    public function testList()  {
        $request = $this->client->get($this->endpoint,array('content-type' => 'application/json'));
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode());
        $data=json_decode($response->getBody(true));
        $this->assertGreaterThan(0, $data->page->id);
    }

    public function testPOSTValidate() {


        $request = $this->client->post($this->endpoint, array('content-type' => 'application/json'), json_encode(array()));
        $response = $request->send();

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testPost() {

        $data = array(
            'parent_id'=>1,
            'name' => "alexk",
            'email' => "alexk@alexk",
            'text' => "test body",
        );

        $request = $this->client->post($this->endpoint,array('content-type' => 'application/json'),json_encode($data));
        $response = $request->send();
        
        var_dump($response->getBody(true));
        
        $this->assertEquals(201, $response->getStatusCode());
    }

}
