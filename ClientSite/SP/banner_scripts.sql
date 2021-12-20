DROP PROCEDURE IF EXISTS banner_get_info;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `banner_get_info`()
BEGIN
	DECLARE DesktopTopNavigationUrl varchar(500);
    DECLARE DesktopTopUrl varchar(500);
    DECLARE MobileTopUrl varchar(500);
    DECLARE MobileTopNavigationUrl varchar(500);
    DECLARE DesktopCenterUrl varchar(500);
    DECLARE DesktopCenterNavigationUrl varchar(500);
    DECLARE MobileCenterUrl varchar(500);
    DECLARE MobileCenterNavigationUrl varchar(500);
    DECLARE DesktopLeftUrl varchar(500);
    DECLARE DesktopLeftNavigationUrl varchar(500);
    DECLARE DesktopRightUrl varchar(500);
    DECLARE DesktopRightNavigationUrl varchar(500);
   
    
	select wo.option_value INTO DesktopTopNavigationUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_navigation_url";
    
    select wo.option_value INTO DesktopTopUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_desktop_url";
    
    select wo.option_value INTO MobileTopUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_mobile_url";
    
    select wo.option_value INTO MobileTopNavigationUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_mobile_navigation_url";
    
    select wo.option_value INTO DesktopCenterUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_desktop_center_url";
    
    select wo.option_value INTO DesktopCenterNavigationUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_desktop_center_navigation_url";
    
    select wo.option_value INTO MobileCenterUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_mobile_center_url";
    
    select wo.option_value INTO MobileCenterNavigationUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_mobile_center_navigation_url";
    
    select wo.option_value INTO DesktopLeftUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_desktop_left_url";
    
    select wo.option_value INTO DesktopLeftNavigationUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_desktop_left_navigation_url";
    
    select wo.option_value INTO DesktopRightUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_desktop_right_url";
    
    select wo.option_value INTO DesktopRightNavigationUrl
    FROM wp_options wo 
    WHERE wo.option_name = "banner_desktop_right_navigation_url";
    
    select DesktopTopNavigationUrl, DesktopTopUrl, MobileTopUrl, MobileTopNavigationUrl, DesktopCenterUrl, 
			DesktopCenterNavigationUrl, MobileCenterUrl, MobileCenterNavigationUrl, DesktopLeftUrl, DesktopLeftNavigationUrl,
            DesktopRightUrl, DesktopRightNavigationUrl, MobileCenterUrl, MobileCenterNavigationUrl;
END

//
DELIMITER ;	

DROP PROCEDURE IF EXISTS banner_update;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `banner_update`(
	IN DesktopTopNavigationUrl VARCHAR(500),
    IN DesktopTopUrl VARCHAR(500),
    IN MobileTopUrl VARCHAR(500),
    IN MobileTopNavigationUrl VARCHAR(500),
    IN DesktopCenterUrl VARCHAR(500),
	IN DesktopCenterNavigationUrl VARCHAR(500),
    IN DesktopLeftUrl VARCHAR(500),
	IN DesktopLeftNavigationUrl VARCHAR(500),
    IN DesktopRightUrl VARCHAR(500),
	IN DesktopRightNavigationUrl VARCHAR(500),
    IN MobileCenterUrl VARCHAR(500),
	IN MobileCenterNavigationUrl VARCHAR(500),
    IN ActionId INT,
    IN UpdatedDate DATETIME,
    IN ActorId INT,
    IN LogData TEXT,
	OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)
)
BEGIN
	DECLARE DesktopTopNavigationUrlExist varchar(500);
    DECLARE DesktopTopUrlExist varchar(500);
    DECLARE MobileTopUrlExist varchar(500);
    DECLARE MobileTopNavigationUrlExist varchar(500);
    DECLARE DesktopCenterUrlExist varchar(500);
    DECLARE DesktopCenterNavigationUrlExist varchar(500);
    DECLARE DesktopLeftUrlExist varchar(500);
    DECLARE DesktopLeftNavigationUrlExist varchar(500);
    DECLARE DesktopRightUrlExist varchar(500);
    DECLARE DesktopRightNavigationUrlExist varchar(500);
    DECLARE MobileCenterUrlExist varchar(500);
    DECLARE MobileCenterNavigationUrlExist varchar(500);
    
    
    SELECT option_value INTO DesktopTopNavigationUrlExist FROM wp_options where  option_name = 'banner_navigation_url';
    SELECT option_value INTO DesktopTopUrlExist FROM wp_options where  option_name = 'banner_desktop_url';
    SELECT option_value INTO MobileTopUrlExist FROM wp_options where  option_name = 'banner_mobile_url';
    SELECT option_value INTO MobileTopNavigationUrlExist FROM wp_options where  option_name = 'banner_mobile_navigation_url';
    SELECT option_value INTO DesktopCenterUrlExist FROM wp_options where  option_name = 'banner_desktop_center_url';
    SELECT option_value INTO DesktopCenterNavigationUrlExist FROM wp_options where  option_name = 'banner_desktop_center_navigation_url';
    SELECT option_value INTO DesktopLeftUrlExist FROM wp_options where  option_name = 'banner_desktop_left_url';
    SELECT option_value INTO DesktopLeftNavigationUrlExist FROM wp_options where  option_name = 'banner_desktop_left_navigation_url';
    SELECT option_value INTO DesktopRightUrlExist FROM wp_options where  option_name = 'banner_desktop_right_url';
    SELECT option_value INTO DesktopRightNavigationUrlExist FROM wp_options where  option_name = 'banner_desktop_right_navigation_url';
    SELECT option_value INTO MobileCenterUrlExist FROM wp_options where  option_name = 'banner_mobile_center_url';
    SELECT option_value INTO MobileCenterNavigationUrlExist FROM wp_options where  option_name = 'banner_mobile_center_navigation_url';
    
	IF(DesktopTopNavigationUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopTopNavigationUrl
        WHERE option_name = 'banner_navigation_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_navigation_url',
		IFNULL(DesktopTopNavigationUrl, ''));
    END IF;
    
    IF(DesktopTopUrlExist  is not null)
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopTopUrl
        WHERE option_name = 'banner_desktop_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_desktop_url',
		IFNULL(DesktopTopUrl, ''));
    END IF;
    
    IF(MobileTopUrlExist  is not null)
    THEN
		UPDATE `wp_options`
        SET option_value = MobileTopUrl
        WHERE option_name = 'banner_mobile_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_mobile_url',
		IFNULL(MobileTopUrl, ''));
    END IF;
    
    IF(MobileTopNavigationUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = MobileTopNavigationUrl
        WHERE option_name = 'banner_mobile_navigation_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_mobile_navigation_url',
		IFNULL(MobileTopNavigationUrl, ''));
    END IF;
    
    IF(DesktopCenterUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopCenterUrl
        WHERE option_name = 'banner_desktop_center_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_desktop_center_url',
		IFNULL(DesktopCenterUrl, ''));
    END IF;
    
    IF(DesktopCenterNavigationUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopCenterNavigationUrl
        WHERE option_name = 'banner_desktop_center_navigation_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_desktop_center_navigation_url',
		IFNULL(DesktopCenterNavigationUrl, ''));
    END IF;
    
    IF(DesktopLeftUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopLeftUrl
        WHERE option_name = 'banner_desktop_left_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_desktop_left_url',
		IFNULL(DesktopLeftUrl, ''));
    END IF;
    
    IF(DesktopLeftNavigationUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopLeftNavigationUrl
        WHERE option_name = 'banner_desktop_left_navigation_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_desktop_left_navigation_url',
		IFNULL(DesktopLeftNavigationUrl, ''));
    END IF;
    
     IF(DesktopRightUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopRightUrl
        WHERE option_name = 'banner_desktop_right_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_desktop_right_url',
		IFNULL(DesktopRightUrl, ''));
    END IF;
    
    IF(DesktopRightNavigationUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = DesktopRightNavigationUrl
        WHERE option_name = 'banner_desktop_right_navigation_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_desktop_right_navigation_url',
		IFNULL(DesktopRightNavigationUrl, ''));
    END IF;
    
     IF(MobileCenterUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = MobileCenterUrl
        WHERE option_name = 'banner_mobile_center_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_mobile_center_url',
		IFNULL(MobileCenterUrl, ''));
    END IF;
    
    IF(MobileCenterNavigationUrlExist is not null )
    THEN
		UPDATE `wp_options`
        SET option_value = MobileCenterNavigationUrl
        WHERE option_name = 'banner_mobile_center_navigation_url';
	ELSE 
		 INSERT INTO `wp_options`
		(`option_name`,
		`option_value`)
		VALUES
		('banner_mobile_center_navigation_url',
		IFNULL(MobileCenterNavigationUrl, ''));
    END IF;
    
    
    INSERT INTO `wp_Log`
	(`ActorId`,
	`ActionId`,
	`Data`,
    `LogDate`
    )
	VALUES
	(ActorId,
     ActionId,
     LogData,
     UpdatedDate
     );

END
//
DELIMITER ;	