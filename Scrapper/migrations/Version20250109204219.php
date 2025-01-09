<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109204219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_group (student_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(student_id, group_id))');
        $this->addSql('CREATE INDEX IDX_E5F73D58CB944F1A ON student_group (student_id)');
        $this->addSql('CREATE INDEX IDX_E5F73D58FE54D947 ON student_group (group_id)');
        $this->addSql('ALTER TABLE student_group ADD CONSTRAINT FK_E5F73D58CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_group ADD CONSTRAINT FK_E5F73D58FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE student_group DROP CONSTRAINT FK_E5F73D58CB944F1A');
        $this->addSql('ALTER TABLE student_group DROP CONSTRAINT FK_E5F73D58FE54D947');
        $this->addSql('DROP TABLE student_group');
    }
}
