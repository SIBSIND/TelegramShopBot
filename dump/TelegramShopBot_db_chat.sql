CREATE DATABASE TelegramShopBot
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE TelegramShopBot;

# Create table for city
CREATE TABLE db_city
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL
);
CREATE UNIQUE INDEX City_name_uindex ON db_city (name);

# Create table for Districts
CREATE TABLE db_district
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    city_id INT(11) NOT NULL,
    CONSTRAINT db_district_db_city_id_fk FOREIGN KEY (city_id) REFERENCES db_city (id)
);
CREATE INDEX db_district_db_city_id_fk ON db_district (city_id);

# Create table for Chats
CREATE TABLE db_chat
(
    id INT(11) PRIMARY KEY NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    username VARCHAR(255),
    city_id INT(11),
    `status` INT(11) DEFAULT '0',
    CONSTRAINT Users_City_id_fk FOREIGN KEY (city_id) REFERENCES db_city (id) ON DELETE SET NULL
);
CREATE UNIQUE INDEX db_user_id_uindex ON db_chat (id);
CREATE INDEX Users_City_id_fk ON db_chat (city_id);

# Create table for messages and updates
CREATE TABLE db_message
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    chat_id INT(11) NOT NULL,
    update_id INT(11),
    `date` INT(11) NOT NULL,
    `text` TEXT,
    message_id INT(11),
    CONSTRAINT db_message_db_chat_id_fk FOREIGN KEY (chat_id) REFERENCES db_chat (id)
);
CREATE INDEX db_message_db_chat_id_fk ON db_message (chat_id);

# Table for config
CREATE TABLE db_config
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `offset` INT(11) DEFAULT '0' NOT NULL
);