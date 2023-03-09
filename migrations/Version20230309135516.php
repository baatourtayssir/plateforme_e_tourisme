<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309135516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE excursion');
        $this->addSql('DROP TABLE offer');
        $this->addSql('ALTER TABLE good_address ADD country_id INT NOT NULL, ADD entitled VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD category VARCHAR(255) NOT NULL, ADD picture VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE good_address ADD CONSTRAINT FK_D974FFD6F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_D974FFD6F92F3E70 ON good_address (country_id)');
        $this->addSql('DROP INDEX IDX_6970EB0F53C674EE ON reviews');
        $this->addSql('ALTER TABLE reviews DROP offer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE excursion (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE good_address DROP FOREIGN KEY FK_D974FFD6F92F3E70');
        $this->addSql('DROP INDEX IDX_D974FFD6F92F3E70 ON good_address');
        $this->addSql('ALTER TABLE good_address DROP country_id, DROP entitled, DROP address, DROP description, DROP category, DROP picture');
        $this->addSql('ALTER TABLE reviews ADD offer_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_6970EB0F53C674EE ON reviews (offer_id)');
    }
}
