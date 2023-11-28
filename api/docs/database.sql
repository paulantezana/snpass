CREATE TABLE roles (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  description varchar(64) NOT NULL,

  state tinyint(4) DEFAULT 1,
  updated_at datetime DEFAULT NULL,
  created_at datetime DEFAULT NULL,
  created_user varchar(64) DEFAULT '',
  updated_user varchar(64) DEFAULT ''
) ENGINE=InnoDB;

CREATE TABLE users (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_name varchar(64) NOT NULL,
  password varchar(64) NOT NULL,
  full_name varchar(255) NOT NULL,
  gender enum('0','1','2') DEFAULT '2',
  email varchar(64) DEFAULT '',
  phone varchar(32) DEFAULT '',
  role_id int(11) NOT NULL,

  state tinyint(4) DEFAULT 1,
  updated_at datetime DEFAULT NULL,
  created_at datetime DEFAULT NULL,
  created_user varchar(64) DEFAULT '',
  updated_user varchar(64) DEFAULT '',

  UNIQUE KEY user_name (user_name),
  UNIQUE KEY email (email),

  CONSTRAINT fk_users_roles FOREIGN KEY (role_id) REFERENCES roles (id)
) ENGINE=InnoDB;

CREATE TABLE passwords (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  web_site varchar(255) NOT NULL,
  user_name varchar(255) NOT NULL,
  pass varchar(255) NOT NULL,

  state tinyint(4) DEFAULT 1,
  updated_at datetime DEFAULT NULL,
  created_at datetime DEFAULT NULL,
  created_user varchar(64) DEFAULT '',
  updated_user varchar(64) DEFAULT ''
) ENGINE=InnoDB;

CREATE TABLE notes (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name varchar(128) DEFAULT '',
  description TEXT,

  state tinyint(4) DEFAULT 1,
  updated_at datetime DEFAULT NULL,
  created_at datetime DEFAULT NULL,
  created_user varchar(64) DEFAULT '',
  updated_user varchar(64) DEFAULT ''
) ENGINE=InnoDB;
