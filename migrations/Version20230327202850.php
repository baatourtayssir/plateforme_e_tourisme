<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327202850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price_list DROP FOREIGN KEY FK_399A0AA253C674EE');
        $this->addSql('ALTER TABLE price_list_excursion DROP FOREIGN KEY FK_6DAC5EF84AB4296F');
        $this->addSql('ALTER TABLE price_list_excursion DROP FOREIGN KEY FK_6DAC5EF85688DED7');
        $this->addSql('ALTER TABLE price_list_hotel DROP FOREIGN KEY FK_6E48C5DE3243BB18');
        $this->addSql('ALTER TABLE price_list_hotel DROP FOREIGN KEY FK_6E48C5DE5688DED7');
        $this->addSql('DROP TABLE price_list');
        $this->addSql('DROP TABLE price_list_excursion');
        $this->addSql('DROP TABLE price_list_hotel');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE price_list (id INT AUTO_INCREMENT NOT NULL, offer_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prix VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_399A0AA253C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE price_list_excursion (price_list_id INT NOT NULL, excursion_id INT NOT NULL, INDEX IDX_6DAC5EF85688DED7 (price_list_id), INDEX IDX_6DAC5EF84AB4296F (excursion_id), PRIMARY KEY(price_list_id, excursion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE price_list_hotel (price_list_id INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_6E48C5DE3243BB18 (hotel_id), INDEX IDX_6E48C5DE5688DED7 (price_list_id), PRIMARY KEY(price_list_id, hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE price_list ADD CONSTRAINT FK_399A0AA253C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE price_list_excursion ADD CONSTRAINT FK_6DAC5EF84AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE price_list_excursion ADD CONSTRAINT FK_6DAC5EF85688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE price_list_hotel ADD CONSTRAINT FK_6E48C5DE3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE price_list_hotel ADD CONSTRAINT FK_6E48C5DE5688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id) ON DELETE CASCADE');
    }
}
