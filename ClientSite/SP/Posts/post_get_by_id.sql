/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS post_get_by_id;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `post_get_by_id`(
	postId BIGINT
    
    /*
		Creator: Hank@20230818
        Revisions:	
			- 20230818@Hank: init
            - 20240410@Hank: select custom schema.org
            - 20240410@Hank: select videoview
            - 20240627@Hank: select primary category
    */
)
GetPostDetailByIdSlug:BEGIN
	DECLARE tags VARCHAR(600);
    DECLARE tagsSlug VARCHAR(600);
    DECLARE cate_name VARCHAR(250);
    DECLARE cate_id VARCHAR(250);
    DECLARE categoriesSlug VARCHAR(250);
    

	IF NOT EXISTS (SELECT 1 FROM wp_posts p WHERE p.ID = postId AND p.post_status = 'publish')
	THEN
		LEAVE GetPostDetailByIdSlug;
	END IF;
    
    SELECT group_concat(t.name separator ', '), group_concat(t.slug separator ', ')
    INTO tags, tagsSlug
	FROM wp_terms as t
	INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'post_tag'
	INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
	WHERE  t_r.object_id = postId;
    
    SELECT group_concat(t.name separator ', '), group_concat(t.term_id separator ', '), group_concat(t.slug separator ', ')
    INTO cate_name, cate_id, categoriesSlug
	FROM wp_terms as t
	INNER JOIN wp_term_taxonomy as t_t ON t_t.term_id = t.term_id AND taxonomy = 'category'
	INNER JOIN wp_term_relationships as t_r ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
	WHERE t_r.object_id = postId;
    
	SELECT p.ID, p.post_title as  PostTitle, p.post_date as PostDate, p.post_date_gmt as PostDateGMT, p.post_modified as PostDateModified,
	p.post_content as PostContent ,p.post_excerpt as PostExcerpt, p.post_name as Slug, pp.guid as FeatureImage, p.comment_count as CommentCount, 
    tags as Tags, tagsSlug as TagsSlug, cate_name as CatesName, cate_id as Categories, categoriesSlug, p.post_author as AuthorId, u.display_name as Author,
    yi.title as SeoTitle, yi.description as SeoDescription, yi.schema_article_type as SchemaType, custom.meta_value as CustomSchema, views.meta_value as VideoViews,
    cate.meta_value as PrimaryCategory
    FROM wp_posts p
    LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment"
    LEFT JOIN wp_postmeta as custom ON custom.post_id = p.ID and custom.meta_key = "saswp_custom_schema_field"
    LEFT JOIN wp_postmeta as views ON views.post_id = p.ID AND views.meta_key = "trx_addons_video_views_count"
    LEFT JOIN wp_postmeta as cate ON cate.post_id = p.ID AND cate.meta_key = "_yoast_wpseo_primary_category"
    LEFT JOIN wp_users as u ON u.ID = p.post_author
    LEFT JOIN wp_yoast_indexable yi ON yi.object_id = p.ID AND yi.object_type = "post" 
    WHERE p.ID = postId AND p.post_status = 'publish';
END
//
DELIMITER ;	