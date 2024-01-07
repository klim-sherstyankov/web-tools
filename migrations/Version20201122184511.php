<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201122184511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
      $this->addSql("CREATE TABLE `news` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
        `slug` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
        `description` TEXT NULL DEFAULT NULL,
        `short_description` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
        `updated_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `published_at` DATETIME NULL DEFAULT NULL,
        `is_active` TINYINT(1) NOT NULL DEFAULT '0',
        `is_hide` TINYINT(1) NOT NULL DEFAULT '0',
        `hits` INT(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE INDEX `slug` (`slug`)
      )DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE news');
    }
}
