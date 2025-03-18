<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('CREATE TRIGGER after_insert_user
        AFTER INSERT ON users FOR EACH ROW
        BEGIN
            IF NEW.role_id = 2 THEN
                INSERT INTO carts (client_id, created_at, updated_at)
                VALUES (NEW.id, NOW(), NOW());
            END IF;
        END;');

        DB::unprepared('CREATE TRIGGER before_insert_cart_details
        BEFORE INSERT ON cart_details
        FOR EACH ROW
        BEGIN
            DECLARE product_price double(8,2);
            SELECT price INTO product_price
            FROM products
            WHERE id = NEW.product_id;
            SET NEW.unit_price = product_price;
        END;');

        DB::unprepared('CREATE PROCEDURE pay_cart(IN p_cart_id INT, IN p_client_id INT, IN p_method INT)
        BEGIN

            UPDATE carts
            SET status_id = 2
            WHERE id = p_cart_id;

            INSERT INTO purchases (cart_id, payment_method_id, created_at, updated_at)
            VALUES (p_cart_id, p_method, NOW(), NOW());

            INSERT INTO carts (client_id, status_id, created_at, updated_at)
            VALUES (p_client_id, 1, NOW(), NOW());

            UPDATE products
            SET stock = stock - (SELECT quantity FROM cart_details WHERE cart_id = p_cart_id AND product_id = products.id)
            WHERE id IN (SELECT product_id FROM cart_details WHERE cart_id = p_cart_id);
        END;');

        DB::unprepared('CREATE PROCEDURE add_cart(IN cart_id_param INT, IN product_id_param INT, IN quantity_param INT)
        BEGIN

            DECLARE existing_row_count INT;
        
            SELECT COUNT(*) INTO existing_row_count
            FROM cart_details
            WHERE cart_id = cart_id_param AND product_id = product_id_param;

            IF existing_row_count > 0 THEN
                UPDATE cart_details
                SET quantity = quantity_param
                WHERE cart_id = cart_id_param AND product_id = product_id_param;
            ELSE
                INSERT INTO cart_details (cart_id, product_id, quantity, created_at, updated_at)
                VALUES (cart_id_param, product_id_param, quantity_param, NOW(), NOW());
            END IF;
        END;');

        DB::unprepared('CREATE VIEW purchases_view AS
        SELECT 
            purchases.id AS purchase_id,
            users.id AS user_id,
            users.dni AS user_dni,
            users.names AS user_names,
            users.surnames AS user_surnames,
            SUM(cart_details.quantity * cart_details.unit_price) AS total,
            purchases.created_at
        FROM
            purchases
        JOIN carts ON purchases.cart_id = carts.id
        JOIN users ON carts.client_id = users.id
        JOIN cart_details ON carts.id = cart_details.cart_id
        GROUP BY
            purchases.id, users.id, users.dni, users.names, users.surnames, purchases.created_at;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_user;');
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_cart_details');
        DB::unprepared('DROP PROCEDURE IF EXISTS pay_cart');
        DB::unprepared('DROP PROCEDURE IF EXISTS add_cart');
        DB::unprepared('DROP VIEW IF EXISTS purchases_view');
    }
};
