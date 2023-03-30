<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324004158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cruise ADD CONSTRAINT FK_9E9D701BBF396750 FOREIGN KEY (id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pictures ADD hotel_id INT DEFAULT NULL, ADD good_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC03243BB18 FOREIGN KEY (hotel_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC02B09C43C FOREIGN KEY (good_address_id) REFERENCES offer (id)');
        $this->addSql('CREATE INDEX IDX_8F7C2FC03243BB18 ON pictures (hotel_id)');
        $this->addSql('CREATE INDEX IDX_8F7C2FC02B09C43C ON pictures (good_address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cruise DROP FOREIGN KEY FK_9E9D701BBF396750');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC03243BB18');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC02B09C43C');
        $this->addSql('DROP INDEX IDX_8F7C2FC03243BB18 ON pictures');
        $this->addSql('DROP INDEX IDX_8F7C2FC02B09C43C ON pictures');
        $this->addSql('ALTER TABLE pictures DROP hotel_id, DROP good_address_id');
    }
}
