CREATE DATABASE votingsystem22 /*!40100 DEFAULT CHARACTER SET latin1 */;

USE votingsystem22;

CREATE TABLE projects (
 project_id int(10) unsigned NOT NULL AUTO_INCREMENT,
 titel varchar(400) NOT NULL,
 keywords varchar(400) NOT NULL,
 abstract varchar(600) NOT NULL,
 description text NOT NULL,
 degree tinyint(4) NOT NULL,
 skills varchar(100) NOT NULL,
 order_id int(10) unsigned NOT NULL,
 PRIMARY KEY (project_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE project_order (
 order_id int(10) unsigned NOT NULL AUTO_INCREMENT,
 project_id int(10) unsigned NOT NULL,
 teacher1_id int(10) unsigned NOT NULL,
 teacher2_id int(10) unsigned NOT NULL,
 teacher3_id int(10) unsigned NOT NULL,
 teacher4_id int(10) unsigned NOT NULL,
 teacher5_id int(10) unsigned NOT NULL,
 PRIMARY KEY (order_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE student (
 student_id int(10) unsigned NOT NULL AUTO_INCREMENT,
 full_name varchar(100) NOT NULL,
 email varchar(100) NOT NULL,
 studyfield varchar(100) NOT NULL,
 degree tinyint(4) NOT NULL,
 skills varchar(100) NOT NULL,
 matrikulation int(11) NOT NULL,
 crypt_id char(16) NOT NULL,
 active tinyint(1) NOT NULL,
 PRIMARY KEY (student_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE student_order (
 order_id int(10) unsigned NOT NULL AUTO_INCREMENT,
 student_id int(10) unsigned NOT NULL,
 project1_id int(10) unsigned NOT NULL,
 project2_id int(10) unsigned NOT NULL,
 project3_id int(10) unsigned NOT NULL,
 PRIMARY KEY (order_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE teacher (
 teacher_id int(10) unsigned NOT NULL AUTO_INCREMENT,
 full_name varchar(100) NOT NULL,
 email varchar(100) NOT NULL,
 pw char(64) NOT NULL,
 PRIMARY KEY (teacher_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO teacher (
  full_name,
  email,
  pw
) VALUES (
  'Administrator',
  'Admin@vs.de',
  '$2y$10$OM77cO7ngJjAW/FsvYrdcuDX3uccqgNl51jpyk4Oj/wzIFWQhfdmW'
); /*$2y$10$OM77cO7ngJjAW/FsvYrdcuDX3uccqgNl51jpyk4Oj/wzIFWQhfdmW = hash for 123*/
