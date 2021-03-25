<?php

use PHPUnit\Framework\TestCase;
use SiteSecurityHeaders\Controllers\Application;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
	protected function setUp(): void
	{
		// Setup our fake mysql instances instead of maintaining sqlite files
		$this->pdo = new \Vimeo\MysqlEngine\Php7\FakePdo('mysql:host=localhost;dbname=fake;charset=utf8', 'u', 'p');
		$this->pdo->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_LOWER);
		$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

		// Instantiate the controller instances with our fake pdo
		$this->Application = new Application($this->pdo);

		// Clear the data on each run
		$this->pdo->query('TRUNCATE ' . $this->Application->table);
	}

	public function testCanActivate()
	{
		$this->Application->activate();

		$expected = [['id' => 1]];
		$actual = $this->pdo->query('SELECT id FROM ' . $this->Application->table . ' LIMIT 1')->fetchAll(PDO::FETCH_ASSOC);

		$this->assertEquals($expected, $actual);
	}

	public function testCanActivateWithProperData()
	{
		$this->Application->activate();

		$expected = [['header' => 'permissions-policy']];
		$actual = $this->pdo->query('SELECT header FROM ' . $this->Application->table . ' WHERE id = 3')->fetchAll(PDO::FETCH_ASSOC);

		$this->assertEquals($expected, $actual);
	}

	public function testCanDeactivate()
	{
		$this->Application->activate();
		$this->Application->deactivate();

		$expected = [];
		$actual = $this->pdo->query('SELECT id FROM ' . $this->Application->table . ' LIMIT 1')->fetchAll(PDO::FETCH_ASSOC);

		$this->assertEquals($expected, $actual);
	}
}
