<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200623121815 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, INDEX IDX_5A8600B07294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_field (id INT AUTO_INCREMENT NOT NULL, my_option_id INT NOT NULL, name VARCHAR(255) NOT NULL, additional_price DOUBLE PRECISION NOT NULL, INDEX IDX_621EF830419513FB (my_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B07294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE option_field ADD CONSTRAINT FK_621EF830419513FB FOREIGN KEY (my_option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE restaurant CHANGE phone phone VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE option_field DROP FOREIGN KEY FK_621EF830419513FB');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE option_field');
        $this->addSql('ALTER TABLE restaurant CHANGE phone phone VARCHAR(14) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
