CREATE DATABASE simple_container;

CREATE TABLE simple_container.users (
    id          INT             AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)    NOT NULL,
    email       VARCHAR(250)    NOT NULL,
    password    VARCHAR(250)    NOT NULL
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
