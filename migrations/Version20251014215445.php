<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251014215445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author DROP email, DROP age');
        $this->addSql('ALTER TABLE book ADD nb_books INT NOT NULL, DROP published');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author ADD email VARCHAR(255) NOT NULL, ADD age INT NOT NULL');
        $this->addSql('ALTER TABLE book ADD published TINYINT(1) NOT NULL, DROP nb_books');
    }
}
