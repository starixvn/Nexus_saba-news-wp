/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS user_create_token_confirm;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `user_create_token_confirm`(
	IN Email VARCHAR(200),
    IN Token VARCHAR(200),
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)
    /*
		Creator: Hank@20220720
        Revisions:
			- 20220720@Hank: init
    */
)
User_Create_Token_Confirm:BEGIN

	DECLARE UserId INT;
    DECLARE UMetaId INT;
    SET UserId = -1;
    SET UMetaId = -1;
    
	SELECT u.ID 
	INTO UserId
	FROM `wp_users` AS u
    INNER JOIN `wp_usermeta` AS m ON u.ID = m.user_id AND m.meta_key = 'wp_capabilities'
	WHERE u.user_login = Email AND u.user_email = Email AND m.meta_value = 'a:0:{}';
    
    IF (UserId = -1) THEN
		SET ErrCode = 1,
			ErrMsg = 'User does not exist';
        LEAVE User_Create_Token_Confirm;
	END IF;
    
    SELECT m.umeta_id
    INTO UMetaId
    FROM `wp_usermeta` AS m
    WHERE m.user_id = UserId AND m.meta_key = 'token_confirmed';
    
    IF (UMetaId = -1) THEN
		INSERT INTO `wp_usermeta`
		(`user_id`,
		`meta_key`,
		`meta_value`)
		VALUES
		(UserId,
		'token_confirmed',
		Token);
	ELSE
		UPDATE `wp_usermeta`
		SET meta_value = Token
		WHERE umeta_id = UMetaId;
	END IF;
END

//
DELIMITER ;	