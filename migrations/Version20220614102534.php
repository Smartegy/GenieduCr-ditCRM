<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614102534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partenaire_vendeurr (partenaire_id INT NOT NULL, vendeurr_id INT NOT NULL, INDEX IDX_6DB78BFC98DE13AC (partenaire_id), INDEX IDX_6DB78BFCD39B2AB5 (vendeurr_id), PRIMARY KEY(partenaire_id, vendeurr_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partenaire_vendeurr ADD CONSTRAINT FK_6DB78BFC98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partenaire_vendeurr ADD CONSTRAINT FK_6DB78BFCD39B2AB5 FOREIGN KEY (vendeurr_id) REFERENCES vendeurr (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE partenaire_vendeurr');
    }
}
