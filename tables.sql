CREATE DATABASE telephone_dir CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE records
(
  id              int unsigned NOT NULL auto_increment,
  surname         char(40) NOT NULL,
  name            char(40) NOT NULL,
  pathronymic     char(40) NOT NULL,
  city_id         int unsigned NOT NULL,
  street_id       int unsigned NOT NULL,
  date_birth      date NOT NULL, 
  number          char(40) NOT NULL,
  PRIMARY KEY     (id),
  FOREIGN KEY     (city_id) REFERENCES cities (id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY     (street_id) REFERENCES streets (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE streets
(
  id              int unsigned NOT NULL auto_increment,
  street_name     char(40) NOT NULL UNIQUE,
  city_id         int unsigned NOT NULL,
  PRIMARY KEY     (id),
  FOREIGN KEY     (city_id) REFERENCES cities (id) ON DELETE CASCADE ON UPDATE CASCADE                  
)
ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE cities
(
  id              int unsigned NOT NULL auto_increment,
  city_name       char(40) NOT NULL UNIQUE,
  PRIMARY KEY     (id)
)
ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;


INSERT INTO `cities`(`id`, `city_name`) 
VALUES (1, 'Владивосток'), (2, 'Уссурийск');


INSERT INTO `streets`(`id`, `street_name`, `city_id`)
VALUES (1, 'Светланская', 1),
       (2, 'Борисенко', 1),
       (3, 'Бестужева', 2),
       (4, 'Боевая', 2);
  
