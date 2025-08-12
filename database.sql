create database belajar_laravel_database;

use belajar_laravel_database;

CREATE TABLE categories (
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP
) engine innodb;

desc categories;