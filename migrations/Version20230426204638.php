<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426204638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursion_category DROP FOREIGN KEY FK_B89F954C4AB4296F');
        $this->addSql('ALTER TABLE excursion_category DROP FOREIGN KEY FK_B89F954C12469DE2');
        $this->addSql('ALTER TABLE excursion_price_list DROP FOREIGN KEY FK_53B99D7C4AB4296F');
        $this->addSql('ALTER TABLE excursion_price_list DROP FOREIGN KEY FK_53B99D7C5688DED7');
        $this->addSql('ALTER TABLE offer_category DROP FOREIGN KEY FK_7F31A9A312469DE2');
        $this->addSql('ALTER TABLE offer_category DROP FOREIGN KEY FK_7F31A9A353C674EE');
        $this->addSql('DROP TABLE excursion_category');
        $this->addSql('DROP TABLE excursion_price_list');
        $this->addSql('DROP TABLE offer_category');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE excursion_category (excursion_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_B89F954C4AB4296F (excursion_id), INDEX IDX_B89F954C12469DE2 (category_id), PRIMARY KEY(excursion_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE excursion_price_list (excursion_id INT NOT NULL, price_list_id INT NOT NULL, INDEX IDX_53B99D7C4AB4296F (excursion_id), INDEX IDX_53B99D7C5688DED7 (price_list_id), PRIMARY KEY(excursion_id, price_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offer_category (offer_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_7F31A9A353C674EE (offer_id), INDEX IDX_7F31A9A312469DE2 (category_id), PRIMARY KEY(offer_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE excursion_category ADD CONSTRAINT FK_B89F954C4AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE excursion_category ADD CONSTRAINT FK_B89F954C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE excursion_price_list ADD CONSTRAINT FK_53B99D7C4AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE excursion_price_list ADD CONSTRAINT FK_53B99D7C5688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_category ADD CONSTRAINT FK_7F31A9A312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_category ADD CONSTRAINT FK_7F31A9A353C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
    }
}
