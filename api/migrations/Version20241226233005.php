<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226233005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discount ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE discount ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40E4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E1E0B40E4584665A ON discount (product_id)');
        $this->addSql('CREATE INDEX IDX_E1E0B40E12469DE2 ON discount (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE discount DROP CONSTRAINT FK_E1E0B40E4584665A');
        $this->addSql('ALTER TABLE discount DROP CONSTRAINT FK_E1E0B40E12469DE2');
        $this->addSql('DROP INDEX IDX_E1E0B40E4584665A');
        $this->addSql('DROP INDEX IDX_E1E0B40E12469DE2');
        $this->addSql('ALTER TABLE discount DROP product_id');
        $this->addSql('ALTER TABLE discount DROP category_id');
    }
}
