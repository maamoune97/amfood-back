<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200930022530 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_pack (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, command_id INT NOT NULL, option_field_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_279CD9227294869C (article_id), INDEX IDX_279CD92233E1689A (command_id), INDEX IDX_279CD92264407614 (option_field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_pack ADD CONSTRAINT FK_279CD9227294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_pack ADD CONSTRAINT FK_279CD92233E1689A FOREIGN KEY (command_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE article_pack ADD CONSTRAINT FK_279CD92264407614 FOREIGN KEY (option_field_id) REFERENCES option_field (id)');
        $this->addSql('DROP TABLE order_article');
        $this->addSql('DROP TABLE order_option_field');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_article (order_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F440A72D7294869C (article_id), INDEX IDX_F440A72D8D9F6D38 (order_id), PRIMARY KEY(order_id, article_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_option_field (order_id INT NOT NULL, option_field_id INT NOT NULL, INDEX IDX_66DE6C5A64407614 (option_field_id), INDEX IDX_66DE6C5A8D9F6D38 (order_id), PRIMARY KEY(order_id, option_field_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_article ADD CONSTRAINT FK_F440A72D7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_article ADD CONSTRAINT FK_F440A72D8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_option_field ADD CONSTRAINT FK_66DE6C5A64407614 FOREIGN KEY (option_field_id) REFERENCES option_field (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_option_field ADD CONSTRAINT FK_66DE6C5A8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE article_pack');
    }
}
