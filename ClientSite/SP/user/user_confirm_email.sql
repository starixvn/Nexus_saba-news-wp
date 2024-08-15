/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS user_confirm_email;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `user_confirm_email`(
    IN id INT,
    IN token VARCHAR(200),
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)
)
User_Confirm_Email:BEGIN

	DECLARE UserId INT;
    SET UserId = -1;
    
	SELECT u.ID 
	INTO UserId
	FROM `wp_users` AS u
    INNER JOIN `wp_usermeta` AS m ON u.ID = m.user_id AND m.meta_key = 'token_confirmed'
    INNER JOIN `wp_usermeta` AS mc ON u.ID = mc.user_id AND mc.meta_key = 'email_confirmed'
	WHERE u.ID = id AND m.meta_value = token AND mc.meta_value = 0;
    
    IF (UserId = -1) THEN
		SET ErrCode = 1,
			ErrMsg = 'Token does not exist';
        LEAVE User_Confirm_Email;
	END IF;
    
    UPDATE `wp_usermeta`
    SET meta_value = 1
    WHERE meta_key= 'email_confirmed' AND user_id = UserId;
    
    DELETE FROM `wp_usermeta`
    WHERE meta_key= 'token_confirmed' AND user_id = UserId;
END

//
DELIMITER ;	