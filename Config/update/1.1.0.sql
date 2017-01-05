SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- stripe_customer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `stripe_customer`;

CREATE TABLE `stripe_customer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `customer_id` INTEGER NOT NULL,
    `stripe_customer_id` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `FI_stripe_customer_customer_id` (`customer_id`),
    CONSTRAINT `fk_stripe_customer_customer_id`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
