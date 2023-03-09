<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309122147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE excursion_region (excursion_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_9ED459DE98260155 (region_id), PRIMARY KEY(excursion_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_good_address (offer_id INT NOT NULL, good_address_id INT NOT NULL, INDEX IDX_3E4DF06D2B09C43C (good_address_id), PRIMARY KEY(offer_id, good_address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE excursion_region ADD CONSTRAINT FK_9ED459DE4AB4296F98260155 FOREIGN KEY (excursion_id, region_id) REFERENCES excursion (id)');
        $this->addSql('ALTER TABLE excursion_region ADD CONSTRAINT FK_9ED459DE98260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_good_address ADD CONSTRAINT FK_3E4DF06D53C674EE2B09C43C FOREIGN KEY (offer_id, good_address_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE offer_good_address ADD CONSTRAINT FK_3E4DF06D2B09C43C FOREIGN KEY (good_address_id) REFERENCES good_address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE excursion ADD category VARCHAR(255) NOT NULL, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE excursion ADD CONSTRAINT FK_9B08E72FBF396750 FOREIGN KEY (id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE good_address ADD country_id INT NOT NULL, ADD entitled VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD category VARCHAR(255) NOT NULL, ADD picture VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE good_address ADD CONSTRAINT FK_D974FFD6F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_D974FFD6F92F3E70 ON good_address (country_id)');
        $this->addSql('ALTER TABLE offer ADD country_id INT DEFAULT NULL, ADD agence_id INT DEFAULT NULL, ADD title VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD picture VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873ED725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_29D6873EF92F3E70 ON offer (country_id)');
        $this->addSql('CREATE INDEX IDX_29D6873ED725330D ON offer (agence_id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursion_region DROP FOREIGN KEY FK_9ED459DE4AB4296F98260155');
        $this->addSql('ALTER TABLE excursion_region DROP FOREIGN KEY FK_9ED459DE98260155');
        $this->addSql('ALTER TABLE offer_good_address DROP FOREIGN KEY FK_3E4DF06D53C674EE2B09C43C');
        $this->addSql('ALTER TABLE offer_good_address DROP FOREIGN KEY FK_3E4DF06D2B09C43C');
        $this->addSql('DROP TABLE excursion_region');
        $this->addSql('DROP TABLE offer_good_address');
        $this->addSql('ALTER TABLE excursion DROP FOREIGN KEY FK_9B08E72FBF396750');
        $this->addSql('ALTER TABLE excursion DROP category, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE good_address DROP FOREIGN KEY FK_D974FFD6F92F3E70');
        $this->addSql('DROP INDEX IDX_D974FFD6F92F3E70 ON good_address');
        $this->addSql('ALTER TABLE good_address DROP country_id, DROP entitled, DROP address, DROP description, DROP category, DROP picture');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EF92F3E70');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873ED725330D');
        $this->addSql('DROP INDEX IDX_29D6873EF92F3E70 ON offer');
        $this->addSql('DROP INDEX IDX_29D6873ED725330D ON offer');
        $this->addSql('ALTER TABLE offer DROP country_id, DROP agence_id, DROP title, DROP description, DROP picture, DROP type');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F53C674EE');
    }
}
