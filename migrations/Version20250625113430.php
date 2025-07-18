<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625113430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE proyecto (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion LONGTEXT NOT NULL, fecha_inicio DATETIME NOT NULL, fecha_fin DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE registro_de_horas (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, horas DOUBLE PRECISION NOT NULL, comentario LONGTEXT NOT NULL, tarea_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_929F3F076D5BDFE1 (tarea_id), INDEX IDX_929F3F07DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tarea (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion LONGTEXT NOT NULL, estado VARCHAR(50) NOT NULL, plazo DATETIME NOT NULL, horas_estimadas DOUBLE PRECISION NOT NULL, horas_realizadas DOUBLE PRECISION NOT NULL, proyecto_id INT NOT NULL, tipologia_id INT DEFAULT NULL, INDEX IDX_3CA05366F625D1BA (proyecto_id), INDEX IDX_3CA05366636030D6 (tipologia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tarea_user (tarea_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B5F7A06C6D5BDFE1 (tarea_id), INDEX IDX_B5F7A06CA76ED395 (user_id), PRIMARY KEY(tarea_id, user_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tipologia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas ADD CONSTRAINT FK_929F3F076D5BDFE1 FOREIGN KEY (tarea_id) REFERENCES tarea (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas ADD CONSTRAINT FK_929F3F07DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366F625D1BA FOREIGN KEY (proyecto_id) REFERENCES proyecto (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366636030D6 FOREIGN KEY (tipologia_id) REFERENCES tipologia (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea_user ADD CONSTRAINT FK_B5F7A06C6D5BDFE1 FOREIGN KEY (tarea_id) REFERENCES tarea (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea_user ADD CONSTRAINT FK_B5F7A06CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_tarea DROP FOREIGN KEY FK_DE62A83E6D5BDFE1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_tarea DROP FOREIGN KEY FK_DE62A83EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_tarea
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_tarea (user_id INT NOT NULL, tarea_id INT NOT NULL, INDEX IDX_DE62A83EA76ED395 (user_id), INDEX IDX_DE62A83E6D5BDFE1 (tarea_id), PRIMARY KEY(user_id, tarea_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_tarea ADD CONSTRAINT FK_DE62A83E6D5BDFE1 FOREIGN KEY (tarea_id) REFERENCES tarea (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_tarea ADD CONSTRAINT FK_DE62A83EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas DROP FOREIGN KEY FK_929F3F076D5BDFE1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas DROP FOREIGN KEY FK_929F3F07DB38439E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea DROP FOREIGN KEY FK_3CA05366F625D1BA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea DROP FOREIGN KEY FK_3CA05366636030D6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea_user DROP FOREIGN KEY FK_B5F7A06C6D5BDFE1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea_user DROP FOREIGN KEY FK_B5F7A06CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE proyecto
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE registro_de_horas
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tarea
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tarea_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tipologia
        SQL);
    }
}
