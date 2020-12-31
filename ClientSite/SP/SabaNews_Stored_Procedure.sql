DROP PROCEDURE IF EXISTS check_type_category;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `check_type_category`( _parent varchar(200), _ID INT)
SQL SECURITY DEFINER
check_type_category:BEGIN
	DECLARE parentId INT;
    SET parentId = (SELECT term_id FROM wp_terms WHERE slug = _parent );
	SELECT  t_t.term_id
	FROM wp_term_taxonomy as t_t
	WHERE t_t.parent = parentId 
	AND t_t.term_id IN (SELECT t_t.term_id 
						FROM wp_term_taxonomy as t_t 
						INNER JOIN wp_term_relationships t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id AND t_r.object_id = _ID);
END;

//
DELIMITER ;



DROP PROCEDURE IF EXISTS get_category_detail;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_category_detail`(_slug varchar(50))
SQL SECURITY DEFINER
get_category_detail:BEGIN
	SELECT  t.name as title , t.term_id, t.slug
		,(SELECT meta_value FROM wp_termmeta tm1  WHERE  meta_key = "rank_math_description" AND tm1.term_id = t.term_id) as description
        ,(SELECT meta_value FROM wp_termmeta tm2  WHERE  meta_key = "rank_math_facebook_image" AND tm2.term_id = t.term_id) as og_image
        ,(SELECT meta_value FROM wp_termmeta tm3  WHERE  meta_key = "rank_math_facebook_description" AND tm3.term_id = t.term_id) as og_description
        ,(SELECT meta_value FROM wp_termmeta tm4  WHERE  meta_key = "rank_math_facebook_title" AND tm4.term_id = t.term_id) as og_title
        ,(SELECT meta_value FROM wp_termmeta tm5  WHERE  meta_key = "rank_math_title" AND tm5.term_id = t.term_id) as seo_title
    FROM wp_terms t 
    INNER JOIN wp_term_taxonomy wt ON  t.term_id = wt.term_id AND wt.taxonomy = "category"
    WHERE t.slug = _slug;
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS get_menu;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_menu`(_menu_name VARCHAR(50))
SQL SECURITY DEFINER
get_menu:BEGIN
	SELECT p.ID, p.post_title, pl.meta_value as term_id, pp.meta_value as menu_parent, t.name as cate_title, t.slug as slug, p1.post_name as page_slug
	FROM wp_term_relationships as txr 
	INNER JOIN wp_posts as p ON txr.object_id = p.ID AND p.post_status = 'publish' AND p.post_type = 'nav_menu_item'
	LEFT JOIN wp_postmeta as pl ON p.ID = pl.post_id AND pl.meta_key = '_menu_item_object_id'
	LEFT JOIN wp_postmeta as pp ON p.ID = pp.post_id AND pp.meta_key = '_menu_item_menu_item_parent'
    LEFT JOIN wp_terms as t ON t.term_id = pl.meta_value
    LEFT JOIN wp_posts as p1 ON p1.ID = pl.meta_value AND p1.post_status = 'publish' AND p1.post_type = "page"
    
	WHERE txr.term_taxonomy_id = (SELECT t.term_id 
									FROM wp_term_taxonomy as tax 
									LEFT JOIN wp_terms as t ON tax.term_id = t.term_id 
									WHERE taxonomy = 'nav_menu' AND name = _menu_name) 
	ORDER BY p.menu_order;
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS get_page_detail_by_cateId;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_page_detail_by_cateId`(ID INT)
SQL SECURITY DEFINER
get_page_detail_by_cateId:BEGIN
	SELECT p.post_title as title, p.ID as term_id
    ,(SELECT meta_value FROM wp_postmeta pm1  WHERE  meta_key = "rank_math_title" AND pm1.post_id = p.ID) as seo_title
	,(SELECT meta_value FROM wp_postmeta pm2  WHERE  meta_key = "rank_math_description" AND pm2.post_id = p.ID) as description
    ,(SELECT meta_value FROM wp_postmeta pm3  WHERE  meta_key = "rank_math_facebook_image" AND pm3.post_id = p.ID) as og_image
	,(SELECT meta_value FROM wp_postmeta pm4  WHERE  meta_key = "rank_math_facebook_description" AND pm4.post_id = p.ID) as og_description
	,(SELECT meta_value FROM wp_postmeta pm5  WHERE  meta_key = "rank_math_facebook_title" AND pm5.post_id = p.ID) as og_title
	FROM wp_posts p 
	where p.ID = ID AND post_type = "page";
END;

//
DELIMITER ;



DROP PROCEDURE IF EXISTS get_page_detail_by_slug;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_page_detail_by_slug`(Slug varchar(50))
SQL SECURITY DEFINER
get_page_detail_by_slug:BEGIN
	SELECT p.post_title as title, p.ID as term_id
    ,(SELECT meta_value FROM wp_postmeta pm1  WHERE  meta_key = "rank_math_title" AND pm1.post_id = p.ID) as seo_title
	,(SELECT meta_value FROM wp_postmeta pm2  WHERE  meta_key = "rank_math_description" AND pm2.post_id = p.ID) as description
    ,(SELECT meta_value FROM wp_postmeta pm3  WHERE  meta_key = "rank_math_facebook_image" AND pm3.post_id = p.ID) as og_image
	,(SELECT meta_value FROM wp_postmeta pm4  WHERE  meta_key = "rank_math_facebook_description" AND pm4.post_id = p.ID) as og_description
	,(SELECT meta_value FROM wp_postmeta pm5  WHERE  meta_key = "rank_math_facebook_title" AND pm5.post_id = p.ID) as og_title
	FROM wp_posts p 
	where p.post_name = Slug AND p.post_type = "page";
END;

//
DELIMITER ;



DROP PROCEDURE IF EXISTS get_posts;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts`(
	_category INT,
    _start INT,
    _end INT
)
SQL SECURITY DEFINER
get_posts:BEGIN
	DECLARE count  INT;
	
    SET count = (SELECT count(*) FROM wp_term_taxonomy WHERE FIND_IN_SET(term_id, _category) and parent = 0);
	IF count > 0 THEN
		SELECT distinct p.id, p.post_title, post_content, p.post_date, p.post_date_gmt, p.post_name
		, (SELECT pp.guid FROM wp_posts pp  WHERE pp.ID = m.meta_value AND pp.post_type="attachment") as feature_image
		, (SELECT pm.meta_value FROM wp_postmeta pm WHERE pm.post_id = m.meta_value AND  pm.meta_key= "_wp_attachment_metadata") as metadata
		, (SELECT t.name FROM wp_terms t WHERE term_id = _category) as cate_name
		FROM wp_posts as p
		INNER JOIN wp_term_relationships as t_r ON p.ID = t_r.object_id
		INNER JOIN wp_term_taxonomy as t_t ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        INNER JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
		WHERE p.post_type = 'post' AND p.post_status = 'publish'
		AND t_t.parent = _category
		ORDER BY p.post_date DESC
        limit _start, _end;
	ELSE 
		SELECT distinct p.id, p.post_title, p.post_content, p.post_date, p.post_name
		, (SELECT pp.guid FROM wp_posts pp  WHERE pp.ID = m.meta_value AND pp.post_type="attachment") as feature_image
		, (SELECT pm.meta_value FROM wp_postmeta pm WHERE pm.post_id = m.meta_value AND  pm.meta_key= "_wp_attachment_metadata") as metadata
		, (SELECT t.name FROM wp_terms t WHERE term_id = _category) as cate_name
		FROM wp_posts as p
		INNER JOIN wp_term_relationships as t_r ON p.ID = t_r.object_id
		INNER JOIN wp_term_taxonomy as t_t ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        INNER JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
		WHERE p.post_type = 'post' AND p.post_status = 'publish'
		AND taxonomy = 'category' and FIND_IN_SET(t_t.term_id, _category)
		ORDER BY p.post_date DESC
        limit _start, _end;
    END IF;
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS get_posts_by_date;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts_by_date`(_date VARCHAR(30), _start INT,  _end INT)
SQL SECURITY DEFINER
get_posts_by_date:BEGIN
	SELECT  p.ID, p.post_title, p.post_date, p.post_date_gmt, p.post_content, p.post_name
   , (SELECT pp.guid FROM wp_posts pp  WHERE pp.ID = m.meta_value AND pp.post_type="attachment") as feature_image
	, (SELECT pm.meta_value FROM wp_postmeta pm WHERE pm.post_id = m.meta_value AND  pm.meta_key= "_wp_attachment_metadata") as metadata
    FROM wp_posts p
    INNER JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
    WHERE p.post_type = 'post'AND p.post_status = 'publish' AND DATE(p.post_date) like  CONCAT(_date,'%')
    ORDER BY p.post_date DESC
    LIMIT _start, _end;
END;

//
DELIMITER ;



DROP PROCEDURE IF EXISTS get_posts_by_slug;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts_by_slug`(
	_categorySlug varchar(50),
    _start INT,
    _end INT
)
SQL SECURITY DEFINER
get_posts_by_slug:BEGIN
	DECLARE count  INT;
	DECLARE _category INT;
    SET _category = (SELECT t.term_id FROM wp_terms t INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND taxonomy = "category" WHERE slug = _categorySlug );
    SET count = (SELECT count(*) FROM wp_term_taxonomy WHERE FIND_IN_SET(term_id, _category) and parent = 0);
	IF count > 0 THEN
		SELECT distinct p.id, p.post_title, p.post_content, p.post_date, p.post_date_gmt, p.post_name, pp.guid as feature_image, m1.meta_value as metadata, t.name as cate_name
		FROM wp_posts as p
		INNER JOIN wp_term_relationships as t_r ON p.ID = t_r.object_id
		INNER JOIN wp_term_taxonomy as t_t ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        INNER JOIN wp_terms as t ON t.term_id = _category
        INNER JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
        INNER JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
        INNER JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
        
		WHERE p.post_type = 'post' AND p.post_status = 'publish'
		AND t_t.parent = _category
		ORDER BY p.post_date DESC
        limit _start, _end;
	ELSE 
		SELECT distinct p.id, p.post_title, p.post_content, p.post_date, p.post_date_gmt, p.post_name, pp.guid as feature_image, m1.meta_value as metadata, t.name as cate_name
		FROM wp_posts as p
		INNER JOIN wp_term_relationships as t_r ON p.ID = t_r.object_id
		INNER JOIN wp_term_taxonomy as t_t ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
		INNER JOIN wp_terms as t ON t.term_id = _category
        INNER JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
        INNER JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
        INNER JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
		WHERE p.post_type = 'post' AND p.post_status = 'publish'
		AND taxonomy = 'category' and FIND_IN_SET(t_t.term_id, _category)
		ORDER BY p.post_date DESC
        limit _start, _end;
    END IF;
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS get_posts_by_tag;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts_by_tag`(_tag varchar(50), _start INT, _end INT)
SQL SECURITY DEFINER
get_posts_by_tag:BEGIN
	SELECT  distinct p.ID, p.post_title, p.post_content, p.post_date, p.post_date_gmt, p.post_name, t.name  as tag_name
    , (SELECT pp.guid FROM wp_posts pp  WHERE pp.ID = m.meta_value AND pp.post_type="attachment") as feature_image
	, (SELECT pm.meta_value FROM wp_postmeta pm WHERE pm.post_id = m.meta_value AND  pm.meta_key= "_wp_attachment_metadata") as metadata
	FROM wp_terms t
	INNER JOIN wp_term_taxonomy tt ON tt.term_id = t.term_id AND taxonomy = 'post_tag'
	INNER JOIN wp_term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
	INNER JOIN wp_posts p ON p.ID = tr.object_id AND p.post_type='post' AND p.post_status='publish'
    INNER JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	WHERE FIND_IN_SET(t.slug, _tag)
	ORDER BY p.post_date DESC
    LIMIT _start,_end;
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS get_post_detail;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_post_detail`(ID INT)
SQL SECURITY DEFINER
get_post_detail:BEGIN
	SELECT p.ID, p.post_title, p.post_date, p.post_date_gmt, p.post_content, p.post_name
    , (SELECT pp.guid FROM wp_postmeta pm
		INNER JOIN wp_posts pp ON pp.ID = pm.meta_value AND pp.post_type="attachment"
		WHERE pm.post_id = p.ID and meta_key = "_thumbnail_id") as feature_image
	, (SELECT pm2.meta_value FROM wp_postmeta pm
		INNER JOIN wp_postmeta pm2 ON pm2.post_id = pm.meta_value AND pm2.meta_key="_wp_attachment_metadata"
		WHERE pm.post_id = p.ID and pm.meta_key = "_thumbnail_id") as metadata
	, (SELECT group_concat(t.name separator ', ')
		FROM wp_terms as t
        INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'post_tag'
        INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        WHERE  p.ID = t_r.object_id
	) as 'tags'
    ,(SELECT group_concat(t.term_id separator ', ')
		FROM wp_terms as t
        INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'category'
        INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        WHERE p.ID = t_r.object_id
	) as 'categories'
    ,(SELECT group_concat(t.slug separator ', ')
		FROM wp_terms as t
        INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'category'
        INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        WHERE p.ID = t_r.object_id
	) as 'categoriesSlug'
    FROM wp_posts p
    WHERE p.ID = ID AND p.post_type = 'post' AND p.post_status = 'publish';
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS get_post_detail_by_slug;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_post_detail_by_slug`(_slug varchar(300))
SQL SECURITY DEFINER
get_post_detail_by_slug:BEGIN
	SELECT p.ID, p.post_title, p.post_date, p.post_date_gmt, p.post_content, p.post_name
    , (SELECT pp.guid FROM wp_postmeta pm
		INNER JOIN wp_posts pp ON pp.ID = pm.meta_value AND pp.post_type="attachment"
		WHERE pm.post_id = p.ID and meta_key = "_thumbnail_id") as feature_image
	, (SELECT pm2.meta_value FROM wp_postmeta pm
		INNER JOIN wp_postmeta pm2 ON pm2.post_id = pm.meta_value AND pm2.meta_key="_wp_attachment_metadata"
		WHERE pm.post_id = p.ID and pm.meta_key = "_thumbnail_id") as metadata
	, (SELECT group_concat(t.name separator ', ')
		FROM wp_terms as t
        INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'post_tag'
        INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        WHERE  p.ID = t_r.object_id
	) as 'tags'
    , (SELECT group_concat(t.slug separator ', ')
		FROM wp_terms as t
        INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'post_tag'
        INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        WHERE  p.ID = t_r.object_id
	) as 'tagsSlug'
    ,(SELECT group_concat(t.term_id separator ', ')
		FROM wp_terms as t
        INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'category'
        INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        WHERE p.ID = t_r.object_id
	) as 'cate_name'
    ,(SELECT group_concat(t.slug separator ', ')
		FROM wp_terms as t
        INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'category'
        INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        WHERE p.ID = t_r.object_id
	) as 'categoriesSlug'
    FROM wp_posts p
    WHERE p.post_name = _slug AND p.post_status = 'publish';
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS get_random_post_categories;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_random_post_categories`(_categories VARCHAR(50), _ID INT)
SQL SECURITY DEFINER
get_random_post_categories:BEGIN
	SELECT distinct p.id, p.post_title, p.post_content, p.post_date, p.post_date_gmt, p.post_name
		, (SELECT pp.guid FROM wp_posts pp  WHERE pp.ID = m.meta_value AND pp.post_type="attachment") as feature_image
		, (SELECT pm.meta_value FROM wp_postmeta pm WHERE pm.post_id = m.meta_value AND  pm.meta_key= "_wp_attachment_metadata") as metadata
		FROM wp_posts as p
		INNER JOIN wp_term_relationships as t_r ON p.ID = t_r.object_id
		INNER JOIN wp_term_taxonomy as t_t ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
        INNER JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
		WHERE p.post_type = 'post' AND p.post_status = 'publish'
		AND t_t.taxonomy = 'category' and FIND_IN_SET(t_t.term_id, _categories) AND p.ID  != _ID
		ORDER BY RAND()
        limit 0, 2;
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS get_related_posts;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_related_posts`(_ID INT, _start INT, _end INT)
SQL SECURITY DEFINER
get_related_posts:BEGIN
	SELECT  y.ID, p.post_title, p.post_content, p.post_date, p.post_date_gmt, p.post_name
	FROM wp_posts p
	INNER JOIN wp_yarpp_related_cache y ON y.ID = p.ID AND y.reference_ID = _ID
	WHERE post_type = 'post' AND post_status = 'publish'
	ORDER BY post_date DESC
    LIMIT _start, _end;
END;

//
DELIMITER ;



DROP PROCEDURE IF EXISTS get_tag_name_by_slug;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_tag_name_by_slug`(_slug varchar(300))
SQL SECURITY DEFINER
get_tag_name_by_slug:BEGIN
	SELECT name from wp_terms where slug = _slug;
END;

//
DELIMITER ;


DROP PROCEDURE IF EXISTS search_posts;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `search_posts`(_keyword varchar(50), _start INT, _end INT)
SQL SECURITY DEFINER
search_posts:BEGIN
	SELECT p.ID, p.post_title, p.post_date, p.post_date_gmt, p.post_content, p.post_name
    , (SELECT pp.guid FROM wp_posts pp  WHERE pp.ID = m.meta_value AND pp.post_type="attachment") as feature_image
	, (SELECT pm.meta_value FROM wp_postmeta pm WHERE pm.post_id = m.meta_value AND  pm.meta_key= "_wp_attachment_metadata") as metadata
    FROM wp_posts p
    INNER JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
    WHERE (p.post_title like CONCAT('%',_keyword,'%')  OR p.post_content like CONCAT('%',_keyword,'%'))
    AND p.post_type = 'post' 
    AND p.post_status = 'publish'
    ORDER BY p.post_date DESC
    LIMIT _start, _end;
END;

//
DELIMITER ;

