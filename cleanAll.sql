SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `tis_mbravesoft` ;
CREATE SCHEMA IF NOT EXISTS `tis_mbravesoft` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `tis_mbravesoft` ;

-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`user` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`user` (
  `user_id` INT NOT NULL ,
  `name` VARCHAR(50) NOT NULL ,
  `lastname` VARCHAR(50) NOT NULL ,
  `birth_date` DATETIME NOT NULL ,
  `email` VARCHAR(50) NOT NULL ,
  `password` VARCHAR(20) NOT NULL ,
  `institution` VARCHAR(50) NULL ,
  `city` VARCHAR(50) NULL ,
  PRIMARY KEY (`user_id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`problem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`problem` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`problem` (
  `problem_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `problem_name` VARCHAR(50) NOT NULL ,
  `problem_author` VARCHAR(100) NOT NULL ,
  `is_simple` TINYINT(1) NOT NULL DEFAULT 1 ,
  `compare_type` VARCHAR(10) NOT NULL DEFAULT 'STRICT' ,
  `time_constraint` INT UNSIGNED NULL DEFAULT 0 ,
  `memory_constraint` INT UNSIGNED NULL DEFAULT 0 ,
  `source_constraint` INT UNSIGNED NULL DEFAULT 0 ,
  `problem_creator` INT NOT NULL ,
  PRIMARY KEY (`problem_id`) ,
  UNIQUE INDEX `problem_id_UNIQUE` (`problem_id` ASC) ,
  UNIQUE INDEX `problem_name_UNIQUE` (`problem_name` ASC) ,
  INDEX `fk_problem_user` (`problem_creator` ASC) ,
  CONSTRAINT `fk_problem_user`
    FOREIGN KEY (`problem_creator` )
    REFERENCES `tis_mbravesoft`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = big5;


-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`solution`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`solution` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`solution` (
  `solution_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `solution_date` DATETIME NOT NULL ,
  `solution_language` VARCHAR(5) NOT NULL ,
  `solution_source_file` VARCHAR(200) NOT NULL ,
  `grade` INT NULL DEFAULT 0 ,
  `runtime` FLOAT UNSIGNED NULL DEFAULT 0 ,
  `used_memory` INT UNSIGNED NULL DEFAULT 0 ,
  `status` VARCHAR(45) NULL DEFAULT 'On Queue' ,
  `error_message` TEXT NULL ,
  `solution_submitter` INT NOT NULL ,
  `problem_problem_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`solution_id`) ,
  UNIQUE INDEX `solution_id_UNIQUE` (`solution_id` ASC) ,
  INDEX `fk_solution_user1` (`solution_submitter` ASC) ,
  INDEX `fk_solution_problem1` (`problem_problem_id` ASC) ,
  CONSTRAINT `fk_solution_user1`
    FOREIGN KEY (`solution_submitter` )
    REFERENCES `tis_mbravesoft`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_solution_problem1`
    FOREIGN KEY (`problem_problem_id` )
    REFERENCES `tis_mbravesoft`.`problem` (`problem_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`training`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`training` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`training` (
  `training_id` INT NOT NULL AUTO_INCREMENT ,
  `training_name` VARCHAR(50) NOT NULL ,
  `start_date` DATETIME NOT NULL ,
  `start_time` VARCHAR(45) NOT NULL ,
  `end_date` DATETIME NOT NULL ,
  `end_time` VARCHAR(45) NOT NULL ,
  `training_owner` INT NOT NULL ,
  PRIMARY KEY (`training_id`) ,
  UNIQUE INDEX `training_name_UNIQUE` (`training_name` ASC) ,
  INDEX `fk_training_user1` (`training_owner` ASC) ,
  CONSTRAINT `fk_training_user1`
    FOREIGN KEY (`training_owner` )
    REFERENCES `tis_mbravesoft`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`training_has_problem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`training_has_problem` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`training_has_problem` (
  `training_training_id` INT NOT NULL ,
  `problem_problem_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`training_training_id`, `problem_problem_id`) ,
  INDEX `fk_training_has_problem_problem1` (`problem_problem_id` ASC) ,
  INDEX `fk_training_has_problem_training1` (`training_training_id` ASC) ,
  CONSTRAINT `fk_training_has_problem_training1`
    FOREIGN KEY (`training_training_id` )
    REFERENCES `tis_mbravesoft`.`training` (`training_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_training_has_problem_problem1`
    FOREIGN KEY (`problem_problem_id` )
    REFERENCES `tis_mbravesoft`.`problem` (`problem_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`group` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`group` (
  `group_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `group_name` VARCHAR(50) NOT NULL ,
  `group_owner` INT NOT NULL ,
  PRIMARY KEY (`group_id`) ,
  UNIQUE INDEX `group_id_UNIQUE` (`group_id` ASC) ,
  UNIQUE INDEX `group_name_UNIQUE` (`group_name` ASC) ,
  INDEX `fk_group_user1` (`group_owner` ASC) ,
  CONSTRAINT `fk_group_user1`
    FOREIGN KEY (`group_owner` )
    REFERENCES `tis_mbravesoft`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`user_has_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`user_has_group` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`user_has_group` (
  `user_user_id` INT NOT NULL ,
  `group_group_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`user_user_id`, `group_group_id`) ,
  INDEX `fk_user_has_group_group1` (`group_group_id` ASC) ,
  INDEX `fk_user_has_group_user1` (`user_user_id` ASC) ,
  CONSTRAINT `fk_user_has_group_user1`
    FOREIGN KEY (`user_user_id` )
    REFERENCES `tis_mbravesoft`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_group_group1`
    FOREIGN KEY (`group_group_id` )
    REFERENCES `tis_mbravesoft`.`group` (`group_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`training_has_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`training_has_group` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`training_has_group` (
  `training_training_id` INT NOT NULL ,
  `group_group_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`training_training_id`, `group_group_id`) ,
  INDEX `fk_training_has_group_group1` (`group_group_id` ASC) ,
  INDEX `fk_training_has_group_training1` (`training_training_id` ASC) ,
  CONSTRAINT `fk_training_has_group_training1`
    FOREIGN KEY (`training_training_id` )
    REFERENCES `tis_mbravesoft`.`training` (`training_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_training_has_group_group1`
    FOREIGN KEY (`group_group_id` )
    REFERENCES `tis_mbravesoft`.`group` (`group_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tis_mbravesoft`.`test_case`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tis_mbravesoft`.`test_case` ;

CREATE  TABLE IF NOT EXISTS `tis_mbravesoft`.`test_case` (
  `test_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `test_in` VARCHAR(200) NOT NULL ,
  `test_out` VARCHAR(200) NOT NULL ,
  `test_points` INT UNSIGNED NOT NULL ,
  `problem_problem_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`test_id`) ,
  UNIQUE INDEX `test_id_UNIQUE` (`test_id` ASC) ,
  INDEX `fk_test_problem1` (`problem_problem_id` ASC) ,
  CONSTRAINT `fk_test_problem1`
    FOREIGN KEY (`problem_problem_id` )
    REFERENCES `tis_mbravesoft`.`problem` (`problem_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `tis_mbravesoft`;

DELIMITER $$

USE `tis_mbravesoft`$$
DROP TRIGGER IF EXISTS `tis_mbravesoft`.`save_solution_date` $$
USE `tis_mbravesoft`$$


CREATE TRIGGER `save_solution_date` BEFORE INSERT ON `solution` FOR EACH ROW
BEGIN
    SET NEW.solution_date = NOW();
END$$


DELIMITER ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `tis_mbravesoft`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `tis_mbravesoft`;
INSERT INTO `tis_mbravesoft`.`user` (`user_id`, `name`, `lastname`, `birth_date`, `email`, `password`, `institution`, `city`) VALUES (1, 'Daniela', 'Meneses', '1990-12-01', 'daniela11290@gmail.com', '1121990', 'UMSS', 'CBBA');
INSERT INTO `tis_mbravesoft`.`user` (`user_id`, `name`, `lastname`, `birth_date`, `email`, `password`, `institution`, `city`) VALUES (2, 'Fabio', 'Arandia', '1990-12-01', 'fabio@gmail.com', '1234567', 'UMSS', 'CBBA');
INSERT INTO `tis_mbravesoft`.`user` (`user_id`, `name`, `lastname`, `birth_date`, `email`, `password`, `institution`, `city`) VALUES (3, 'Richi', 'Daza', '1990-12-01', 'richi@gmail.com', '1234567', 'UMSS', 'CBBA');

COMMIT;

