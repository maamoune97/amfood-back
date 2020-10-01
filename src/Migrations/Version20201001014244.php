<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201001014244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_article_pack (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, command_id INT NOT NULL, option_field_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_235C4D487294869C (article_id), INDEX IDX_235C4D4833E1689A (command_id), INDEX IDX_235C4D4864407614 (option_field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_article_pack ADD CONSTRAINT FK_235C4D487294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE order_article_pack ADD CONSTRAINT FK_235C4D4833E1689A FOREIGN KEY (command_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_article_pack ADD CONSTRAINT FK_235C4D4864407614 FOREIGN KEY (option_field_id) REFERENCES option_field (id)');
        $this->addSql('DROP TABLE article_pack');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_pack (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, command_id INT NOT NULL, option_field_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_279CD92233E1689A (command_id), INDEX IDX_279CD92264407614 (option_field_id), INDEX IDX_279CD9227294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_pack ADD CONSTRAINT FK_279CD92233E1689A FOREIGN KEY (command_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE article_pack ADD CONSTRAINT FK_279CD92264407614 FOREIGN KEY (option_field_id) REFERENCES option_field (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE article_pack ADD CONSTRAINT FK_279CD9227294869C FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE order_article_pack');
    }
}
