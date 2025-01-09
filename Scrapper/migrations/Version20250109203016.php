<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109203016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "group" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lesson (id SERIAL NOT NULL, worker_id_id INT DEFAULT NULL, worker_cover_id_id INT DEFAULT NULL, group_id_id INT NOT NULL, room_id_id INT NOT NULL, subject_id_id INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, hours INT NOT NULL, lesson_form VARCHAR(255) NOT NULL, lesson_status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F87474F363E33A83 ON lesson (worker_id_id)');
        $this->addSql('CREATE INDEX IDX_F87474F36F2E0405 ON lesson (worker_cover_id_id)');
        $this->addSql('CREATE INDEX IDX_F87474F32F68B530 ON lesson (group_id_id)');
        $this->addSql('CREATE INDEX IDX_F87474F335F83FFC ON lesson (room_id_id)');
        $this->addSql('CREATE INDEX IDX_F87474F36ED75F8F ON lesson (subject_id_id)');
        $this->addSql('CREATE TABLE student (id SERIAL NOT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F363E33A83 FOREIGN KEY (worker_id_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F36F2E0405 FOREIGN KEY (worker_cover_id_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F32F68B530 FOREIGN KEY (group_id_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F335F83FFC FOREIGN KEY (room_id_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F36ED75F8F FOREIGN KEY (subject_id_id) REFERENCES subject (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F363E33A83');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F36F2E0405');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F32F68B530');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F335F83FFC');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F36ED75F8F');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE student');
    }
}
