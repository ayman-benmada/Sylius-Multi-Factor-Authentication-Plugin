<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230115191347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_admin_user ADD mfa_secret VARCHAR(255) DEFAULT NULL, ADD mfa_enabled TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE sylius_shop_user ADD mfa_secret VARCHAR(255) DEFAULT NULL, ADD mfa_enabled TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_admin_user DROP mfa_secret, DROP mfa_enabled');
        $this->addSql('ALTER TABLE sylius_shop_user DROP mfa_secret, DROP mfa_enabled');
    }
}
