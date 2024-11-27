DROP TABLE IF EXISTS USER;
DROP TABLE IF EXISTS SELLER;
DROP TABLE IF EXISTS ITEM;
DROP TABLE IF EXISTS PURCHASE;
DROP TABLE IF EXISTS CATEGORY;
DROP TABLE IF EXISTS CONDITION;
DROP TABLE IF EXISTS SIZE;
DROP TABLE IF EXISTS SHOPPING_CART;
DROP TABLE IF EXISTS PHOTO;
DROP TABLE IF EXISTS CHAT;
DROP TABLE IF EXISTS PAYMENT_OPTION;

PRAGMA foreign_keys = ON;

CREATE TABLE USER(
    realname VARCHAR(30),
    username VARCHAR(30) PRIMARY KEY,
    pw VARCHAR(30),
    email VARCHAR(30),
    phoneNo VARCHAR(30),
    userAddress VARCHAR(50),
    country VARCHAR(50),
    city VARCHAR(50),
    isAdmin BOOLEAN default false
);

CREATE TABLE ITEM(
    id INT PRIMARY KEY,
    name VARCHAR(30),
    details TEXT DEFAULT NULL,
    seller VARCHAR(30),
    postTimestamp DATETIME,
    price DECIMAL(10,2),
    brand VARCHAR(30), 
    model VARCHAR(30),
    category INT,
    releaseYear INT,
    condition INT,
    sold BOOLEAN,
    FOREIGN KEY(seller) REFERENCES USER(username) ON UPDATE CASCADE,
    FOREIGN KEY(category) REFERENCES CATEGORY(categoryId),
    FOREIGN KEY(condition) REFERENCES CONDITION(conditionId)
);

-- guardar so o path para a imagem no img, e ter uma pasta para servir de arquivo das imagens
CREATE TABLE PHOTO(
    photoId INT PRIMARY KEY,
    itemId INT,
    img VARCHAR(30),
    FOREIGN KEY(itemId) REFERENCES ITEM(id) ON DELETE CASCADE
);

CREATE TABLE SHOPPING_CART(
    username INT,
    itemId INT,
    PRIMARY KEY(username, itemId),
    FOREIGN KEY(username) REFERENCES USER(username) ON UPDATE CASCADE,
    FOREIGN KEY(itemId) REFERENCES ITEM(id) ON DELETE CASCADE
);

CREATE TABLE CHAT(
    id INT,
    msg TEXT,
    buyer VARCHAR(30),
    seller VARCHAR(30),
    postTimestamp DATETIME,
    PRIMARY KEY (id),
    FOREIGN KEY(buyer) REFERENCES USER(username),
    FOREIGN KEY(seller) REFERENCES USER(username)
);
-- cenas que o admin pode criar/alterar têm que ter uma table
CREATE TABLE CATEGORY(
    categoryId INT PRIMARY KEY,
    categoryName VARCHAR(30)
);

CREATE TABLE CONDITION(
    conditionId INT PRIMARY KEY,
    conditionName VARCHAR(30)
);

CREATE TABLE PAYMENT_OPTION(
    paymentOptionId INT PRIMARY KEY,
    paymentOptionName VARCHAR(30) 
);

CREATE TABLE PURCHASE(
    purchaseId INT PRIMARY KEY,
    buyer VARCHAR(30),
    item INT,
    purchaseDate DATE,
    FOREIGN KEY(buyer) REFERENCES USER(username) ON UPDATE CASCADE,
    FOREIGN KEY(item) REFERENCES ITEM(id) ON DELETE CASCADE
);

INSERT INTO CONDITION(conditionId, conditionName) VALUES
    (1,'Damaged'),
    (2,'Worn'),
    (3,'Used'),
    (4,'Barely Used'),
    (5,'New'),
    (6,'Unavailable');

INSERT INTO CATEGORY(categoryId, categoryName) VALUES
    (1,'Computers'),
    (2,'Smartphones'),
    (3,'Home Appliances'),
    (4,'Televisions'),
    (5,'Components'),
    (6, 'Tablets'),
    (7, 'Other');


/* INSERT AFTER LOADING THE SQL TO THE .DB AND CREATING THE xavisilva ACCOUNT

INSERT INTO ITEM(id, name, details, seller, postTimestamp, price, brand, model, category, releaseYear, condition, sold) VALUES
    (1, 'TV LED 4K','brand new tv! buy :) no scam','xavisilva','2010-04-16 14:00:00', 799.0,'Samsung','M123',4,1970,1, false),
    (2, 'IPHONE X', 'brand new phone! buy :) no scam','xavisilva','2023-04-16 14:00:00', 420.0,'Apple','X',2,1950,2, false),
    (3, 'ASUS VX3','brand new computer! buy :) no scam','xavisilva','2024-04-16 18:00:00', 260.0,'Asus','VX3',1,2000,3, false),
    (4, 'Samsung Fridge S96','brand new fridge! buy :) no scam','xavisilva','2020-04-16 17:00:00', 800.0,'Samsung','S96',3,2014,4, false),
    (5, 'HDMI 3.0 Cable','brand new hdmi! buy :) no scam','xavisilva','2023-04-16 07:00:00', 7.80,'Samsung','3.0',5,2018,5, false),
    (6, 'iPad 22X', 'brand new ipad! buy :) no scam','xavisilva','2022-07-16 10:00:00', 300.0,'Apple','22X',6,2020,2, false),
    (7, 'iPad 21X', 'brand new ipad! buy :) no scam','xavisilva','2022-03-16 10:00:00', 300.0,'Apple','21X',6,2021,2, false),
    (8, 'iPad 20X', 'brand new ipad! buy :) no scam','xavisilva','2023-03-16 10:00:00', 300.0,'Apple','20X',6,1976,2, false),
    (9, 'iPad 19X', 'brand new ipad! buy :) no scam','xavisilva','2024-03-16 10:00:00', 300.0,'Apple','19X',6,2010,2, false),
    (10, 'iPad 23y', 'brand new ipad! buy :) no scam','xavisilva','2024-04-16 10:00:00', 300.0,'Apple','23y',6,2004,2, false),
    (11, 'iPad 23x', 'brand new ipad! buy :) no scam','xavisilva','2021-03-16 10:00:00', 300.0,'Apple','23X',6,2009,2, false),
    (12, 'iPad 23X', 'brand new ipad! buy :) no scam','xavisilva','2021-03-16 10:00:00', 300.0,'Apple','23X',6,2023,2, false);

INSERT INTO PHOTO(photoId, itemid, img) VALUES
    (1,1,'/product_images/tv.jpg'),
    (3,2,'/product_images/phone.jpg'),
    (2,2,'/product_images/tv.jpg'),
    (4,3,'/product_images/computer.jpg'),
    (5,4,'/product_images/fridge.jpg'),
    (6,5,'/product_images/hdmi.jpg'),
    (7,6,'/product_images/ipad.jpg'),
    (8,7,'/product_images/ipad.jpg'),
    (9,8,'/product_images/ipad.jpg'),
    (10,9,'/product_images/ipad.jpg'),
    (11,10,'/product_images/ipad.jpg'),
    (12,11,'/product_images/ipad.jpg'),
    (13,12,'/product_images/ipad.jpg');

 */




