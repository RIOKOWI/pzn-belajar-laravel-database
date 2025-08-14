create database belajar_laravel_database;

use belajar_laravel_database;

CREATE TABLE categories (
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP
) engine innodb;

select * from categories;
desc categories;


-- COUNTER
CREATE TABLE counters (
    id      VARCHAR(100) NOT NULL PRIMARY KEY,
    counter int NOT NULL DEFAULT 0
) ENGINE innodb;

insert into counters(id, counter) VALUES('sample', 0);

select * from counters;