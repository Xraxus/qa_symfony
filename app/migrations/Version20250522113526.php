<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250522113526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE question_tag (question_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_339D56FB1E27F6BF (question_id), INDEX IDX_339D56FBBAD26311 (tag_id), PRIMARY KEY(question_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6FBC9426989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nickname VARCHAR(50) NOT NULL, is_blocked TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, answer_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, user_id INT NOT NULL, value INT NOT NULL, INDEX IDX_518B7ACFAA334807 (answer_id), INDEX IDX_518B7ACFF8697D13 (comment_id), INDEX IDX_518B7ACFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question_tag ADD CONSTRAINT FK_339D56FB1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question_tag ADD CONSTRAINT FK_339D56FBBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFAA334807 FOREIGN KEY (answer_id) REFERENCES answers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFF8697D13 FOREIGN KEY (comment_id) REFERENCES comments (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answers ADD CONSTRAINT FK_50D0C6061E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answers ADD CONSTRAINT FK_50D0C606F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions ADD author_id INT NOT NULL, ADD category_id INT NOT NULL, ADD best_answer_id INT DEFAULT NULL, ADD status VARCHAR(20) NOT NULL, ADD image VARCHAR(255) DEFAULT NULL, DROP comment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5B6DEA817 FOREIGN KEY (best_answer_id) REFERENCES answers (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8ADC54D5F675F31B ON questions (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8ADC54D512469DE2 ON questions (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8ADC54D5B6DEA817 ON questions (best_answer_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE answers DROP FOREIGN KEY FK_50D0C606F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question_tag DROP FOREIGN KEY FK_339D56FB1E27F6BF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question_tag DROP FOREIGN KEY FK_339D56FBBAD26311
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFAA334807
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFF8697D13
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE question_tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tags
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE votes
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6061E27F6BF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D512469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5B6DEA817
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8ADC54D5F675F31B ON questions
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8ADC54D512469DE2 ON questions
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8ADC54D5B6DEA817 ON questions
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions ADD comment LONGTEXT DEFAULT NULL, DROP author_id, DROP category_id, DROP best_answer_id, DROP status, DROP image
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A1E27F6BF
        SQL);
    }
}
