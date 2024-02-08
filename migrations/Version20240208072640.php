<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208072640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id VARCHAR(36) NOT NULL, delivery_address LONGTEXT NOT NULL, delivery_option VARCHAR(100) NOT NULL, estimated_delivery_date DATETIME DEFAULT NULL, status VARCHAR(50) NOT NULL, created_at VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE order_items (id VARCHAR(36) NOT NULL, item VARCHAR(255) NOT NULL, quantity INT NOT NULL, `order` VARCHAR(36) NOT NULL, INDEX IDX_62809DB0F5299398 (`order`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB0F5299398 FOREIGN KEY (`order`) REFERENCES orders (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB0F5299398');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE orders');
    }
}
