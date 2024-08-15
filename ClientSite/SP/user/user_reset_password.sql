/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS user_reset_password;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `user_reset_password`(
    IN id INT,
    IN _password varchar(200),
    IN token VARCHAR(200),
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)
)
User_Reset_Password:BEGIN

	DECLARE UserId INT;
    SET UserId = -1;
    
	SELECT u.ID 
	INTO UserId
	FROM `wp_users` AS u
    INNER JOIN `wp_usermeta` AS m ON u.ID = m.user_id AND m.meta_key = 'token_reset'
	WHERE u.ID = id AND m.meta_value = token;
    
    IF (UserId = -1) THEN
		SET ErrCode = 1,
			ErrMsg = 'Token does not exist';
        LEAVE User_Reset_Password;
	END IF;
    
    UPDATE `wp_users` as u
    SET u.user_pass = _password WHERE u.ID = id;
    
    DELETE FROM `wp_usermeta`
    WHERE meta_key= 'token_reset' AND user_id = UserId;
END

//
DELIMITER ;	