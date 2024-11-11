<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241111162337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classroom (id SERIAL NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN classroom.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE grade (id SERIAL NOT NULL, teacher_id INT NOT NULL, student_submission_id INT NOT NULL, grade INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_595AAE3441807E1D ON grade (teacher_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_595AAE34B461B1AD ON grade (student_submission_id)');
        $this->addSql('CREATE TABLE homework (id SERIAL NOT NULL, teacher_id INT NOT NULL, lesson_id INT NOT NULL, description VARCHAR(255) NOT NULL, deadline TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C600B4E41807E1D ON homework (teacher_id)');
        $this->addSql('CREATE INDEX IDX_8C600B4ECDF80196 ON homework (lesson_id)');
        $this->addSql('CREATE TABLE homework_file (id SERIAL NOT NULL, homework_id INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3E993B45B203DDE5 ON homework_file (homework_id)');
        $this->addSql('CREATE TABLE lesson (id SERIAL NOT NULL, classroom_id INT NOT NULL, subject_id INT NOT NULL, teachers_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F87474F36278D5A8 ON lesson (classroom_id)');
        $this->addSql('CREATE INDEX IDX_F87474F323EDC87 ON lesson (subject_id)');
        $this->addSql('CREATE INDEX IDX_F87474F384365182 ON lesson (teachers_id)');
        $this->addSql('CREATE TABLE student_submission (id SERIAL NOT NULL, student_id INT NOT NULL, homework_id INT NOT NULL, submitted_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_36DAB712CB944F1A ON student_submission (student_id)');
        $this->addSql('CREATE INDEX IDX_36DAB712B203DDE5 ON student_submission (homework_id)');
        $this->addSql('CREATE TABLE student_submission_file (id SERIAL NOT NULL, student_submission_id INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_423DD559B461B1AD ON student_submission_file (student_submission_id)');
        $this->addSql('CREATE TABLE subject (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE3441807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34B461B1AD FOREIGN KEY (student_submission_id) REFERENCES student_submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE homework ADD CONSTRAINT FK_8C600B4E41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE homework ADD CONSTRAINT FK_8C600B4ECDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE homework_file ADD CONSTRAINT FK_3E993B45B203DDE5 FOREIGN KEY (homework_id) REFERENCES homework (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F36278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F323EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F384365182 FOREIGN KEY (teachers_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_submission ADD CONSTRAINT FK_36DAB712CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_submission ADD CONSTRAINT FK_36DAB712B203DDE5 FOREIGN KEY (homework_id) REFERENCES homework (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_submission_file ADD CONSTRAINT FK_423DD559B461B1AD FOREIGN KEY (student_submission_id) REFERENCES student_submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student ADD class_room_id INT NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF339162176F FOREIGN KEY (class_room_id) REFERENCES classroom (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B723AF339162176F ON student (class_room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE student DROP CONSTRAINT FK_B723AF339162176F');
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT FK_595AAE3441807E1D');
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT FK_595AAE34B461B1AD');
        $this->addSql('ALTER TABLE homework DROP CONSTRAINT FK_8C600B4E41807E1D');
        $this->addSql('ALTER TABLE homework DROP CONSTRAINT FK_8C600B4ECDF80196');
        $this->addSql('ALTER TABLE homework_file DROP CONSTRAINT FK_3E993B45B203DDE5');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F36278D5A8');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F323EDC87');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F384365182');
        $this->addSql('ALTER TABLE student_submission DROP CONSTRAINT FK_36DAB712CB944F1A');
        $this->addSql('ALTER TABLE student_submission DROP CONSTRAINT FK_36DAB712B203DDE5');
        $this->addSql('ALTER TABLE student_submission_file DROP CONSTRAINT FK_423DD559B461B1AD');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE homework');
        $this->addSql('DROP TABLE homework_file');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE student_submission');
        $this->addSql('DROP TABLE student_submission_file');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP INDEX IDX_B723AF339162176F');
        $this->addSql('ALTER TABLE student DROP class_room_id');
    }
}
