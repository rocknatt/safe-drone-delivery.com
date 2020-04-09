<?php namespace App;

use CodeIgniter\Test\FeatureTestCase;

class TestUserRole extends FeatureTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function test_index()
    {
    	$this->get_index();
    }

    public function get_index()
    {
    	$result = $this->call('get', site_url('userrole'));

    	$result->assertOK();
    }
}