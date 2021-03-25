CREATE TABLE IF NOT EXISTS @table@ (
	id int(11) NOT NULL AUTO_INCREMENT,
	header varchar(255) NOT NULL,
	expected varchar(1024) NOT NULL,
	unexpected varchar(1024) DEFAULT NULL,
	exact tinyint(1) DEFAULT 0,
	information_link varchar(1024) NOT NULL,
	information_description varchar(1024) NOT NULL,
	deprecated tinyint(1) DEFAULT 0,
	deprecated_alternative_name varchar(255) DEFAULT NULL,
	deprecated_alternative_link varchar(1024) DEFAULT NULL,
	last_updated timestamp NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (id),
	UNIQUE (header)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci

