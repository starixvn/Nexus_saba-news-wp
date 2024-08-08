/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS user_create;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `user_create`(
    IN _name NVARCHAR(100),
    IN _email VARCHAR(100),
    IN _password VARCHAR(200),
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100),
    OUT UserId INT
    /*
		Creator: Hank@20220720
      Revisions:
			  - 20220720@Hank: init
    */
)
User_Create:BEGIN

	DECLARE UserExists INT;
    
	SELECT 1
	INTO UserExists
	FROM `wp_users`
	WHERE user_login = _email OR user_email = _email;

    IF (UserExists = 1) THEN
		SET ErrCode = 1,
			ErrMsg = 'Email đã tồn tại';
        LEAVE User_Create;
	END IF;

	INSERT INTO `wp_users`
	(`user_login`,
	`user_pass`,
	`user_nicename`,
	`user_email`,
	`user_registered`,
	`user_status`,
	`display_name`)
	VALUES
	(_email,
	_password,
	_name,
	_email,
	LOCALTIME(),
	0,
	_name
	);
	
	SET UserId = LAST_INSERT_ID();
	
	INSERT INTO `wp_usermeta`
	(`user_id`,
	`meta_key`,
	`meta_value`)
	VALUES
	(UserId,
	'nickname',
	_name);
    
  INSERT INTO `wp_usermeta`
	(`user_id`,
	`meta_key`,
	`meta_value`)
	VALUES
	(UserId,
	'first_name',
	_name);
    
    INSERT INTO `wp_usermeta`
	(`user_id`,
	`meta_key`,
	`meta_value`)
	VALUES
	(UserId,
	'wp_capabilities',
	'a:0:{}');
    
    INSERT INTO `wp_usermeta`
	(`user_id`,
	`meta_key`,
	`meta_value`)
	VALUES
	(UserId,
	'email_confirmed',
	0);
    
    INSERT INTO `wp_usermeta`
	(`user_id`,
	`meta_key`,
	`meta_value`)
	VALUES
	(UserId,
	'is_subscribe',
	1);
END

//
DELIMITER ;	