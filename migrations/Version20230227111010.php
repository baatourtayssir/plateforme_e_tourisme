<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227111010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6E43D4424C');
        $this->addSql('DROP INDEX IDX_9596AB6E43D4424C ON agents');
        $this->addSql('ALTER TABLE agents CHANGE nom_agence_id agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agents ADD CONSTRAINT FK_9596AB6ED725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_9596AB6ED725330D ON agents (agence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6ED725330D');
        $this->addSql('DROP INDEX IDX_9596AB6ED725330D ON agents');
        $this->addSql('ALTER TABLE agents CHANGE agence_id nom_agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agents ADD CONSTRAINT FK_9596AB6E43D4424C FOREIGN KEY (nom_agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_9596AB6E43D4424C ON agents (nom_agence_id)');
    }
}
