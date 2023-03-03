<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227112742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F1766181EEF2');
        $this->addSql('DROP INDEX IDX_F62F1766181EEF2 ON region');
        $this->addSql('ALTER TABLE region CHANGE name_country_id country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_F62F176F92F3E70 ON region (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F176F92F3E70');
        $this->addSql('DROP INDEX IDX_F62F176F92F3E70 ON region');
        $this->addSql('ALTER TABLE region CHANGE country_id name_country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F1766181EEF2 FOREIGN KEY (name_country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_F62F1766181EEF2 ON region (name_country_id)');
    }
}
