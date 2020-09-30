<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616024358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A937E3C61F9');
        $this->addSql('DROP INDEX IDX_7D053A937E3C61F9 ON menu');
        $this->addSql('ALTER TABLE menu DROP owner_id, DROP name');
        $this->addSql('ALTER TABLE restaurant ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB95123FCCD7E912 ON restaurant (menu_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu ADD owner_id INT NOT NULL, ADD name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A937E3C61F9 FOREIGN KEY (owner_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_7D053A937E3C61F9 ON menu (owner_id)');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FCCD7E912');
        $this->addSql('DROP INDEX UNIQ_EB95123FCCD7E912 ON restaurant');
        $this->addSql('ALTER TABLE restaurant DROP menu_id');
    }
}
