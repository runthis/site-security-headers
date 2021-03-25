<?php

spl_autoload_register(function ($class) {
	if (strpos($class, 'SiteSecurityHeaders') !== false) {
		$class = str_replace('SiteSecurityHeaders', 'App', $class);
		$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
		require_once $class . '.php';
	}
});
