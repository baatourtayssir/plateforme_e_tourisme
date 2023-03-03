<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227113907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent (id INT NOT NULL, agence_id INT DEFAULT NULL, numtel VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, INDEX IDX_268B9C9DD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6EBF396750');
        $this->addSql('ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6ED725330D');
        $this->addSql('DROP TABLE agents');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agents (id INT NOT NULL, agence_id INT DEFAULT NULL, numtel VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9596AB6ED725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE agents ADD CONSTRAINT FK_9596AB6EBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agents ADD CONSTRAINT FK_9596AB6ED725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DD725330D');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DBF396750');
        $this->addSql('DROP TABLE agent');
    }
}
