-- APPLIQUER APRÈS LA CRÉATION DU COMPTE

INSERT INTO addresses (street, city, province, country, postal_code)
VALUES ('2100 Oak Avenue', 'Springfield', 'State1', 'Country1', 'A1B2C3');

-- Order 2 for user 21
-- New transaction
INSERT INTO transactions (user_id, billing_address, shipping_address, payment_method, total)
VALUES (21, 11, 11, 'PayPal', 320.00);

-- New order
INSERT INTO orders (user_id, transaction_id, delivery_address_id, order_status, special_instruction)
VALUES (21, 11, 11, 'Pending', 'Please call upon arrival');

-- Order items for order 2
INSERT INTO order_items (order_id, item_id, quantity)
VALUES
    (11, 2, 1),  -- One unit of item ID 2
    (11, 5, 2);  -- Two units of item ID 5

-- Order 3 for user 21
-- New transaction
INSERT INTO transactions (user_id, billing_address, shipping_address, payment_method, total)
VALUES (21, 11, 11, 'Credit Card', 200.00);

-- New order
INSERT INTO orders (user_id, transaction_id, delivery_address_id, order_status, special_instruction)
VALUES (21, 12, 11, 'Shipped', 'No special instructions');

-- Order items for order 3
INSERT INTO order_items (order_id, item_id, quantity)
VALUES
    (12, 3, 1),  -- One unit of item ID 3
    (12, 6, 1);  -- One unit of item ID 6

-- Order 4 for user 21
-- New transaction
INSERT INTO transactions (user_id, billing_address, shipping_address, payment_method, total)
VALUES (21, 11, 11, 'Debit Card', 540.00);

-- New order
INSERT INTO orders (user_id, transaction_id, delivery_address_id, order_status, special_instruction)
VALUES (21, 13, 11, 'Delivered', 'Please be discreet');

-- Order items for order 4
INSERT INTO order_items (order_id, item_id, quantity)
VALUES
    (13, 8, 2),  -- Two units of item ID 8
    (13, 10, 1); -- One unit of item ID 10

-- Order 5 for user 21
-- New transaction
INSERT INTO transactions (user_id, billing_address, shipping_address, payment_method, total)
VALUES (21, 11, 11, 'Credit Card', 150.00);

-- New order
INSERT INTO orders (user_id, transaction_id, delivery_address_id, order_status, special_instruction)
VALUES (21, 14, 11, 'Pending', 'Deliver after 5 PM');

-- Order items for order 5
INSERT INTO order_items (order_id, item_id, quantity)
VALUES
    (14, 9, 3);  -- Three units of item ID 9

-- Order 6 for user 21
-- New transaction
INSERT INTO transactions (user_id, billing_address, shipping_address, payment_method, total)
VALUES (21, 11, 11, 'PayPal', 275.00);

-- New order
INSERT INTO orders (user_id, transaction_id, delivery_address_id, order_status, special_instruction)
VALUES (21, 15, 11, 'Canceled', 'N/A');

-- Order items for order 6
INSERT INTO order_items (order_id, item_id, quantity)
VALUES
    (15, 11, 1),  -- One unit of item ID 11
    (15, 12, 2);  -- Two units of item ID 12
