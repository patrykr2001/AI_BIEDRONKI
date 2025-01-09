CREATE DATABASE IF NOT EXISTS plan;

CREATE TABLE IF NOT EXISTS worker (
  id_worker INT UNSIGNED NOT NULL AUTO_INCREMENT,
  worker VARCHAR(40) NOT NULL,
  worker_title VARCHAR(50),
PRIMARY KEY (id_worker)
);

CREATE TABLE IF NOT EXISTS student (
 id_student INT UNSIGNED NOT NULL AUTO_INCREMENT,
 student int UNSIGNED NOT NULL,
PRIMARY KEY (id_student)
);

CREATE TABLE IF NOT EXISTS groups (
 id_group INT UNSIGNED NOT NULL AUTO_INCREMENT,
 group_name VARCHAR(40) NOT NULL,
PRIMARY KEY (id_group)
);

CREATE TABLE IF NOT EXISTS tok (
 id_tok INT UNSIGNED NOT NULL AUTO_INCREMENT,
 tok VARCHAR(40) NOT NULL,
PRIMARY KEY (id_tok)
);
CREATE TABLE IF NOT EXISTS room (
 id_room INT UNSIGNED NOT NULL AUTO_INCREMENT,
 room VARCHAR(20) NOT NULL,
PRIMARY KEY (id_room)
);
CREATE TABLE IF NOT EXISTS subject (
  id_subject INT UNSIGNED NOT NULL AUTO_INCREMENT,
  subject TINYTEXT NOT NULL,
  subject_full TINYTEXT,
PRIMARY KEY (id_subject)
);
CREATE TABLE IF NOT EXISTS lesson_form (
 id_lesson_form TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
 form VARCHAR(20) NOT NULL,
PRIMARY KEY (id_lesson_form)
);
CREATE TABLE IF NOT EXISTS lesson_status (
 id_lesson_status TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
lesson_status VARCHAR(20) NOT NULL,
PRIMARY KEY (id_lesson_status)
);

CREATE TABLE IF NOT EXISTS lesson (
 id_lesson INT UNSIGNED NOT NULL AUTO_INCREMENT,
 start DATETIME NOT NULL,
end DATETIME NOT NULL,
hours BIT NOT NULL,
id_worker INT UNSIGNED NOT NULL ,
worker_cover INT UNSIGNED DEFAULT NULL, 
id_student INT UNSIGNED NOT NULL, 
id_group INT UNSIGNED NOT NULL, 
id_tok INT UNSIGNED NOT NULL ,
id_room INT UNSIGNED NOT NULL ,
id_subject INT UNSIGNED NOT NULL ,
id_lesson_form TINYINT UNSIGNED NOT NULL ,
id_lesson_status TINYINT UNSIGNED NOT NULL,
PRIMARY KEY (id_lesson),
FOREIGN KEY (id_worker) REFERENCES worker(id_worker),
FOREIGN KEY (worker_cover) REFERENCES worker(id_worker),
FOREIGN KEY (id_student) REFERENCES student(id_student),
FOREIGN KEY (id_group) REFERENCES groups(id_group),
FOREIGN KEY (id_tok) REFERENCES tok(id_tok),
FOREIGN KEY (id_room) REFERENCES room(id_room),
FOREIGN KEY (id_subject) REFERENCES subject(id_subject),
FOREIGN KEY (id_lesson_form) REFERENCES lesson_form(id_lesson_form),
FOREIGN KEY (id_lesson_status) REFERENCES lesson_status(id_lesson_status)
);

