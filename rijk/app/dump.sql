CREATE TABLE users
(
    id         SERIAL PRIMARY KEY,
    nome       VARCHAR(50)  NOT NULL,
    email      VARCHAR(100) NOT NULL DEFAULT '',
    telefone   VARCHAR(20)           DEFAULT '',
    senha      VARCHAR(255)          DEFAULT '',
    nivel      INT          NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE chemical_treatment
(
    id               SERIAL PRIMARY KEY,
    name    VARCHAR NOT NULL DEFAULT '',
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE packaging
(
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL DEFAULT '',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE variety
(
    id               SERIAL PRIMARY KEY,
    name    VARCHAR NOT NULL DEFAULT '',
    slug    VARCHAR NOT NULL DEFAULT '',
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE category
(
    id               SERIAL PRIMARY KEY,
    name    VARCHAR NOT NULL DEFAULT '',
    slug    VARCHAR NOT NULL DEFAULT '',
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE sales_unit
(
    id      SERIAL PRIMARY KEY,
    type    VARCHAR DEFAULT NULL,
    info    VARCHAR DEFAULT NULL,
    created_at  TIMESTAMP NULL DEFAULT NULL,
    updated_at  TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE stock
(
    id          SERIAL PRIMARY KEY,
    quantity    VARCHAR DEFAULT NULL,
    package     VARCHAR NOT NULL DEFAULT '',
    created_at  TIMESTAMP NULL DEFAULT NULL,
    updated_at  TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE calibre
(
    id               SERIAL PRIMARY KEY,
    name    VARCHAR NOT NULL DEFAULT '',
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE products
(
    id SERIAL PRIMARY KEY,
    name    VARCHAR NOT NULL DEFAULT '',
    id_category     INT     DEFAULT NULL REFERENCES category(id),
    id_calibre     INT     DEFAULT NULL REFERENCES calibre(id),
    id_variety     INT     DEFAULT NULL REFERENCES variety(id),
    id_sales_unit     INT     DEFAULT NULL REFERENCES sales_unit(id),
    id_chemical_treatment    INT     DEFAULT NULL REFERENCES chemical_treatment(id),
    id_stock    INT     DEFAULT NULL REFERENCES stock(id),
    batch   name    VARCHAR NOT NULL DEFAULT '',
    maturity    TIMESTAMP NULL DEFAULT NULL,
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE customer_payment_type
(
    id SERIAL PRIMARY KEY,
    name    VARCHAR NOT NULL DEFAULT '',
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE customer_credit
(
    id SERIAL PRIMARY KEY,
    valor DECIMAL(8,2) DEFAULT NULL,
    days    INT,
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE customer_credit_days
(
    id SERIAL PRIMARY KEY,
    days    INT,
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE customer_category
(
    id SERIAL PRIMARY KEY,
    name    VARCHAR NOT NULL DEFAULT '',
    basic_discount    INT,
    cash_payment_discount  INT,
    goal_discount    INT,
    goal_introduction    INT,
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);




CREATE TABLE address_district
(
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL DEFAULT '',
    country VARCHAR NOT NULL DEFAULT '',
    region INT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE address_state
(
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL DEFAULT '',
    code VARCHAR NOT NULL DEFAULT '',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE address_city
(
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL DEFAULT '',
    id_state INT DEFAULT NULL REFERENCES address_state(id),
    id_district INT DEFAULT NULL REFERENCES address_district(id),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE address
(
    id SERIAL PRIMARY KEY,
    address_1    VARCHAR NOT NULL DEFAULT '',
    address_2    VARCHAR NOT NULL DEFAULT '',
    zipcode    VARCHAR NOT NULL DEFAULT '',
    id_city     INT     DEFAULT NULL REFERENCES address_city(id),
    id_state     INT     DEFAULT NULL REFERENCES address_state(id),
    id_district     INT     DEFAULT NULL REFERENCES address_district(id),
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE customer
(
    id SERIAL PRIMARY KEY,
    internal_code    INT,
    description    VARCHAR NOT NULL DEFAULT '',
    group_code    INT,
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE client
(
    id SERIAL PRIMARY KEY,
    id_salesman INT DEFAULT NULL REFERENCES users(id),
    id_category_customer INT DEFAULT NULL REFERENCES customer_category(id),
    full_name VARCHAR NOT NULL DEFAULT '',
    email VARCHAR NOT NULL DEFAULT '',
    telephone VARCHAR(20) DEFAULT ''
    fax VARCHAR(20) DEFAULT ''
    mobile VARCHAR(20) DEFAULT ''
    website VARCHAR NOT NULL DEFAULT '',
    relation_number INT NOT,
    bio VARCHAR NOT NULL DEFAULT '',
    id_address INT DEFAULT NULL REFERENCES address(id),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE discount
(
    id SERIAL PRIMARY KEY,
    value INT DEFAULT NULL,
    percentage INT DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE orders
(
    id SERIAL PRIMARY KEY,
    id_customer INT DEFAULT NULL REFERENCES users(id),
    value DECIMAL(8,2) DEFAULT NULL,
    value_total DECIMAL(8,2) DEFAULT NULL,
    discount INT DEFAULT NULL,
    order_number INT DEFAULT NULL,
    status INT DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

ALTER TABLE orders ADD FOREIGN KEY ("id_customer") REFERENCES users("id");

CREATE TABLE order_cart
(
    id SERIAL PRIMARY KEY,
    id_product INT DEFAULT NULL REFERENCES products(id),
    id_customer INT DEFAULT NULL REFERENCES users(id),
    id_package INT DEFAULT NULL REFERENCES products_packaging(id),
    value INT DEFAULT NULL,
    quantity INT DEFAULT NULL,
    early_discount INT DEFAULT NULL,
    order_number INT DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

ALTER TABLE order_cart ADD FOREIGN KEY ("id_product") REFERENCES products("id");
ALTER TABLE order_cart ADD FOREIGN KEY ("id_customer") REFERENCES users("id");
ALTER TABLE order_cart ADD FOREIGN KEY ("id_package") REFERENCES products_packaging("id");


CREATE TABLE assist_status
(
    id  SERIAL PRIMARY KEY,
    name    VARCHAR NOT NULL DEFAULT '',
    created_at       TIMESTAMP NULL DEFAULT NULL,
    updated_at       TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE assist_icms
(
    -- id SERIAL PRIMARY KEY,
    -- id_category INT DEFAULT NULL REFERENCES customer(id),
    -- id_credit_deadline INT DEFAULT NULL REFERENCES customer(id),
    -- created_at TIMESTAMP NULL DEFAULT NULL,
    -- updated_at TIMESTAMP NULL DEFAULT NULL
);


CREATE TABLE customer_category_to_credit
(
    id SERIAL PRIMARY KEY,
    id_category INT DEFAULT NULL REFERENCES customer(id),
    id_credit_deadline INT DEFAULT NULL REFERENCES customer(id),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);





ALTER TABLE customer_category_to_credit ADD FOREIGN KEY ("id_category") REFERENCES customer_category("id");
ALTER TABLE customer_category_to_credit ADD FOREIGN KEY ("id_credit_deadline") REFERENCES customer_credit_deadline("id");

ALTER TABLE address_city ADD FOREIGN KEY ("id_state") REFERENCES address_state("id");
ALTER TABLE address_city ADD FOREIGN KEY ("id_district") REFERENCES address_district("id");

ALTER TABLE address ADD FOREIGN KEY ("id_city") REFERENCES address_city("id");
ALTER TABLE address ADD FOREIGN KEY ("id_state") REFERENCES address_state("id");
ALTER TABLE address ADD FOREIGN KEY ("id_district") REFERENCES address_district("id");


ALTER TABLE products ADD FOREIGN KEY ("id_calibre") REFERENCES calibre("id");
ALTER TABLE products ADD FOREIGN KEY ("id_category") REFERENCES category("id");
ALTER TABLE products ADD FOREIGN KEY ("id_variety") REFERENCES variety("id");
ALTER TABLE products ADD FOREIGN KEY ("id_sales_unit") REFERENCES sales_unit("id");
ALTER TABLE products ADD FOREIGN KEY ("id_chemical_treatment") REFERENCES chemical_treatment("id");
ALTER TABLE products ADD FOREIGN KEY ("id_stock") REFERENCES stock("id");


ALTER TABLE address ADD FOREIGN KEY ("id_state") REFERENCES address_state("id");
ALTER TABLE address ADD FOREIGN KEY ("id_country") REFERENCES address_country("id");

