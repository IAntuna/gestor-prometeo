<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608081318 extends AbstractMigration
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
            CREATE TABLE tarea (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion LONGTEXT NOT NULL, estado VARCHAR(50) NOT NULL, plazo DATETIME NOT NULL, horas_estimadas DOUBLE PRECISION NOT NULL, horas_realizadas DOUBLE PRECISION NOT NULL, proyecto_id INT NOT NULL, tipologia_id INT DEFAULT NULL, usuario_id INT DEFAULT NULL, INDEX IDX_3CA05366F625D1BA (proyecto_id), INDEX IDX_3CA05366636030D6 (tipologia_id), INDEX IDX_3CA05366DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tipologia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, roles JSON NOT NULL, password VARCHAR(30) NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas ADD CONSTRAINT FK_929F3F076D5BDFE1 FOREIGN KEY (tarea_id) REFERENCES tarea (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas ADD CONSTRAINT FK_929F3F07DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366F625D1BA FOREIGN KEY (proyecto_id) REFERENCES proyecto (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366636030D6 FOREIGN KEY (tipologia_id) REFERENCES tipologia (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
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
            ALTER TABLE tarea DROP FOREIGN KEY FK_3CA05366DB38439E
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
            DROP TABLE tipologia
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuario
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
