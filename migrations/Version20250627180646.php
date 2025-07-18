<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627180646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas DROP FOREIGN KEY FK_929F3F07DB38439E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_929F3F07DB38439E ON registro_de_horas
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas CHANGE usuario_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas ADD CONSTRAINT FK_929F3F07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_929F3F07A76ED395 ON registro_de_horas (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas DROP FOREIGN KEY FK_929F3F07A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_929F3F07A76ED395 ON registro_de_horas
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas CHANGE user_id usuario_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE registro_de_horas ADD CONSTRAINT FK_929F3F07DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_929F3F07DB38439E ON registro_de_horas (usuario_id)
        SQL);
    }
}
