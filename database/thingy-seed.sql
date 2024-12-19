DROP SCHEMA IF EXISTS lbaw2474 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2474;
SET search_path TO lbaw2474;

--
-- Drop any existing tables.
--

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS "ProductTemplate" CASCADE;
DROP TABLE IF EXISTS "Product" CASCADE;
DROP TABLE IF EXISTS "ProductImage" CASCADE;
DROP TABLE IF EXISTS "ProductPlatform" CASCADE;
DROP TABLE IF EXISTS "Review" CASCADE;
DROP TABLE IF EXISTS "Wishlist" CASCADE;
DROP TABLE IF EXISTS "ShoppingCart" CASCADE;
DROP TABLE IF EXISTS "Order" CASCADE;
DROP TABLE IF EXISTS "OrderItem" CASCADE;
DROP TABLE IF EXISTS "Payment" CASCADE;
DROP TABLE IF EXISTS "PaymentMethod" CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;

DROP TYPE IF EXISTS UserState;
DROP TYPE IF EXISTS Platform;
DROP TYPE IF EXISTS OrderStatus;
DROP TYPE IF EXISTS PaymentMethod;
DROP TYPE IF EXISTS CardIssuer;
DROP TYPE IF EXISTS NotificationType;

-- ---------------------
-- DEFINE TYPES (ENUMS)
-- ---------------------

CREATE TYPE UserState AS ENUM ('Active', 'Inactive', 'Blocked', 'Banned');
CREATE TYPE Platform AS ENUM ('PC', 'MacOS', 'Xbox', 'Playstation', 'Switch');
CREATE TYPE OrderStatus AS ENUM ('New', 'Shipped', 'Delivered', 'Canceled');
CREATE TYPE PaymentMethod AS ENUM ('Mbway', 'Paypal', 'Card');
CREATE TYPE CardIssuer AS ENUM ('Visa', 'MasterCard', 'Amex');
CREATE TYPE NotificationType AS ENUM ('NewReviewNotification', 'ProductAvailableNotification', 'ProductPriceChangeNotification', 'PaymentApprovedNotification', 'OrderStatusChangeNotification');


--
-- Create tables.
--
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(25) UNIQUE NOT NULL,
    password VARCHAR(256) NOT NULL,
    address TEXT NOT NULL,
    state UserState NOT NULL,
    profile_picture TEXT,
    first_login TIMESTAMP NOT NULL,
    last_login TIMESTAMP NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    CHECK (first_login < last_login),
    remember_token VARCHAR
);

CREATE TABLE "ProductTemplate" (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    developer TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE "Product" (
    id SERIAL PRIMARY KEY,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0 CHECK (price >= 0),
    stock INT NOT NULL,
    seller INT REFERENCES users(id),
    template INT REFERENCES "ProductTemplate"(id)
);

CREATE TABLE "ProductImage" (
    template INT REFERENCES "ProductTemplate"(id),
    index INT NOT NULL,
    path TEXT NOT NULL,
    PRIMARY KEY (template, index)
);

CREATE TABLE "ProductPlatform" (
    product INT REFERENCES "Product"(id),
    platform_name Platform NOT NULL,
    PRIMARY KEY (product, platform_name)
);

CREATE TABLE "Review" (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT now(),
    last_edited TIMESTAMP DEFAULT now(),
    template INT REFERENCES "ProductTemplate"(id),
    author INT REFERENCES users(id),
    CHECK (date < last_edited)
);

CREATE TABLE "Wishlist" (
    user_id INT REFERENCES users(id),
    product INT REFERENCES "Product"(id),
    PRIMARY KEY (user_id, product)
);

CREATE TABLE "ShoppingCart" (
    user_id INT REFERENCES users(id),
    product INT REFERENCES "Product"(id),
    added_on TIMESTAMP NOT NULL DEFAULT now(),
    quantity INT NOT NULL DEFAULT 1 CHECK (quantity > 0),
    PRIMARY KEY (user_id, product)
);

CREATE TABLE "PaymentMethod" (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    user_id INT REFERENCES users(id),
    type PaymentMethod NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100),
    card_number VARCHAR(25),
    card_issuer CardIssuer,
    expiry_month INT,
    expiry_year INT,
    cvv VARCHAR(4),
    CHECK (card_issuer IS NULL OR card_issuer IN ('Visa', 'MasterCard', 'Amex'))
);

CREATE TABLE "Payment" (
    id SERIAL PRIMARY KEY,
    payment_method INT REFERENCES "PaymentMethod"(id)
);

CREATE TABLE "Order" (
    id SERIAL PRIMARY KEY,
    ordered_on TIMESTAMP NOT NULL DEFAULT now(),
    delivered_on TIMESTAMP,
    shipping_address TEXT,
    status OrderStatus NOT NULL,
    buyer INT REFERENCES users(id),
    payment INT REFERENCES "Payment"(id)
);

CREATE TABLE "OrderItem" (
    id SERIAL PRIMARY KEY,
    order_id INT REFERENCES "Order"(id) ON DELETE CASCADE,
    product INT REFERENCES "Product"(id),
    quantity INT NOT NULL CHECK (quantity > 0),
    price DECIMAL(10, 2) NOT NULL CHECK (price >= 0)
);

CREATE TABLE notifications (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(255) NOT NULL,
    product INT REFERENCES "Product"(id) ON DELETE CASCADE,
    review INT REFERENCES "Review"(id) ON DELETE CASCADE,
    order_id INT REFERENCES "Order"(id) ON DELETE CASCADE,
    notifiable_id INT NOT NULL, -- Polymorphic relationship ID
    notifiable_type VARCHAR(255) NOT NULL, -- Polymorphic relationship type
    data JSON,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);



--
-- Insert value.
--

INSERT INTO users VALUES
(DEFAULT, 'Zé Pedro', 'zepedro@example.com', 'ze_ppp', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Avenida Espírito Santo', 'Active', NULL, NOW() - INTERVAL '2 years', NOW(), FALSE),
(DEFAULT, 'Nuno Rios', 'nunorios11@gmail.com', 'nunorios', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Rua Fernando Luís', 'Active', NULL, NOW() - INTERVAL '1 year', NOW(), TRUE),
(DEFAULT, 'Álvaro Pacheco', 'alvpacheco@outlook.com', '_alvaro', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Rua Padre António Vieira', 'Active', NULL, NOW() - INTERVAL '2 year', NOW(), TRUE);

INSERT INTO "ProductTemplate" (name, developer, description)
VALUES
('Minecraft', 'Mojang', 'Minecraft is the ultimate sandbox adventure, offering limitless creativity and exploration. Build anything from small cottages to vast cities, delve into mysterious caves, and face off against mobs in survival mode. Play solo or with friends across platforms, and let your imagination shape the world. With endless possibilities, every adventure is uniquely yours in Minecraft!'),
('Grand Theft Auto V', 'Rockstar Games', 'When a young street hustler, a retired bank robber and a terrifying psychopath find themselves entangled with some of the most frightening and deranged elements of the criminal underworld, the U.S. government and the entertainment industry, they must pull off a series of dangerous heists to survive in a ruthless city in which they can trust nobody, least of all each other.'),
('Until Dawn', 'Supermassive Games', 'Until Dawn is a gripping interactive survival horror game where every choice shapes the story. Trapped in an isolated mountain lodge, eight friends face a night of terror, and your decisions determine who survives. With cinematic visuals, a star-studded cast, and multiple endings, Until Dawn delivers an unforgettable experience where every moment counts.'),
('EAFC 25', 'Eletronic Arts', 'The latest football simulation game by EA Sports, featuring realistic gameplay and updated rosters.');

INSERT INTO "Product" (price, stock, seller, template)
VALUES
-- Minecraft
(29.99, 3, 1, 1), -- PC, MacOS
(19.99, 40, 2, 1), -- Xbox
(19.99, 35, 2, 1), -- PlayStation
(29.99, 20, 1, 1), -- Switch
-- GTA V
(49.99, 25, 1, 2), -- PC, MacOS
(39.99, 15, 2, 2), -- Xbox
(39.99, 10, 2, 2), -- Playstation
-- Until Dawn
(29.99, 15, 1, 3), -- Playstation
(29.99, 10, 2, 3), -- PC
-- EAFC 25
(59.99, 100, 1, 4), -- PC
(69.99, 80, 2, 4), -- Xbox
(69.99, 75, 2, 4), -- PlayStation
(59.99, 60, 1, 4); -- Switch


INSERT INTO "ProductImage" (template, index, path)
VALUES
-- Minecraft images
(1, 1, '/images/Minecraft.jpeg'),
(1, 2, '/images/Minecraft2.png'),
(1, 3, '/images/Minecraft3.png'),
(1, 4, '/images/Minecraft4.png'),

-- GTA V images
(2, 1, '/images/gtav.jpg'),
(2, 2, '/images/gtav2.jpg'),
(2, 3, '/images/gtav3.jpg'),
(2, 4, '/images/gtav4.jpg'),

-- Until Dawn images
(3, 1, '/images/untildawn.jpg'),
(3, 2, '/images/untildawn2.jpg'),
(3, 3, '/images/untildawn3.jpg'),
(3, 4, '/images/untildawn4.jpg'),

-- EAFC 25 images
(4, 1, '/images/eafc251.jpg'),
(4, 2, '/images/eafc252.jpg'),
(4, 3, '/images/eafc253.jpg'),
(4, 4, '/images/eafc254.jpg');

INSERT INTO "ProductPlatform" (product, platform_name)
VALUES
-- Minecraft
(1, 'PC'), (1, 'MacOS'), (2, 'Xbox'), (3, 'Playstation'), (4, 'Switch'),
-- GTA V
(5, 'PC'), (5, 'MacOS'), (6, 'Xbox'), (7, 'Playstation'),
-- Until Dawn
(8, 'Playstation'), (9, 'PC'),
-- EAFC 25 platforms
(10, 'PC'),
(11, 'Xbox'),
(12, 'Playstation'),
(13, 'Switch');


INSERT INTO "Review" (content, date, template, author)
VALUES
('Amazing game, soooo nostalgic!', NOW() - INTERVAL '10 days', 1, 1),
('Incredible game, but my son is way too young to play this', NOW() - INTERVAL '5 days', 2, 2),
('Scary and thrilling, recommend. Good service, key delivered right away.', NOW() - INTERVAL '15 days', 3, 3);

INSERT INTO "Wishlist" (user_id, product)
VALUES
(1, 5), -- Zé Pedro -> GTA V
(1, 2), -- Zé Pedro -> Minecraft
(2, 8), -- Nuno Rios -> Until Dawn
(3, 1); -- Álvaro Pacheco -> Minecraft

INSERT INTO "ShoppingCart" (user_id, product, added_on, quantity)
VALUES
(1, 1, NOW() - INTERVAL '1 day', 1), -- Zé Pedro -> Minecraft
(1, 5, NOW() - INTERVAL '1 day', 1), -- Zé Pedro -> GTA V
(2, 5, NOW() - INTERVAL '3 days', 2), -- Nuno Rios -> GTA V
(2, 8, NOW() - INTERVAL '3 days', 2), -- Nuno Rios -> Until Dawn
(3, 8, NOW() - INTERVAL '5 days', 1); -- Álvaro Pacheco -> Until Dawn

INSERT INTO "PaymentMethod" (name, user_id, type, phone, email, card_number, card_issuer, expiry_month, expiry_year, cvv)
VALUES
('Mom''s Visa', 1, 'Card', NULL, 'zepedro@example.com', '4111111111111111', 'Visa', 12, 2025, '123'),
('Nuno''s Paypal Account', 2, 'Paypal', NULL, 'nunorios11@gmail.com', NULL, NULL, NULL, NULL, NULL),
('MBWay', 3, 'Mbway', '912345678', NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO "Payment" (payment_method)
VALUES
(1), -- Zé Pedro -> Visa
(2), -- Nuno Rios -> Paypal
(3); -- Álvaro -> Mbway

INSERT INTO "Order" (ordered_on, shipping_address, status, buyer, payment)
VALUES
(NOW() - INTERVAL '5 days', 'Avenida Espírito Santo', 'Shipped', 1, 1), -- Zé Pedro
(NOW() - INTERVAL '3 days', 'Avenida Júlio César', 'New', 2, 2),         -- Nuno Rios
(NOW() - INTERVAL '2 days', 'Rua Padre António Vieira', 'Delivered', 3, 3); -- Álvaro Pacheco

INSERT INTO notifications (date, user_id, type, product, review, order_id, notifiable_id, notifiable_type, data)
VALUES
(NOW() - INTERVAL '2 days', 1, 'NewReviewNotification', 1, 1, NULL, 1, 'App\Models\User', '{"message": "A new review has been posted."}'),
(NOW() - INTERVAL '1 day', 2, 'OrderStatusChangeNotification', NULL, NULL, 2, 2, 'App\Models\User', '{"message": "Order status has changed."}'), -- Nuno Rios
(NOW() - INTERVAL '3 days', 3, 'PaymentApprovedNotification', NULL, NULL, 3, 3, 'App\Models\User', '{"message": "Your payment has been approved."}');

INSERT INTO "OrderItem" (order_id, product, quantity, price)
VALUES
(1, 1, 2, 29.99), -- 2x Minecraft
(1, 3, 1, 19.99), -- 1x GTA V
(2, 2, 1, 49.99), -- 1x GTA V
(3, 4, 1, 29.99); -- 1x Until Dawn
