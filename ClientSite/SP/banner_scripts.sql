DROP PROCEDURE IF EXISTS banner_get_info;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `banner_get_info`()
BEGIN
	DECLARE NavigationUrl varchar(255);
    DECLARE DesktopUrl varchar(255);
    DECLARE MobileUrl varchar(255);
    
	select wo.option_value INTO NavigationUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_navigation_url";
    
    select wo.option_value INTO DesktopUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_desktop_url";
    
    select wo.option_value INTO MobileUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_mobile_url";
    
    select NavigationUrl, DesktopUrl, MobileUrl;
END

//
DELIMITER ;	

DROP PROCEDURE IF EXISTS banner_update;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `banner_update`(
	IN NaviagationUrl VARCHAR(255),
    IN DesktopUrl VARCHAR(255),
    IN MobileUrl VARCHAR(255),
    IN ActionId INT,
    IN UpdatedDate DATETIME,
    IN ActorId INT,
	OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)
)
BEGIN
	DECLARE NaviagationUrlExist varchar(255);
    DECLARE DesktopUrlExist varchar(255);
    DECLARE MobileUrlExist varchar(255);
    DECLARE LogData LONGTEXT;
    DECLARE LogOldData LONGTEXT;
    SELECT option_value INTO NaviagationUrlExist FROM wp_options where  option_name = 'banner_navigation_url';
    SELECT option_value INTO DesktopUrlExist FROM wp_options where  option_name = 'banner_desktop_url';
    SELECT option_value INTO MobileUrlExist FROM wp_options where  option_name = 'banner_mobile_url';
    
	IF(NaviagationUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = NaviagationUrl
        WHERE option_name = 'banner_navigation_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_navigation_url',
		IFNULL(NaviagationUrl, ''));
    END IF;
    
    IF(DesktopUrlExist  is not null)
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopUrl
        WHERE option_name = 'banner_desktop_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_desktop_url',
		IFNULL(DesktopUrl, ''));
    END IF;
    
    IF(MobileUrlExist  is not null)
    THEN
		UPDATE `wp_options`
        SET option_value = MobileUrl
        WHERE option_name = 'banner_mobile_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_mobile_url',
		IFNULL(MobileUrl, ''));
    END IF;
    
    SET LogData = CONCAT('{"NaviagationUrl":"', IFNULL(NaviagationUrl, '') ,'","DesktopUrl":"', IFNULL(DesktopUrl, '') ,'","MobileUrl":"', IFNULL(MobileUrl, '') ,'"}');
    SET LogOldData = CONCAT('{"NaviagationUrl":"', IFNULL(NaviagationUrlExist, '') ,'","DesktopUrl":"', IFNULL(DesktopUrlExist, '') ,'","MobileUrl":"', IFNULL(MobileUrlExist, '') ,'"}');
    
    INSERT INTO `wp_Log`
	(`ActorId`,
	`ActionId`,
	`Data`,
	`OldData`,
    `LogDate`
    )
	VALUES
	(ActorId,
     ActionId,
     LogData,
     LogOldData,
     UpdatedDate
     );
END
//
DELIMITER ;	

DROP PROCEDURE IF EXISTS log_writelog;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `log_writelog`(
	ActorId INT,
    ActionId INT,
    Data LongTEXT,
    OldData longtext,
    LogDate Datetime,
	OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)
)
BEGIN
	INSERT INTO `wp_Log`
	(`ActorId`,
	`ActionId`,
	`Data`,
	`OldData`,
	`LogDate`)
	VALUES
	(ActorId,
	ActionId,
	Data,
	OldData,
	LogDate);

END
//
DELIMITER ;	