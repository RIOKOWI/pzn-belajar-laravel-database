create database belajar_laravel_database;

use belajar_laravel_database;

CREATE TABLE categories (
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP
) engine innodb;

DROP Table categories;

select * from categories;
desc categories;


-- COUNTER
CREATE TABLE counters (
    id      VARCHAR(100) NOT NULL PRIMARY KEY,
    counter int NOT NULL DEFAULT 0
) ENGINE innodb;

insert into counters(id, counter) VALUES('sample', 0);

select * from counters;

-- PRODUCTS 
CREATE TABLE products (
    id          VARCHAR(100) NOT NULL PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    description TEXT,
    price       int NOT NULL,   
    category_id VARCHAR(100) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    constraint fk_category_id FOREIGN KEY (category_id) REFERENCES categories(id)
) engine innodb;

