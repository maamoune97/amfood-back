<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200613062625 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, section_id INT NOT NULL, name VARCHAR(50) NOT NULL, ingredient VARCHAR(500) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_23A0E66D823E37A (section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, island_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_2D5B0234B0EEFA16 (island_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE island (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_7D053A937E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, name VARCHAR(100) NOT NULL, phone VARCHAR(14) NOT NULL, email VARCHAR(255) DEFAULT NULL, INDEX IDX_EB95123F64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, name VARCHAR(50) NOT NULL, status TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_2D737AEFCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234B0EEFA16 FOREIGN KEY (island_id) REFERENCES island (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A937E3C61F9 FOREIGN KEY (owner_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F64D218E FOREIGN KEY (location_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEFCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F64D218E');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234B0EEFA16');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEFCCD7E912');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A937E3C61F9');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66D823E37A');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE island');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE section');
    }
}
