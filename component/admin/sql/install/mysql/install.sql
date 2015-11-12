SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `#__dummy_objects`
(
    `id`                    int(11)                     NOT NULL AUTO_INCREMENT,
    `asset_id`              int(255)        UNSIGNED    NOT NULL DEFAULT '0',
    `parent_id`             int(10)         UNSIGNED    NOT NULL DEFAULT '0',
    `lft`                   int(11)                     NOT NULL DEFAULT '0',
    `rgt`                   int(11)                     NOT NULL DEFAULT '0',
    `level`                 int(10)         UNSIGNED    NOT NULL DEFAULT '0',
    `name`                 varchar(255)                NOT NULL,
    `alias`                 varchar(255)                NOT NULL DEFAULT '',
    `access`                tinyint(3)      UNSIGNED    NOT NULL DEFAULT '0',
    `path`                  varchar(255)                NOT NULL DEFAULT '',
    `ordering`              int(11)                     NOT NULL DEFAULT '0',
    `published`             tinyint(1)                  NOT NULL,
    `publish_up`            datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down`          datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `checked_out`           int(10)         UNSIGNED    NOT NULL DEFAULT '0',
    `checked_out_time`      datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_user_id`       int(10)         UNSIGNED    NOT NULL DEFAULT '0',
    `created_time`          datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_user_id`      int(10)         UNSIGNED    NOT NULL DEFAULT '0',
    `modified_time`         datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params`                varchar(2048)               NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `idx_left_right` (`lft`,`rgt`),
    KEY `access` (`access`),
    KEY `published` (`published`),
    KEY `publish_up` (`publish_up`),
    KEY `publish_down` (`publish_down`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

SET FOREIGN_KEY_CHECKS = 1;