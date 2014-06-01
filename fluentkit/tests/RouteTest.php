<?php

class RouteTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testRootExample()
	{
		$crawler = $this->client->request('GET', '/');

		$this->assertResponseOk();
	}
    
    public function testLoginExample()
	{
		$crawler = $this->client->request('GET', '/login');

		$this->assertResponseOk();
	}
    
    public function testResetExample()
	{
		$crawler = $this->client->request('GET', '/login/reset');

		$this->assertResponseOk();
	}
    
    public function testTokenExample()
	{
		$crawler = $this->client->request('GET', '/login/token/sometokenvalue');
        $this->assertResponseOk();
	}
    
    public function testEmptyTokenExample()
	{
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
		$crawler = $this->client->request('GET', '/login/token');
	}
    
    public function testLogoutExample()
	{
		$crawler = $this->client->request('GET', '/logout');

        $this->assertRedirectedTo('/login');
	}
    
}