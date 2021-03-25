<?php

use PHPUnit\Framework\TestCase;
use SiteSecurityHeaders\Controllers\Application;
use SiteSecurityHeaders\Models\SecurityHeaders;

class SecurityHeadersTest extends \PHPUnit\Framework\TestCase
{
	protected function setUp(): void
	{
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

		// Clear the data on each run
		$pdo->query('TRUNCATE ' . $this->SecurityHeaders->table);

		// Add data back on each run
		$this->Application->activate();
	}

	public function testCanGetSecurityHeaders()
	{
		$security_headers = $this->SecurityHeaders->get_security_headers();

		$expected = 'strict-transport-security';
		$actual = $security_headers[0]['header'];

		$this->assertEquals($expected, $actual);
	}

	public function testCanProcessHeaders()
	{
		$security_headers = $this->SecurityHeaders->get_security_headers();
		$headers = [0 => 'HTTP/1.1 200 OK'];

		$processed = $this->SecurityHeaders->process_headers($security_headers, $headers);

		$this->assertIsArray($processed);
	}

	/**
	 * @dataProvider provider_validHeader
	 */
	public function testCanDetectValidHeader($header)
	{
		$security_headers = $this->SecurityHeaders->get_security_headers();
		$processed = $this->SecurityHeaders->process_headers($security_headers, $header);
		$missing_key = array_search(key($header), array_column($processed, 'header'));

		$this->assertArrayHasKey('valid', $processed[$missing_key]);
	}

	/**
	 * @dataProvider provider_missingHeader
	 */
	public function testCanDetectMissingHeader($headers, $missing)
	{
		$security_headers = $this->SecurityHeaders->get_security_headers();
		$processed = $this->SecurityHeaders->process_headers($security_headers, $headers);
		$missing_key = array_search($missing, array_column($processed, 'header'));

		$this->assertArrayNotHasKey('exists', $processed[$missing_key]);
	}

	/**
	 * @dataProvider provider_invalidHeader
	 */
	public function testCanDetectInvalidHeader($header)
	{
		$security_headers = $this->SecurityHeaders->get_security_headers();
		$processed = $this->SecurityHeaders->process_headers($security_headers, $header);
		$missing_key = array_search(key($header), array_column($processed, 'header'));

		$this->assertArrayNotHasKey('valid', $processed[$missing_key]);
	}

	/* Data Providers */

	/**
	 * for: testCanDetectValidHeader
	 */
	public function provider_validHeader()
	{
		return [
			[['x-frame-options' => 'SAMEORIGIN']],
			[['x-content-type-options' => 'nosniff']],
			[['content-security-policy' => "default-src 'none'; style-src 'self';"]],
			[['strict-transport-security' => 'max-age=31536000; includeSubDomains; preload']],
		];
	}

	/**
	 * for: testCanDetectMissingHeader
	 */
	public function provider_missingHeader()
	{
		return [
			[['x-frame-options' => 'SAMEORIGIN'], 'x-content-type-options'],
			[['x-content-type-options' => 'nosniff'], 'x-frame-options']
		];
	}

	/**
	 * for: testCanDetectInvalidHeader
	 */
	public function provider_invalidHeader()
	{
		return [
			[['x-frame-options' => ':SAMEORIGIN']],
			[['x-content-type-options' => 'nosesniff']],
			[['content-security-policy' => "default-src 'self'; script-src 'self';"]],
			[['strict-transport-security' => 'max-age=123; includeSubDomains; preload']],
		];
	}
}
