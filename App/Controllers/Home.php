<?php

namespace SiteSecurityHeaders\Controllers;

use SiteSecurityHeaders\Models\SecurityHeaders;

/**
 * Controller for the home view
 * @author Robert Miller
 */
class Home
{
	/**
	 * @var array
	 */
	public $site_headers;

	/**
	 * @var array
	 */
	public $security_headers;

	/**
	 * @param SecurityHeaders $SecurityHeaders
	 */
	public function __construct(SecurityHeaders $SecurityHeaders)
	{
		$this->SecurityHeaders = $SecurityHeaders;
	}

	/**
	 * Get the site headers, the security headers, process them
	 *
	 * @param string $url
	 *
	 * @return void
	 */
	public function init(string $url): void
	{
		$method = $this->set_http_method();
		$security_headers = $this->get_security_headers();

		$this->site_headers = $this->get_site_headers($url);
		$this->security_headers = $this->SecurityHeaders->process_headers($security_headers, $this->site_headers);
	}

	/**
	 * Helper method to collect only missing headers
	 *
	 * @return array
	 */
	public function headers_missing(): array
	{
		return array_filter($this->security_headers, function ($entry) {
			return !$entry['exists'];
		});
	}

	/**
	 * Helper method to collect only invalid headers
	 *
	 * @return array
	 */
	public function headers_invalid(): array
	{
		return array_filter($this->security_headers, function ($entry) {
			if ($entry['exists']) {
				return !$entry['valid'];
			}
		});
	}

	/**
	 * Calculate the grade/score based on validity
	 *
	 * @return array
	 */
	public function score(): array
	{
		$grades = [
			['grade' => 'F-', 'color' => 'red'],
			['grade' => 'F', 'color' => 'red'],
			['grade' => 'E', 'color' => 'red'],
			['grade' => 'D', 'color' => 'orange'],
			['grade' => 'C', 'color' => 'yellow'],
			['grade' => 'B', 'color' => 'yellow'],
			['grade' => 'A', 'color' => 'green'],
			['grade' => 'A+', 'color' => 'green'],
			['grade' => '?', 'color' => 'purple'],
		];

		// If we have no site headers, assume the site is broken and give it a question mark
		if (empty($this->site_headers)) {
			return $grades[(count($grades) - 1)];
		}

		return $grades[array_sum(array_column($this->security_headers, 'valid'))];
	}

	/**
	 * Get the sites headers
	 *
	 * @param string $url
	 *
	 * @return array
	 */
	private function get_site_headers(string $url): array
	{
		return array_change_key_case((@get_headers($url, true) ?: []), CASE_LOWER);
	}

	/**
	 * Get the models security headers
	 *
	 * @return array
	 */
	private function get_security_headers(): array
	{
		return $this->SecurityHeaders->get_security_headers();
	}

	/**
	 * Set the http method to head
	 *
	 * @return void
	 */
	private function set_http_method(): void
	{
		stream_context_set_default(['http' => ['method' => 'HEAD']]);
	}
}

global $wpdb;

$SecurityHeaders = new SecurityHeaders($wpdb);
$Home = new Home($SecurityHeaders);
$Home->init(get_site_url());
