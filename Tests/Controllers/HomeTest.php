<?php

use PHPUnit\Framework\TestCase;
use SiteSecurityHeaders\Controllers\Home;
use SiteSecurityHeaders\Controllers\Application;
use SiteSecurityHeaders\Models\SecurityHeaders;

class HomeTest extends \PHPUnit\Framework\TestCase
{
	protected function setUp(): void
	{
		// Setup our fake mysql instances instead of maintaining sqlite files
		// We need to duplicate a small wordpress functionality (get_results)
		// We can get away with a very quick anonymous class for the 2 lines we need
		$pdo = new class extends \Vimeo\MysqlEngine\Php7\FakePdo {
			public function __construct()
			{
				parent::__construct('mysql:host=localhost;dbname=fake;charset=utf8', 'u', 'p');
			}

			public function get_results($query, $order)
			{
				$query = $this->query($query);

				return $query->fetchAll(PDO::FETCH_ASSOC);
			}
		};

		$pdo->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_LOWER);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

		// Instantiate the controller and model instances with our fake pdo
		$this->Application = new Application($pdo);
		$this->SecurityHeaders = new SecurityHeaders($pdo);
		$this->Home = new Home($this->SecurityHeaders);

		// Clear the data on each run
		$pdo->query('TRUNCATE ' . $this->SecurityHeaders->table);

		// Add data back on each run
		$this->Application->activate();
	}

	public function testCanInit()
	{
		$this->Home->init('fake');
		$expected = $this->Home->security_headers;

		$this->assertIsArray($expected);
	}

	public function testCanGetHeadersMissing()
	{
		$this->Home->init('fake');
		$expected = $this->Home->headers_missing();

		$this->assertIsArray($expected);
	}

	public function testCanGetHeadersInvalid()
	{
		$this->Home->init('fake');
		$expected = $this->Home->headers_invalid();

		$this->assertIsArray($expected);
	}

	public function testCanGetScore()
	{
		$actual = $this->Home->score();
		$expected = ['grade' => '?', 'color' => 'purple'];

		$this->assertEquals($expected, $actual);
	}
}
