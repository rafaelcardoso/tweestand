SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `webservice` ;
CREATE SCHEMA IF NOT EXISTS `webservice` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
SHOW WARNINGS;
USE `webservice` ;

-- -----------------------------------------------------
-- Table `webservice`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`roles` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`roles` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(20) NOT NULL ,
  `description` VARCHAR(500) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `UNIQUE_type` (`type` ASC) )
ENGINE = InnoDB
COMMENT = 'Tipos de conta:\ndefault, tester,\nlight, essential,\npremium, ' /* comment truncated */;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `webservice`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`users` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`users` (
  `id` MEDIUMINT UNSIGNED NULL AUTO_INCREMENT ,
  `role_id` TINYINT UNSIGNED NOT NULL ,
  `name` VARCHAR(20) NOT NULL ,
  `username` VARCHAR(20) NOT NULL ,
  `email` VARCHAR(60) NOT NULL ,
  `password` CHAR(60) NOT NULL ,
  `enable` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  `token` CHAR(53) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `UNIQUE_email` (`email` ASC) ,
  INDEX `FK_users_roles_idx` (`role_id` ASC) ,
  UNIQUE INDEX `UNIQUE_username` (`username` ASC) ,
  CONSTRAINT `FK_users_roles`
    FOREIGN KEY (`role_id` )
    REFERENCES `webservice`.`roles` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `webservice`.`twitter_accounts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`twitter_accounts` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`twitter_accounts` (
  `id` MEDIUMINT NULL AUTO_INCREMENT ,
  `user_id` MEDIUMINT UNSIGNED NOT NULL ,
  `identification` INT UNSIGNED NOT NULL ,
  `oauth_token` VARCHAR(50) NOT NULL ,
  `oauth_token_secret` VARCHAR(50) NOT NULL ,
  `enable` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 ,
  `last_tweet_sent_id` BIGINT NULL ,
  `last_mention_received_id` BIGINT NULL ,
  UNIQUE INDEX `UNIQUE_identification` (`identification` ASC) ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_twitter_accounts_users_idx` (`user_id` ASC) ,
  CONSTRAINT `FK_twitter_accounts_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `webservice`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `webservice`.`current_followers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`current_followers` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`current_followers` (
  `id` INT UNSIGNED NOT NULL ,
  `twitter_account_id` MEDIUMINT NOT NULL ,
  INDEX `FK_current_followers_twitter_accounts_idx` (`twitter_account_id` ASC) ,
  CONSTRAINT `FK_current_followers_twitter_accounts`
    FOREIGN KEY (`twitter_account_id` )
    REFERENCES `webservice`.`twitter_accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `webservice`.`daily_updates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`daily_updates` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`daily_updates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `twitter_account_id` MEDIUMINT NOT NULL ,
  `date` DATE NOT NULL ,
  `followers_count` MEDIUMINT UNSIGNED NOT NULL ,
  `tweets_sent_count` SMALLINT UNSIGNED NOT NULL ,
  `mentions_sent_count` SMALLINT UNSIGNED NOT NULL ,
  `mentions_received_count` SMALLINT UNSIGNED NOT NULL ,
  `retweets_sent_count` SMALLINT UNSIGNED NOT NULL ,
  `retweets_receveid_count` SMALLINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `date` (`date` ASC) ,
  INDEX `FK_daily_updates_twitter_accounts_idx` (`twitter_account_id` ASC) ,
  CONSTRAINT `FK_daily_updates_twitter_accounts`
    FOREIGN KEY (`twitter_account_id` )
    REFERENCES `webservice`.`twitter_accounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `webservice`.`lost_followers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`lost_followers` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`lost_followers` (
  `id` INT UNSIGNED NOT NULL ,
  `daily_update_id` INT UNSIGNED NOT NULL ,
  INDEX `FK_lost_followers_daily_updates_idx` (`daily_update_id` ASC) ,
  CONSTRAINT `FK_lost_followers_daily_updates`
    FOREIGN KEY (`daily_update_id` )
    REFERENCES `webservice`.`daily_updates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `webservice`.`won_followers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`won_followers` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`won_followers` (
  `id` INT UNSIGNED NOT NULL ,
  `daily_update_id` INT UNSIGNED NOT NULL ,
  INDEX `FK_won_followers_daily_updates_idx` (`daily_update_id` ASC) ,
  CONSTRAINT `FK_won_followers_daily_updates`
    FOREIGN KEY (`daily_update_id` )
    REFERENCES `webservice`.`daily_updates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `webservice`.`access_keys`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`access_keys` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`access_keys` (
  `token` VARCHAR(40) NULL ,
  PRIMARY KEY (`token`) )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `webservice`.`confirmation_links`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webservice`.`confirmation_links` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `webservice`.`confirmation_links` (
  `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` MEDIUMINT UNSIGNED NOT NULL ,
  `link` VARCHAR(53) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `UNIQUE_link` (`link` ASC) ,
  INDEX `FK_confirmation_links_users_idx` (`user_id` ASC) ,
  CONSTRAINT `FK_confirmation_links_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `webservice`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
