

-- Хранимая процедура получения всех материалов клиента                                                               -- Добавлена

DELIMITER //

    DROP PROCEDURE IF EXISTS  getClientMaterialsAll //

    CREATE PROCEDURE getClientMaterialsAll (
            IN var_limit INT, IN var_from INT
            )

        BEGIN

            SELECT * FROM vtbl_news WHERE newsStatus=4 AND newsTimePublic<UNIX_TIMESTAMP(now())  ORDER BY newsTimePublic DESC LIMIT var_from,var_limit;                                               

        END;

//

DELIMITER ;

-- Хранимая процедура получения  тегов                                                        -- Добавлена

DELIMITER //

    DROP PROCEDURE IF EXISTS  getTagsAll //

    CREATE PROCEDURE getTagsAll (IN var_limit INT)

        BEGIN

            SELECT * FROM `vtbl_tag`  ORDER BY `tagId` DESC LIMIT var_limit;                                             

        END;

//

DELIMITER ;

-- Хранимая процедура получения популярных тегов                                                        -- Добавлена

DELIMITER //

    DROP PROCEDURE IF EXISTS  getPopularTags //

    CREATE PROCEDURE getPopularTags (IN var_limit INT)

        BEGIN

            SELECT `ctbl_tag`.`tagId`, count(*) AS `score`,tagName,tagDesc,tagSearch FROM `ctbl_tag` 
            LEFT JOIN `tbl_tag`
            ON `ctbl_tag`.`tagId`=`tbl_tag`.`tagId` 
            RIGHT JOIN `tbl_news`
            ON `ctbl_tag`.`newsId`=`tbl_news`.`newsId` AND  `tbl_news`.`newsStatus`=4
            GROUP BY `tagId` ORDER BY `score` DESC 
            LIMIT var_limit;                                             

        END;

//

DELIMITER ;

-- Хранимая процедура получения  брендов                                                        -- Добавлена

DELIMITER //

    DROP PROCEDURE IF EXISTS  getBrandsAll //

    CREATE PROCEDURE getBrandsAll (IN var_limit INT)

        BEGIN

            SELECT * FROM `vtbl_brand`  ORDER BY `brandId` DESC LIMIT var_limit;                                             

        END;

//

DELIMITER ;

-- Хранимая процедура получения популярных брендов                                                        -- Добавлена

DELIMITER //

    DROP PROCEDURE IF EXISTS  getPopularBrands //

    CREATE PROCEDURE getPopularBrands (IN var_limit INT)

        BEGIN

            SELECT `tbl_brand`.`brandId`, count(*) AS `score`,brandName,brandDesc,brandSearch,brandImg FROM `ctbl_brand` 
            LEFT JOIN `tbl_brand`
            ON `ctbl_brand`.`brandId`=`tbl_brand`.`brandId` 
            LEFT JOIN `tbl_news`
            ON `ctbl_brand`.`newsId`=`tbl_news`.`newsId` AND  `tbl_news`.`newsStatus`=4
            GROUP BY `brandId` ORDER BY `score` DESC 
            LIMIT var_limit;                                             

        END;

//

DELIMITER ;

-- Хранимая процедура полнотекстового поиска администратора                                                               -- Добавлена

======================================Новая fulltextClient


DELIMITER //

    DROP PROCEDURE IF EXISTS fulltextClient //

    CREATE PROCEDURE fulltextClient (IN var_search TEXT,IN var_layout1 TEXT,IN var_layout2 TEXT)

        BEGIN
            DECLARE var_count_results INT;            
            DROP TEMPORARY TABLE IF EXISTS search_results;
            CREATE TEMPORARY TABLE search_results                                          
	        	SELECT  
	            	vtbl_newsListClient.newsId,
	                vtbl_newsListClient.newsHeader,
	            	vtbl_newsListClient.newsSubheader,
	            	vtbl_newsListClient.newsText,
	                vtbl_newsListClient.newsStatus,
	            	vtbl_newsListClient.newsType,    
	                vtbl_newsListClient.newsMain,
	            	vtbl_newsListClient.newsUrl,
	            	vtbl_newsListClient.categoryId,
	                vtbl_newsListClient.categoryName,
	                vtbl_newsListClient.sourceId,
	            	vtbl_newsListClient.sourceName,
	            	vtbl_newsListClient.sourceLink,
	                vtbl_newsListClient.authorId,           	
	            	vtbl_newsListClient.authorName,
	            	vtbl_newsListClient.newsTimePublic, 
	            	vtbl_newsListClient.newsTimePublicFormat, 
	            	vtbl_newsListClient.newsTime,	            	          	
	                vtbl_newsListClient.newsTimeFormat            	    	
	        	FROM vtbl_newsListClient
	               INNER JOIN tbl_search
				   ON tbl_search.newsId=vtbl_newsListClient.newsId   
	                	WHERE MATCH (tbl_search.newsSearch)
						AGAINST (var_search IN BOOLEAN MODE); 
            SELECT count(newsId) FROM search_results INTO var_count_results;           
            


            IF var_count_results <> 0 THEN
                 SELECT * FROM search_results ORDER BY newsId DESC;
            END IF; 
                
            DROP TEMPORARY TABLE IF EXISTS search_results;
            CREATE TEMPORARY TABLE search_results                                          
	        	SELECT  
	            	vtbl_newsListClient.newsId,
	                vtbl_newsListClient.newsHeader,
	            	vtbl_newsListClient.newsSubheader,
	            	vtbl_newsListClient.newsText,
	                vtbl_newsListClient.newsStatus,
	            	vtbl_newsListClient.newsType,    
	                vtbl_newsListClient.newsMain,
	            	vtbl_newsListClient.newsUrl,
	            	vtbl_newsListClient.categoryId,
	                vtbl_newsListClient.categoryName,
	                vtbl_newsListClient.sourceId,
	            	vtbl_newsListClient.sourceName,
	            	vtbl_newsListClient.sourceLink,
	                vtbl_newsListClient.authorId,           	
	            	vtbl_newsListClient.authorName,
	            	vtbl_newsListClient.newsTimePublic, 
	            	vtbl_newsListClient.newsTimePublicFormat, 
	            	vtbl_newsListClient.newsTime,	            	          	
	                vtbl_newsListClient.newsTimeFormat             	    	
	        	FROM vtbl_newsListClient
	               INNER JOIN tbl_search
				   ON tbl_search.newsId=vtbl_newsListClient.newsId   
	                	WHERE MATCH (tbl_search.newsSearch)
						AGAINST (var_layout1 IN BOOLEAN MODE); 
            SELECT count(newsId) FROM search_results INTO var_count_results;           
            


            IF var_count_results <> 0 THEN
                 SELECT * FROM search_results ORDER BY newsId DESC;
            END IF; 
            DROP TEMPORARY TABLE IF EXISTS search_results;
            CREATE TEMPORARY TABLE search_results                                          
	        	SELECT  
	            	vtbl_newsListClient.newsId,
	                vtbl_newsListClient.newsHeader,
	            	vtbl_newsListClient.newsSubheader,
	            	vtbl_newsListClient.newsText,
	                vtbl_newsListClient.newsStatus,
	            	vtbl_newsListClient.newsType,    
	                vtbl_newsListClient.newsMain,
	            	vtbl_newsListClient.newsUrl,
	            	vtbl_newsListClient.categoryId,
	                vtbl_newsListClient.categoryName,
	                vtbl_newsListClient.sourceId,
	            	vtbl_newsListClient.sourceName,
	            	vtbl_newsListClient.sourceLink,
	                vtbl_newsListClient.authorId,           	
	            	vtbl_newsListClient.authorName,
	            	vtbl_newsListClient.newsTimePublic, 
	            	vtbl_newsListClient.newsTimePublicFormat, 
	            	vtbl_newsListClient.newsTime,	            	          	
	                vtbl_newsListClient.newsTimeFormat             	    	
	        	FROM vtbl_newsListClient
	               INNER JOIN tbl_search
				   ON tbl_search.newsId=vtbl_newsListClient.newsId   
	                	WHERE MATCH (tbl_search.newsSearch)
						AGAINST (var_layout2 IN BOOLEAN MODE); 
            SELECT count(newsId) FROM search_results INTO var_count_results;          
            


            IF var_count_results <> 0 THEN
                 SELECT * FROM search_results ORDER BY newsId DESC;
            END IF; 
                
        END;

//

DELIMITER ;




DELIMITER //

    DROP PROCEDURE IF EXISTS fulltextClientLight //

    CREATE PROCEDURE fulltextClientLight (IN var_search TEXT,IN var_start INT,IN var_count INT)

        BEGIN
           SELECT * FROM vtbl_newsListClient
               INNER JOIN tbl_search
                	ON tbl_search.newsId=vtbl_newsListClient.newsId   
                WHERE MATCH (tbl_search.newsSearch)
                AGAINST (var_search IN BOOLEAN MODE) ORDER BY vtbl_newsListClient.newsId DESC LIMIT var_start,var_count;                                              

        END;

//

DELIMITER ;

DELIMITER //

-- Хранимая процедура поиска брендов
DROP PROCEDURE IF EXISTS  brandSearchLight //
CREATE PROCEDURE `brandSearchLight`(IN var_string TEXT)
BEGIN


            SELECT * FROM vtbl_brand WHERE brandSearch LIKE CONCAT('%', var_string, '%') OR brandName LIKE CONCAT('%', var_string, '%');

        END; //

DELIMITER ;







