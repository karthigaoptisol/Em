<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208073157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB0F5299398');
        $this->addSql('DROP INDEX IDX_62809DB0F5299398 ON order_items');
        $this->addSql('ALTER TABLE order_items CHANGE `order` order_id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_62809DB08D9F6D38 ON order_items (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB08D9F6D38');
        $this->addSql('DROP INDEX IDX_62809DB08D9F6D38 ON order_items');
        $this->addSql('ALTER TABLE order_items CHANGE order_id `order` VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB0F5299398 FOREIGN KEY (`order`) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_62809DB0F5299398 ON order_items (`order`)');
    }
}
