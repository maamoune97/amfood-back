<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201006200125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FCCD7E912');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEFCCD7E912');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP INDEX UNIQ_EB95123FCCD7E912 ON restaurant');
        $this->addSql('ALTER TABLE restaurant DROP menu_id');
        $this->addSql('DROP INDEX IDX_2D737AEFCCD7E912 ON section');
        $this->addSql('ALTER TABLE section CHANGE menu_id restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEFB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_2D737AEFB1E7706E ON section (restaurant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE restaurant ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB95123FCCD7E912 ON restaurant (menu_id)');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEFB1E7706E');
        $this->addSql('DROP INDEX IDX_2D737AEFB1E7706E ON section');
        $this->addSql('ALTER TABLE section CHANGE restaurant_id menu_id INT NOT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEFCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2D737AEFCCD7E912 ON section (menu_id)');
    }
}
