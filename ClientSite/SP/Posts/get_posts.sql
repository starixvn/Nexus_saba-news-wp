/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS get_posts;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts`(
	_toDate DATETIME,
    _category INT,
    _subject INT
)
BEGIN
	IF _category = 3 THEN
		IF _subject = 0 THEN
			CALL get_posts_by_subject('tin-the-thao', _category, _subject, _toDate);
		END IF;
        IF _subject = 2 THEN
			CALL get_posts_by_subject('bong-da', _category, _subject, _toDate);
		END IF;
        IF _subject = 3 THEN
			CALL get_posts_by_subject('bong-ro', _category, _subject, _toDate);
		END IF;
        IF _subject = 81 THEN
			CALL get_posts_by_subject('quan-vot', _category, _subject, _toDate);
		END IF;
	END IF;
    IF _category = 4 THEN
		IF _subject = 0 THEN
			CALL get_posts_by_subject('video', _category, _subject, _toDate);
		END IF;
        IF _subject = 2 THEN
			CALL get_posts_by_subject('video', _category, _subject, _toDate);
		END IF;
        IF _subject = 3 THEN
			CALL get_posts_by_subject('video-bong-ro', _category, _subject, _toDate);
		END IF;
        IF _subject = 81 THEN
			CALL get_posts_by_subject('video-quan-vot', _category, _subject, _toDate);
		END IF;
	END IF;
END

//
DELIMITER ;	