/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/
UPDATE sabasports.wp_posts 
SET 
    post_content = REPLACE(post_content,
        'cdn.sabavn.com',
        'cdn.sabavn.net')
WHERE
    post_content like '%cdn.sabavn.com%';
LIMIT 10000;
    

UPDATE sabasports.wp_posts 
SET 
    guid = REPLACE(guid,
        'cdn.sabavn.com',
        'cdn.sabavn.net')
WHERE
    guid like '%cdn.sabavn.com%' AND post_type = "attachment";
LIMIT 10000;
	

UPDATE sabasports.wp_postmeta
SET 
    meta_value = REPLACE(meta_value,
        'cdn.sabavn.com',
        'cdn.sabavn.net')
WHERE
    meta_value like '%cdn.sabavn.com%';