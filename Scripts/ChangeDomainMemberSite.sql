/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/
UPDATE sabasports.wp_posts 
SET 
    post_content = REPLACE(post_content,
        'https://www.sabavn.com',
        'https://sabavn.net')
WHERE
    post_content like '%https://www.sabavn.com%';
    
UPDATE sabasports.wp_posts 
SET 
    post_content = REPLACE(post_content,
        'https://sabavn.com',
        'https://sabavn.net')
WHERE
    post_content like '%https://sabavn.com%';
	

UPDATE sabasports.wp_email 
SET 
    EmbeddedLink = REPLACE(EmbeddedLink,
        'https://www.sabavn.com',
        'https://sabavn.net')
WHERE
    EmbeddedLink like '%https://www.sabavn.com%';
	

UPDATE sabasports.wp_email 
SET 
    EmailContent = REPLACE(EmailContent,
        'https://www.sabavn.com',
        'https://sabavn.net')
WHERE
    EmailContent like '%https://www.sabavn.com%';
	

/* update banner link */
UPDATE sabasports.wp_options 
SET 
    option_value = REPLACE(option_value,
        'https://www.sabavn.com',
        'https://sabavn.net')
WHERE
    option_value like '%https://www.sabavn.com%';
	
	

UPDATE sabasports.wp_team
SET 
    IconVN = REPLACE(IconVN,
        'https://www.sabavn.com',
        'https://sabavn.net')
WHERE
    IconVN like '%https://www.sabavn.com%';
	