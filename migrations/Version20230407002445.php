<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407002445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cruise_country DROP FOREIGN KEY FK_254C49A1D7B31F30');
        $this->addSql('ALTER TABLE cruise_country DROP FOREIGN KEY FK_254C49A1F92F3E70');
        $this->addSql('ALTER TABLE price_list_excursion DROP FOREIGN KEY FK_6DAC5EF84AB4296F');
        $this->addSql('ALTER TABLE price_list_excursion DROP FOREIGN KEY FK_6DAC5EF85688DED7');
        $this->addSql('ALTER TABLE travel_country DROP FOREIGN KEY FK_92307E77F92F3E70');
        $this->addSql('ALTER TABLE travel_country DROP FOREIGN KEY FK_92307E77ECAB15B3');
        $this->addSql('DROP TABLE cruise_country');
        $this->addSql('DROP TABLE price_list_excursion');
        $this->addSql('DROP TABLE travel_country');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cruise_country (cruise_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_254C49A1D7B31F30 (cruise_id), INDEX IDX_254C49A1F92F3E70 (country_id), PRIMARY KEY(cruise_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE price_list_excursion (price_list_id INT NOT NULL, excursion_id INT NOT NULL, INDEX IDX_6DAC5EF85688DED7 (price_list_id), INDEX IDX_6DAC5EF84AB4296F (excursion_id), PRIMARY KEY(price_list_id, excursion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE travel_country (travel_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_92307E77F92F3E70 (country_id), INDEX IDX_92307E77ECAB15B3 (travel_id), PRIMARY KEY(travel_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cruise_country ADD CONSTRAINT FK_254C49A1D7B31F30 FOREIGN KEY (cruise_id) REFERENCES cruise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cruise_country ADD CONSTRAINT FK_254C49A1F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE price_list_excursion ADD CONSTRAINT FK_6DAC5EF84AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE price_list_excursion ADD CONSTRAINT FK_6DAC5EF85688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE travel_country ADD CONSTRAINT FK_92307E77F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE travel_country ADD CONSTRAINT FK_92307E77ECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id) ON DELETE CASCADE');
    }
}
