<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304204439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence CHANGE num_tel phone_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE agent CHANGE agence_id agence_id INT DEFAULT NULL, CHANGE numtel phone_number VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence CHANGE phone_number num_tel VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE agent CHANGE agence_id agence_id INT NOT NULL, CHANGE phone_number numtel VARCHAR(255) NOT NULL');
    }
}
