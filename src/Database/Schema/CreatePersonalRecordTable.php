<?php

namespace App\Database\Schema;

use App\Database\Connection;

class CreatePersonalRecordTable extends Migration
{
    public function up(): void
    {
        $pdo = Connection::get();

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS personal_record (
                id INT NOT NULL,
                user_id INT NOT NULL,
                movement_id INT NOT NULL,
                value DECIMAL(8,2) NOT NULL,
                date DATETIME NOT NULL,
                PRIMARY KEY (id),
                INDEX (user_id),
                INDEX (movement_id),
                CONSTRAINT personal_record_fk_user
                    FOREIGN KEY (user_id) REFERENCES user(id)
                    ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT personal_record_fk_movement
                    FOREIGN KEY (movement_id) REFERENCES movement(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB
        ");

        $pdo->exec("
            INSERT INTO personal_record
            (id,user_id,movement_id,value,date) VALUES
            (1,1,1,100.0,'2021-01-01 00:00:00'),
            (2,1,1,180.0,'2021-01-02 00:00:00'),
            (3,1,1,150.0,'2021-01-03 00:00:00'),
            (4,1,1,110.0,'2021-01-04 00:00:00'),
            (5,2,1,110.0,'2021-01-04 00:00:00'),
            (6,2,1,140.0,'2021-01-05 00:00:00'),
            (7,2,1,190.0,'2021-01-06 00:00:00'),
            (8,3,1,170.0,'2021-01-01 00:00:00'),
            (9,3,1,120.0,'2021-01-02 00:00:00'),
            (10,3,1,130.0,'2021-01-03 00:00:00'),
            (11,1,2,130.0,'2021-01-03 00:00:00'),
            (12,2,2,130.0,'2021-01-03 00:00:00'),
            (13,3,2,125.0,'2021-01-03 00:00:00'),
            (14,1,2,110.0,'2021-01-05 00:00:00'),
            (15,1,2,100.0,'2021-01-01 00:00:00'),
            (16,2,2,120.0,'2021-01-01 00:00:00'),
            (17,3,2,120.0,'2021-01-01 00:00:00')
            ON DUPLICATE KEY UPDATE
                value = VALUES(value),
                date = VALUES(date)
        ");

        $stmt = $pdo->query("
            SHOW INDEX FROM personal_record 
            WHERE Key_name = 'idx_movement_value'
        ");

        if ($stmt->rowCount() === 0) {
            $pdo->exec("
                CREATE INDEX idx_movement_value
                ON personal_record (movement_id, value DESC)
            ");
        }
    }
}