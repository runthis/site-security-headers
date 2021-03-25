<?php

namespace SiteSecurityHeaders\Routes;

/**
 * Very simple route object to load views and controllers for our very simple app
 * @author Robert Miller
 */
class Web
{
	/**
	 * Magic method to load the files needed, if valid
	 *
	 * @param string $method
	 * @param array $parameters
	 *
	 * @return void
	 */
	public function __call(string $method, array $parameters): void
	{
		if ($this->defined($method)) {
			require_once \SiteSecurityHeaders\CONTROLLERS . ucfirst($method) . '.php';
			require_once \SiteSecurityHeaders\VIEWS . $method . '.php';
		}
	}

	/**
	 * Determine if we have a view with the name being requested
	 *
	 * @param string $method
	 *
	 * @return boolean
	 */
	private function defined(string $method): bool
	{
		return in_array($method, array_map('basename', glob(\SiteSecurityHeaders\VIEWS . '*.php'), ['.php']));
	}
}
