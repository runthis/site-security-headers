<?php

namespace SiteSecurityHeaders\Models;

ini_set('display_errors', 'On');

/**
 * Handle the logic of getting and processing the headers for the controller
 * @author Robert Miller
 */
final class SecurityHeaders
{
	/**
	 * @var string
	 */
	public $table = 'site_security_headers';

	/**
	 * @param $wpdb
	 */
	public function __construct($wpdb)
	{
		$this->wpdb = $wpdb;
	}

	/**
	 * Get the headers data from the database
	 *
	 * @return array
	 */
	public function get_security_headers(): array
	{
		$query = 'SELECT header, expected, unexpected, exact, information_link, information_description, deprecated, deprecated_alternative_name, deprecated_alternative_link FROM ' . $this->wpdb->prefix . $this->table;

		return $this->wpdb->get_results($query, 'ARRAY_A');
	}

	/**
	 * Process all the headers to determine what exists, what does not, what is valid and what is not
	 *
	 * @param array $security_headers
	 * @param array $headers
	 *
	 * @return array
	 */
	public function process_headers(array $security_headers, array $headers): array
	{
		foreach ($security_headers as $key => $security_header) {
			$headerkey = $security_header['header'];

			if (array_key_exists($headerkey, $headers)) {
				$security_headers[$key]['exists'] = true;
				$security_headers[$key]['actual'] = $headers[$headerkey];

				// get_headers follows redirects and appends, we only want the first value
				$headers[$headerkey] = self::one_header($headers, $headerkey);
				$security_headers[$key][$headerkey] = $headers[$headerkey];

				// Check if there is a direct expected value
				if (in_array(strtolower($headers[$headerkey]), json_decode($security_header['expected']))) {
					$security_headers[$key]['valid'] = true;

				// Could not find a direct value, check inside strings
				} elseif ($security_header['exact'] == 0) {
					foreach (json_decode($security_header['expected']) as $expected) {
						if (strpos(strtolower($headers[$headerkey]), $expected) !== false) {
							$security_headers[$key]['valid'] = true;
						}

						// Check if a number in the header is high enough
						if (strpos($expected, '>=') !== false) {
							$check_value = explode('>= ', $expected)[1];
							$header_number = filter_var(str_replace('-', '', $headers[$headerkey]), FILTER_SANITIZE_NUMBER_INT);
							if ($header_number >= $check_value) {
								$security_headers[$key]['valid'] = true;
							}
						}
					}
				}

				// If the header can have values we don't want, check for those
				if (isset($security_header['unexpected'])) {
					foreach (json_decode($security_header['unexpected']) as $unexpected) {
						if (strpos(strtolower($headers[$headerkey]), $unexpected) !== false) {
							$security_headers[$key]['valid'] = false;
							$security_headers[$key]['messages'][] = $unexpected;
						}
					}
				}
			}
		}

		return $security_headers;
	}

	/**
	 * Only get the first header
	 *
	 * @param array $headers
	 * @param string $key
	 *
	 * @return string
	 */
	private function one_header(array $headers, string $key): string
	{
		if (gettype($headers[$key]) == 'array') {
			$headers[$key] = $headers[$key][0];
		}

		return $headers[$key];
	}
}
