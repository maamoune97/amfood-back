<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210122121253 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_article_pack_option_field (order_article_pack_id INT NOT NULL, option_field_id INT NOT NULL, INDEX IDX_A7C999713FC235FC (order_article_pack_id), INDEX IDX_A7C9997164407614 (option_field_id), PRIMARY KEY(order_article_pack_id, option_field_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_article_pack_option_field ADD CONSTRAINT FK_A7C999713FC235FC FOREIGN KEY (order_article_pack_id) REFERENCES order_article_pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_article_pack_option_field ADD CONSTRAINT FK_A7C9997164407614 FOREIGN KEY (option_field_id) REFERENCES option_field (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_article_pack DROP FOREIGN KEY FK_235C4D4864407614');
        $this->addSql('DROP INDEX IDX_235C4D4864407614 ON order_article_pack');
        $this->addSql('ALTER TABLE order_article_pack DROP option_field_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE order_article_pack_option_field');
        $this->addSql('ALTER TABLE order_article_pack ADD option_field_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_article_pack ADD CONSTRAINT FK_235C4D4864407614 FOREIGN KEY (option_field_id) REFERENCES option_field (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_235C4D4864407614 ON order_article_pack (option_field_id)');
    }
}
