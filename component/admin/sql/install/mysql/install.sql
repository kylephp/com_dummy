SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `#__dummy_objects`
(
    `id`                    int(11)                     NOT NULL AUTO_INCREMENT,
    `asset_id`              int(255)        UNSIGNED    NOT NULL DEFAULT '0',
    `title`                 varchar(255)                NOT NULL,
    `alias`                 varchar(255)                NOT NULL DEFAULT '',
    `ordering`              int(11)                     NOT NULL DEFAULT '0',
    `access`                tinyint(3)      UNSIGNED    NOT NULL DEFAULT '0',
    `blocked`               tinyint(1)                  NOT NULL DEFAULT '0',
    `published`             tinyint(1)                  NOT NULL,
    `publish_up`            datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down`          datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `featured`              tinyint(4)                  NOT NULL DEFAULT '0',
    `type_id`               int(11)                     NOT NULL,
    `template_id`           int(11)                     NOT NULL,
    `checked_out`           int(11)                     NOT NULL DEFAULT '0',
    `checked_out_time`      datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_user_id`       int(10)         UNSIGNED    NOT NULL DEFAULT '0',
    `created_time`          datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_user_id`      int(10)         UNSIGNED    NOT NULL DEFAULT '0',
    `modified_time`         datetime                    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params`                varchar(2048)               NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `access` (`access`),
    KEY `blocked` (`blocked`),
    KEY `type_id` (`type_id`),
    KEY `template_id` (`template_id`),
    KEY `published` (`published`),
    KEY `publish_down` (`publish_down`),
    KEY `publish_up` (`publish_up`),
    KEY `featured` (`featured`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__dummy_item_preview`
(
    `id`      varchar(100)     NOT NULL,
    `data`    text             NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;