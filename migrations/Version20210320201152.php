<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320201152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE tb_transference (id INT AUTO_INCREMENT NOT NULL, payer_wallet_id INT DEFAULT NULL, payee_wallet_id INT DEFAULT NULL, number CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', amount NUMERIC(11, 2) NOT NULL, status SMALLINT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3EF2A47A34B0D8CA (payer_wallet_id), INDEX IDX_3EF2A47AB37D0982 (payee_wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tb_user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, document VARCHAR(15) NOT NULL, email VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, UNIQUE INDEX UNIQ_D6E3D458D8698A76 (document), UNIQUE INDEX UNIQ_D6E3D458E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tb_wallet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, on_hand NUMERIC(11, 2) NOT NULL, on_hold NUMERIC(11, 2) NOT NULL, INDEX IDX_C1BF8B53A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tb_transference ADD CONSTRAINT FK_3EF2A47A34B0D8CA FOREIGN KEY (payer_wallet_id) REFERENCES tb_wallet (id)');
        $this->addSql('ALTER TABLE tb_transference ADD CONSTRAINT FK_3EF2A47AB37D0982 FOREIGN KEY (payee_wallet_id) REFERENCES tb_wallet (id)');
        $this->addSql('ALTER TABLE tb_wallet ADD CONSTRAINT FK_C1BF8B53A76ED395 FOREIGN KEY (user_id) REFERENCES tb_user (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE tb_wallet DROP FOREIGN KEY FK_C1BF8B53A76ED395');
        $this->addSql('ALTER TABLE tb_transference DROP FOREIGN KEY FK_3EF2A47A34B0D8CA');
        $this->addSql('ALTER TABLE tb_transference DROP FOREIGN KEY FK_3EF2A47AB37D0982');
        $this->addSql('DROP TABLE tb_transference');
        $this->addSql('DROP TABLE tb_user');
        $this->addSql('DROP TABLE tb_wallet');
    }
}
