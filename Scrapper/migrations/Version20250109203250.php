<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109203250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_group_mapping (id SERIAL NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE student_group_mapping_student (student_group_mapping_id INT NOT NULL, student_id INT NOT NULL, PRIMARY KEY(student_group_mapping_id, student_id))');
        $this->addSql('CREATE INDEX IDX_D52C9F34FF27370B ON student_group_mapping_student (student_group_mapping_id)');
        $this->addSql('CREATE INDEX IDX_D52C9F34CB944F1A ON student_group_mapping_student (student_id)');
        $this->addSql('CREATE TABLE student_group_mapping_group (student_group_mapping_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(student_group_mapping_id, group_id))');
        $this->addSql('CREATE INDEX IDX_4D9DE5E3FF27370B ON student_group_mapping_group (student_group_mapping_id)');
        $this->addSql('CREATE INDEX IDX_4D9DE5E3FE54D947 ON student_group_mapping_group (group_id)');
        $this->addSql('ALTER TABLE student_group_mapping_student ADD CONSTRAINT FK_D52C9F34FF27370B FOREIGN KEY (student_group_mapping_id) REFERENCES student_group_mapping (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_group_mapping_student ADD CONSTRAINT FK_D52C9F34CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_group_mapping_group ADD CONSTRAINT FK_4D9DE5E3FF27370B FOREIGN KEY (student_group_mapping_id) REFERENCES student_group_mapping (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_group_mapping_group ADD CONSTRAINT FK_4D9DE5E3FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE student_group_mapping_student DROP CONSTRAINT FK_D52C9F34FF27370B');
        $this->addSql('ALTER TABLE student_group_mapping_student DROP CONSTRAINT FK_D52C9F34CB944F1A');
        $this->addSql('ALTER TABLE student_group_mapping_group DROP CONSTRAINT FK_4D9DE5E3FF27370B');
        $this->addSql('ALTER TABLE student_group_mapping_group DROP CONSTRAINT FK_4D9DE5E3FE54D947');
        $this->addSql('DROP TABLE student_group_mapping');
        $this->addSql('DROP TABLE student_group_mapping_student');
        $this->addSql('DROP TABLE student_group_mapping_group');
    }
}
