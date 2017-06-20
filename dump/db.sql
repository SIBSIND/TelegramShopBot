CREATE DATABASE TelegramShopBot
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE TelegramShopBot;

CREATE TABLE TelegramShopBot.db_chat
(
  id         INT             NOT NULL
    PRIMARY KEY,
  first_name VARCHAR(255)    NOT NULL,
  username   VARCHAR(255)    NULL,
  city_id    INT             NULL,
  status     INT DEFAULT '0' NULL,
  CONSTRAINT db_user_id_uindex
  UNIQUE (id)
);

CREATE INDEX Users_City_id_fk
  ON db_chat (city_id);

CREATE TABLE TelegramShopBot.db_city
(
  id   INT AUTO_INCREMENT
    PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  CONSTRAINT City_name_uindex
  UNIQUE (name)
);

ALTER TABLE db_chat
  ADD CONSTRAINT Users_City_id_fk
FOREIGN KEY (city_id) REFERENCES TelegramShopBot.db_city (id)
  ON DELETE SET NULL;

CREATE TABLE TelegramShopBot.db_config
(
  id     INT AUTO_INCREMENT
    PRIMARY KEY,
  offset INT DEFAULT '0' NOT NULL
);

CREATE TABLE TelegramShopBot.db_district
(
  id      INT AUTO_INCREMENT
    PRIMARY KEY,
  name    VARCHAR(255) NOT NULL,
  city_id INT          NOT NULL,
  CONSTRAINT db_district_db_city_id_fk
  FOREIGN KEY (city_id) REFERENCES TelegramShopBot.db_city (id)
);

CREATE INDEX db_district_db_city_id_fk
  ON db_district (city_id);

CREATE TABLE TelegramShopBot.db_message
(
  id         INT AUTO_INCREMENT
    PRIMARY KEY,
  chat_id    INT  NOT NULL,
  update_id  INT  NULL,
  date       INT  NOT NULL,
  text       TEXT NULL,
  message_id INT  NULL,
  CONSTRAINT db_message_db_chat_id_fk
  FOREIGN KEY (chat_id) REFERENCES TelegramShopBot.db_chat (id)
);

CREATE INDEX db_message_db_chat_id_fk
  ON db_message (chat_id);

CREATE TABLE TelegramShopBot.db_order
(
  id             INT AUTO_INCREMENT
    PRIMARY KEY,
  chat_id        INT                    NOT NULL,
  product_id     INT                    NOT NULL,
  district_id    INT                    NULL,
  status         TINYINT(1) DEFAULT '0' NULL,
  payment_method VARCHAR(10)            NULL,
  comment        VARCHAR(100)           NULL,
  price          FLOAT                  NULL,
  CONSTRAINT db_order_db_chat_id_fk
  FOREIGN KEY (chat_id) REFERENCES TelegramShopBot.db_chat (id),
  CONSTRAINT db_order_db_district_id_fk
  FOREIGN KEY (district_id) REFERENCES TelegramShopBot.db_district (id)
);

CREATE INDEX db_order_db_chat_id_fk
  ON db_order (chat_id);

CREATE INDEX db_order_db_district_id_fk
  ON db_order (district_id);

CREATE INDEX db_order_db_product_id_fk
  ON db_order (product_id);

CREATE TABLE TelegramShopBot.db_product
(
  id    INT AUTO_INCREMENT
    PRIMARY KEY,
  name  VARCHAR(200) NOT NULL,
  price INT          NOT NULL
);

ALTER TABLE db_order
  ADD CONSTRAINT db_order_db_product_id_fk
FOREIGN KEY (product_id) REFERENCES TelegramShopBot.db_product (id)
  ON DELETE CASCADE;

