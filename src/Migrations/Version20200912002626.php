<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200912002626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE delivery_man DROP FOREIGN KEY FK_4E0DFC7C98260155');
        $this->addSql('DROP INDEX IDX_4E0DFC7C98260155 ON delivery_man');
        $this->addSql('ALTER TABLE delivery_man CHANGE region_id city_id INT NOT NULL');
        $this->addSql('ALTER TABLE delivery_man ADD CONSTRAINT FK_4E0DFC7C8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_4E0DFC7C8BAC62AF ON delivery_man (city_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE delivery_man DROP FOREIGN KEY FK_4E0DFC7C8BAC62AF');
        $this->addSql('DROP INDEX IDX_4E0DFC7C8BAC62AF ON delivery_man');
        $this->addSql('ALTER TABLE delivery_man CHANGE city_id region_id INT NOT NULL');
        $this->addSql('ALTER TABLE delivery_man ADD CONSTRAINT FK_4E0DFC7C98260155 FOREIGN KEY (region_id) REFERENCES city (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4E0DFC7C98260155 ON delivery_man (region_id)');
    }
}
