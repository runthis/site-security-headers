<?php

namespace SiteSecurityHeaders\Controllers;

/**
 * Handle the logic of setting up and destroying the application
 * @author Robert Miller
 */
final class Application
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
	 * Create tables and add data
	 *
	 * @return void
	 */
	public function activate(): void
	{
		$this->schema('Up');
	}

	/**
	 * Reverse any database creation
	 *
	 * @return void
	 */
	public function deactivate(): void
	{
		$this->schema('Down');
	}

	/**
	 * Add the menu option to the admin page
	 *
	 * @return string
	 */
	public function add_menu(): string
	{
		return add_menu_page(
			'Site Security Headers',
			'Security Headers',
			'manage_options',
			'site-security-headers',
			[new \SiteSecurityHeaders\Routes\Web, 'home'],
			'dashicons-shield'
		);
	}

	/**
	 * A mini no-real-awesome-logic migrations script to import and drop models from the database
	 *
	 * @param string $type
	 *
	 * @return void
	 */
	private function schema(string $type): void
	{
		foreach (glob(\SiteSecurityHeaders\DATABASE . $type . '/' . '*.sql') as $file) {
			$schema = str_replace('@table@', str_replace('_data', '', $this->wpdb->prefix . basename($file, '.sql')), file_get_contents($file));
			$this->wpdb->query($schema);
		}
	}
}
