<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309121248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursion DROP FOREIGN KEY FK_9B08E72FBF396750');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F53C674EE');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE excursion_region DROP FOREIGN KEY FK_9ED459DE98260155');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FD725330D');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FF92F3E70');
        $this->addSql('DROP TABLE excursion_region');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE offre_good_address');
        $this->addSql('ALTER TABLE excursion DROP category, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE good_address DROP FOREIGN KEY FK_D974FFD6F92F3E70');
        $this->addSql('DROP INDEX IDX_D974FFD6F92F3E70 ON good_address');
        $this->addSql('ALTER TABLE good_address DROP country_id, DROP entitled, DROP address, DROP description, DROP category, DROP picture');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F53C674EE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F53C674EE');
        $this->addSql('CREATE TABLE excursion_region (excursion_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_9ED459DE98260155 (region_id), PRIMARY KEY(excursion_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, picture VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_AF86866FF92F3E70 (country_id), INDEX IDX_AF86866FD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offre_good_address (offre_id INT NOT NULL, good_address_id INT NOT NULL, INDEX IDX_E251F6942B09C43C (good_address_id), PRIMARY KEY(offre_id, good_address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE excursion_region ADD CONSTRAINT FK_9ED459DE98260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('DROP TABLE offer');
        $this->addSql('ALTER TABLE excursion ADD category VARCHAR(255) NOT NULL, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE excursion ADD CONSTRAINT FK_9B08E72FBF396750 FOREIGN KEY (id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE good_address ADD country_id INT NOT NULL, ADD entitled VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD category VARCHAR(255) NOT NULL, ADD picture VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE good_address ADD CONSTRAINT FK_D974FFD6F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_D974FFD6F92F3E70 ON good_address (country_id)');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F53C674EE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F53C674EE FOREIGN KEY (offer_id) REFERENCES offre (id)');
    }
}
