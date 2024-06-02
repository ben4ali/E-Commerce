-- Auteur: Antoine Langevin
-- Date  : 2023-10-20
-- Dernière mise à jour : 2023-12-02

CREATE
DATABASE IF NOT EXISTS boutique;

USE
boutique;

CREATE TABLE IF NOT EXISTS users
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    hashed_password
    VARCHAR
(
    255
) NOT NULL,
    email VARCHAR
(
    255
) UNIQUE NOT NULL,
    first_name VARCHAR
(
    255
),
    last_name VARCHAR
(
    255
),
    date_of_birth DATE,
    user_role ENUM
(
    'user',
    'admin',
    'merchant'
) DEFAULT 'user',
    register_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    profile_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    phone_number VARCHAR
(
    255
),
    profile_picture_url VARCHAR
(
    255
),
    warnings INT NOT NULL DEFAULT 0,
    isDeactivated TINYINT
(
    1
) DEFAULT 0
    );

CREATE TABLE IF NOT EXISTS categories
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    cat_name
    VARCHAR
(
    255
) NOT NULL UNIQUE
    );

CREATE TABLE IF NOT EXISTS addresses
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    street
    VARCHAR
(
    255
) NOT NULL,
    city VARCHAR
(
    255
) NOT NULL,
    province VARCHAR
(
    255
),
    country VARCHAR
(
    255
) NOT NULL,
    postal_code VARCHAR
(
    10
) NOT NULL
    );

CREATE TABLE IF NOT EXISTS businesses
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    owner_id
    INT
    NOT
    NULL,
    address_id
    INT
    NOT
    NULL,

    bs_name
    VARCHAR
(
    255
) NOT NULL UNIQUE,
    email VARCHAR
(
    255
) NOT NULL UNIQUE,
    url VARCHAR
(
    255
) NOT NULL,
    bs_type VARCHAR
(
    255
) NOT NULL,
    NE VARCHAR
(
    10
) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    FOREIGN KEY
(
    owner_id
) REFERENCES users
(
    id
),
    FOREIGN KEY
(
    address_id
) REFERENCES addresses
(
    id
)
    );

CREATE TABLE IF NOT EXISTS merchants
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    user_id
    INT
    NOT
    NULL,
    business_id
    INT
    NOT
    NULL,

    phone
    VARCHAR
(
    50
) NOT NULL UNIQUE,
    email VARCHAR
(
    255
) NOT NULL UNIQUE,
    FOREIGN KEY
(
    user_id
) REFERENCES users
(
    id
),
    FOREIGN KEY
(
    business_id
) REFERENCES businesses
(
    id
)
    );

CREATE TABLE IF NOT EXISTS items
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    merchant_id
    INT
    NOT
    NULL,
    business_id
    INT
    NOT
    NULL,
    category_id
    INT
    NOT
    NULL,

    item_name
    VARCHAR
(
    255
) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL
(
    10,
    2
) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_url VARCHAR
(
    255
),
    html_data TEXT NOT NULL,
    register_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    upc VARCHAR
(
    255
) NOT NULL,
    FOREIGN KEY
(
    merchant_id
) REFERENCES merchants
(
    id
),
    FOREIGN KEY
(
    business_id
) REFERENCES businesses
(
    id
),
    FOREIGN KEY
(
    category_id
) REFERENCES categories
(
    id
)
    );

CREATE TABLE IF NOT EXISTS transactions
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    user_id
    INT
    NOT
    NULL,
    billing_address
    INT
    NOT
    NULL,
    shipping_address
    INT
    NOT
    NULL,

    payment_method
    VARCHAR
(
    255
) NOT NULL,
    total DECIMAL
(
    10,
    2
) NOT NULL,
    FOREIGN KEY
(
    user_id
) REFERENCES users
(
    id
),
    FOREIGN KEY
(
    billing_address
) REFERENCES addresses
(
    id
),
    FOREIGN KEY
(
    shipping_address
) REFERENCES addresses
(
    id
)
    );

CREATE TABLE IF NOT EXISTS orders
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    user_id
    INT
    NOT
    NULL,
    delivery_address_id
    INT
    NOT
    NULL,
    transaction_id
    INT
    NOT
    NULL,

    created_at
    TIMESTAMP
    DEFAULT
    CURRENT_TIMESTAMP,
    order_status
    ENUM
(
    'Pending',
    'Shipped',
    'Delivered',
    'Canceled'
) NOT NULL DEFAULT 'Pending',
    special_instruction LONGTEXT NOT NULL,
    FOREIGN KEY
(
    user_id
) REFERENCES users
(
    id
),
    FOREIGN KEY
(
    delivery_address_id
) REFERENCES addresses
(
    id
),
    FOREIGN KEY
(
    transaction_id
) REFERENCES transactions
(
    id
)
    );

CREATE TABLE IF NOT EXISTS order_items
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    order_id
    INT
    NOT
    NULL,
    item_id
    INT
    NOT
    NULL,

    quantity
    INT
    NOT
    NULL,

    FOREIGN
    KEY
(
    order_id
) REFERENCES orders
(
    id
),
    FOREIGN KEY
(
    item_id
) REFERENCES items
(
    id
)
    );

CREATE TABLE IF NOT EXISTS loginopt
(
    opt VARCHAR
(
    255
) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY
(
    user_id
) REFERENCES users
(
    id
)
    );


CREATE TABLE IF NOT EXISTS bans
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    user_id
    INT
    NOT
    NULL,
    admin_id
    INT
    NOT
    NULL,

    created_date
    TIMESTAMP
    DEFAULT
    CURRENT_TIMESTAMP,
    reason
    TEXT
    NOT
    NULL,

    FOREIGN
    KEY
(
    user_id
) REFERENCES users
(
    id
),
    FOREIGN KEY
(
    admin_id
) REFERENCES users
(
    id
)
    );

CREATE TABLE IF NOT EXISTS appeals
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    user_id
    INT
    NOT
    NULL,
    ban_id
    INT
    NOT
    NULL,

    created_at
    TIMESTAMP
    DEFAULT
    CURRENT_TIMESTAMP,
    comment
    TEXT
    NOT
    NULL,

    FOREIGN
    KEY
(
    user_id
) REFERENCES users
(
    id
),
    FOREIGN KEY
(
    ban_id
) REFERENCES bans
(
    id
)
    );

CREATE TABLE IF NOT EXISTS reviews
(
    id
    INT
    AUTO_INCREMENT
    PRIMARY
    KEY,
    user_id
    INT
    NOT
    NULL,
    product_id
    INT
    NOT
    NULL,

    comment
    TEXT,
    number_stars
    INT
    NOT
    NULL,
    created_at
    TIMESTAMP
    DEFAULT
    CURRENT_TIMESTAMP,

    FOREIGN
    KEY
(
    user_id
) REFERENCES users
(
    id
),
    FOREIGN KEY
(
    product_id
) REFERENCES items
(
    id
)
    );

ALTER TABLE orders
    ADD CONSTRAINT fk_user_id
        FOREIGN KEY (user_id)
            REFERENCES users (id)
            ON DELETE CASCADE;