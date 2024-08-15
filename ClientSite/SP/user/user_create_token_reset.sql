/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS user_create_token_reset;

DELIMITER //

CREATE DEFINER=`root`@`%` PROCEDURE `user_create_token_reset`(
	IN Email VARCHAR(200),
    IN Token VARCHAR(200),
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)
)
User_Create_Token_Reset:BEGIN

	DECLARE UserId INT;
    DECLARE UMetaId INT;
    DECLARE UConfirm INT;
    SET UserId = -1;
    SET UMetaId = -1;
    SET UConfirm = -1;
    
	SELECT u.ID 
	INTO UserId
	FROM `wp_users` AS u
	WHERE u.user_login = Email AND u.user_email = Email;
    
    IF (UserId = -1) THEN
		SET ErrCode = 1,
			ErrMsg = 'User does not exist';
        LEAVE User_Create_Token_Reset;
	END IF;
    
	SELECT 1
	INTO UConfirm
	FROM `wp_users` AS u
    INNER JOIN `wp_usermeta` AS m ON u.ID = m.user_id AND m.meta_key = 'email_confirmed'
	WHERE u.user_login = Email AND u.user_email = Email AND m.meta_value = 1;
    
    IF (UConfirm = -1) THEN
		SET ErrCode = 1,
			ErrMsg = 'Please confirm email first';
        LEAVE User_Create_Token_Reset;
	END IF;
    
    
    SELECT m.umeta_id
    INTO UMetaId
    FROM `wp_usermeta` AS m
    WHERE m.user_id = UserId AND m.meta_key = 'token_reset';
    
    IF (UMetaId = -1) THEN
		 INSERT INTO `wp_usermeta`
		(`user_id`,
		`meta_key`,
		`meta_value`)
		VALUES
		(UserId,
		'token_reset',
		Token);
	ELSE
		UPDATE `wp_usermeta`
		SET meta_value = Token
		WHERE umeta_id = UMetaId;
	END IF;
END

//
DELIMITER ;	