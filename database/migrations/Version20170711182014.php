<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20170711182014 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, preferences_language VARCHAR(255) NOT NULL, preferences_timezone VARCHAR(255) NOT NULL, preferences_date_format VARCHAR(255) NOT NULL, preferences_time_format INT NOT NULL, preferences_first_day_of_week INT NOT NULL, preferences_show_todo_lists_as VARCHAR(255) NOT NULL, notifications_discussion_started TINYINT(1) NOT NULL, notifications_discussion_commented TINYINT(1) NOT NULL, notifications_todo_list_created TINYINT(1) NOT NULL, notifications_todo_commented TINYINT(1) NOT NULL, notifications_todo_assigned_to_me TINYINT(1) NOT NULL, notifications_event_added TINYINT(1) NOT NULL, notifications_event_commented TINYINT(1) NOT NULL, avatar_path_to48px_avatar VARCHAR(255) NOT NULL, avatar_path_to96px_avatar VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE users');
    }
}
