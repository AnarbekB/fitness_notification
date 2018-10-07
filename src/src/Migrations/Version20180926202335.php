<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180926202335 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE group_lessons (id INT AUTO_INCREMENT NOT NULL, trainer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, comment VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_B9424CD5FB08EDF6 (trainer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_lesson_user (group_lesson_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_915A0FECD4B5D77 (group_lesson_id), INDEX IDX_915A0FECA76ED395 (user_id), PRIMARY KEY(group_lesson_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_lessons ADD CONSTRAINT FK_B9424CD5FB08EDF6 FOREIGN KEY (trainer_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE group_lesson_user ADD CONSTRAINT FK_915A0FECD4B5D77 FOREIGN KEY (group_lesson_id) REFERENCES group_lessons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_lesson_user ADD CONSTRAINT FK_915A0FECA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE group_lesson_user DROP FOREIGN KEY FK_915A0FECD4B5D77');
        $this->addSql('DROP TABLE group_lessons');
        $this->addSql('DROP TABLE group_lesson_user');
    }
}
