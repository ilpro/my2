



--
-- Процедуры
--

-- -------------------------------------------------------------------------------------------------------- Материалы

DELIMITER //
-- Хранимая процедура полнотекстового поиска администратора СТАРАЯ!!!!
DROP PROCEDURE IF EXISTS  fulltextAdmin //
CREATE PROCEDURE `fulltextAdmin`(IN var_search TEXT,IN var_layout1 TEXT,IN var_layout2 TEXT)
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


        END; //

       
DELIMITER ;








------------------------Новая fulltextAdmin

DELIMITER //

    DROP PROCEDURE IF EXISTS fulltextAdmin //

    CREATE PROCEDURE fulltextAdmin (IN var_search TEXT,IN var_layout1 TEXT,IN var_layout2 TEXT)

        BEGIN
            DECLARE var_count_results INT;            
            DROP TEMPORARY TABLE IF EXISTS search_results;
            CREATE TEMPORARY TABLE search_results                                          
	        	SELECT  
	            	vtbl_newsListAdmin.newsId,
	                vtbl_newsListAdmin.newsHeader,
	            	vtbl_newsListAdmin.newsSubheader,
	            	vtbl_newsListAdmin.newsText,
	                vtbl_newsListAdmin.newsStatus,
	            	vtbl_newsListAdmin.newsType,    
	                vtbl_newsListAdmin.newsMain,
	            	vtbl_newsListAdmin.newsUrl,
	            	vtbl_newsListAdmin.categoryId,
	                vtbl_newsListAdmin.categoryName,
	                vtbl_newsListAdmin.sourceId,
	            	vtbl_newsListAdmin.sourceName,
	            	vtbl_newsListAdmin.sourceLink,
	                vtbl_newsListAdmin.authorId,           	
	            	vtbl_newsListAdmin.authorName,
	                vtbl_newsListAdmin.userId,
	                vtbl_newsListAdmin.userName,
	            	vtbl_newsListAdmin.newsTime,
	            	vtbl_newsListAdmin.newsTimePublic,
	                vtbl_newsListAdmin.newsTimeUpdate,             	
	                vtbl_newsListAdmin.newsTimeFormat             	    	
	        	FROM vtbl_newsListAdmin
	               INNER JOIN tbl_search
				   ON tbl_search.newsId=vtbl_newsListAdmin.newsId   
	                	WHERE MATCH (tbl_search.newsSearch)
						AGAINST (var_search IN BOOLEAN MODE); 
            SELECT count(newsId) FROM search_results INTO var_count_results;           
            


            IF var_count_results <> 0 THEN
                 SELECT * FROM search_results ORDER BY newsId DESC;
            ELSE 
                
                DROP TEMPORARY TABLE IF EXISTS search_results;
                CREATE TEMPORARY TABLE search_results                                          
                            SELECT  
                            vtbl_newsListAdmin.newsId,
                            vtbl_newsListAdmin.newsHeader,
                            vtbl_newsListAdmin.newsSubheader,
                            vtbl_newsListAdmin.newsText,
                            vtbl_newsListAdmin.newsStatus,
                            vtbl_newsListAdmin.newsType,    
                            vtbl_newsListAdmin.newsMain,
                            vtbl_newsListAdmin.newsUrl,
                            vtbl_newsListAdmin.categoryId,
                            vtbl_newsListAdmin.categoryName,
                            vtbl_newsListAdmin.sourceId,
                            vtbl_newsListAdmin.sourceName,
                            vtbl_newsListAdmin.sourceLink,
                            vtbl_newsListAdmin.authorId,           	
                            vtbl_newsListAdmin.authorName,
                            vtbl_newsListAdmin.userId,
                            vtbl_newsListAdmin.userName,
                            vtbl_newsListAdmin.newsTime,
                            vtbl_newsListAdmin.newsTimePublic,
                            vtbl_newsListAdmin.newsTimeUpdate,             	
                            vtbl_newsListAdmin.newsTimeFormat             	    	
                            FROM vtbl_newsListAdmin
                           INNER JOIN tbl_search
                                       ON tbl_search.newsId=vtbl_newsListAdmin.newsId   
                                    WHERE MATCH (tbl_search.newsSearch)
                                                    AGAINST (var_layout1 IN BOOLEAN MODE); 
                SELECT count(newsId) FROM search_results INTO var_count_results;           



                IF var_count_results <> 0 THEN
                     SELECT * FROM search_results ORDER BY newsId DESC;
                ELSE 
                    DROP TEMPORARY TABLE IF EXISTS search_results;
                    CREATE TEMPORARY TABLE search_results                                          
                                SELECT  
                                vtbl_newsListAdmin.newsId,
                                vtbl_newsListAdmin.newsHeader,
                                vtbl_newsListAdmin.newsSubheader,
                                vtbl_newsListAdmin.newsText,
                                vtbl_newsListAdmin.newsStatus,
                                vtbl_newsListAdmin.newsType,    
                                vtbl_newsListAdmin.newsMain,
                                vtbl_newsListAdmin.newsUrl,
                                vtbl_newsListAdmin.categoryId,
                                vtbl_newsListAdmin.categoryName,
                                vtbl_newsListAdmin.sourceId,
                                vtbl_newsListAdmin.sourceName,
                                vtbl_newsListAdmin.sourceLink,
                                vtbl_newsListAdmin.authorId,           	
                                vtbl_newsListAdmin.authorName,
                                vtbl_newsListAdmin.userId,
                                vtbl_newsListAdmin.userName,
                                vtbl_newsListAdmin.newsTime,
                                vtbl_newsListAdmin.newsTimePublic,
                                vtbl_newsListAdmin.newsTimeUpdate,             	
                                vtbl_newsListAdmin.newsTimeFormat             	    	
                                FROM vtbl_newsListAdmin
                               INNER JOIN tbl_search
                                           ON tbl_search.newsId=vtbl_newsListAdmin.newsId   
                                        WHERE MATCH (tbl_search.newsSearch)
                                                        AGAINST (var_layout2 IN BOOLEAN MODE); 
                    SELECT count(newsId) FROM search_results INTO var_count_results;           



                    IF var_count_results <> 0 THEN
                         SELECT * FROM search_results ORDER BY newsId DESC;
                    END IF; 
                END IF;
            END IF;
            DROP TEMPORARY TABLE IF EXISTS search_results;
        END; //

DELIMITER ;


-------------------------------облегченная fulltextAdmin
DELIMITER //

    DROP PROCEDURE IF EXISTS fulltextAdminLight //

    CREATE PROCEDURE fulltextAdminLight (IN var_search TEXT)

        BEGIN
           SELECT * FROM vtbl_newsListAdmin
               INNER JOIN tbl_search
                	ON tbl_search.newsId=vtbl_newsListAdmin.newsId   
                WHERE MATCH (tbl_search.newsSearch)
                AGAINST (var_search IN BOOLEAN MODE) ORDER BY vtbl_newsListAdmin.newsId DESC;                                              

        END; //

DELIMITER ;








DELIMITER //


-- Хранимая процедура полнотекстового поиска пользователя


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
                        vtbl_newsListClient.categoryTranslit,
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
                        vtbl_newsListClient.categoryTranslit,
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
                        vtbl_newsListClient.categoryTranslit,
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

-- Хранимая процедура получения поискового текста для полнотекстового поиска
DROP PROCEDURE IF EXISTS  getSearchText //
CREATE PROCEDURE `getSearchText`(IN `var_newsId` INT)
BEGIN
SELECT
                vtbl_newsListAdmin.newsId,
                newsHeader,
                newsSubheader,
                newsText,
                newsVideoDesc,
                sourceName,
                sourceLink,
                authorName,
                newsSeoTitle,
                newsSeoDesc,
                newsSeoKeywords,
                newsTimePublic,
              	GROUP_CONCAT(DISTINCT IFNULL(ctbl_img.imgDesc,'') SEPARATOR ' ') AS imgDescs,
                GROUP_CONCAT(DISTINCT IFNULL(tbl_tag.tagSearch,'') SEPARATOR ' ') AS tags,
                GROUP_CONCAT(DISTINCT IFNULL(tbl_brand.brandSearch,'') SEPARATOR ' ') AS brands,
                GROUP_CONCAT(DISTINCT IFNULL(tbl_region.regionSearch,'') SEPARATOR ' ') AS regions
FROM vtbl_newsListAdmin
                LEFT JOIN ctbl_img
                    on vtbl_newsListAdmin.newsId=ctbl_img.newsId
                LEFT JOIN ctbl_tag
                    on vtbl_newsListAdmin.newsId=ctbl_tag.newsId
                LEFT JOIN tbl_tag
                    on ctbl_tag.tagId=tbl_tag.tagId
                LEFT JOIN ctbl_brand
                    on vtbl_newsListAdmin.newsId=ctbl_brand.newsId
                LEFT JOIN tbl_brand
                    on ctbl_brand.brandId=tbl_brand.brandId
                LEFT JOIN ctbl_region
                    on vtbl_newsListAdmin.newsId=ctbl_region.newsId
                LEFT JOIN tbl_region
                    on ctbl_region.regionId=tbl_region.regionId
                    WHERE vtbl_newsListAdmin.newsId=var_newsId;
    END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура удаления картинок новости
DROP PROCEDURE IF EXISTS  imgDel //
CREATE PROCEDURE `imgDel`(IN `img_id` INT(11))
    NO SQL
DELETE FROM ctbl_img WHERE imgId=img_id;          //

DELIMITER ;
DELIMITER //


-- Хранимая процедура получения картинок новости
DROP PROCEDURE IF EXISTS  imgGet //
CREATE PROCEDURE `imgGet`(IN `news_id` INT(11))
    NO SQL
SELECT imgId, imgName, imgDesc, imgMain FROM ctbl_img WHERE newsId=news_id;          //

DELIMITER ;
DELIMITER //

-- Хранимая процедура получения картинки новости по айди картинки
DROP PROCEDURE IF EXISTS  imgGetId //
CREATE PROCEDURE `imgGetId`(IN img_Id INT)
BEGIN
        	SELECT imgId, imgName, newsId, imgMain FROM ctbl_img WHERE imgId=img_id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура получения главной картинки новости
DROP PROCEDURE IF EXISTS  imgGetMain //
CREATE PROCEDURE `imgGetMain`(IN news_id INT)
BEGIN

        	SELECT imgId, imgName, imgDesc  FROM ctbl_img WHERE newsId=news_id AND imgMain=1;

        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура обновления описания картинок новости
DROP PROCEDURE IF EXISTS  imgSetDesc //
CREATE PROCEDURE `imgSetDesc`(IN img_Id INT, IN img_Desc TEXT)
BEGIN
        	UPDATE ctbl_img SET imgDesc=img_desc WHERE imgId=img_id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура обновления главной картинки новости
DROP PROCEDURE IF EXISTS  imgSetMain //
CREATE PROCEDURE `imgSetMain`(IN img_Id INT, IN news_Id INT)
BEGIN
        	UPDATE ctbl_img SET imgMain=0 WHERE newsId=news_id;
            UPDATE ctbl_img SET imgMain=1 WHERE imgId=img_id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура создания новости
DROP PROCEDURE IF EXISTS  insertNew //
CREATE PROCEDURE `insertNew`(IN user_Id INT)
BEGIN

            INSERT INTO tbl_news SET `newsTime`=NOW(), `newsTimePublic`=NOW(), `newsStatus`=2, `userId`=user_Id;

            SELECT `newsTime` AS saved, newsId AS id FROM tbl_news ORDER BY newsId DESC LIMIT 1;

        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура удаления новости
DROP PROCEDURE IF EXISTS  newsDel //
CREATE PROCEDURE `newsDel`(IN news_Id INT)
BEGIN
        	DELETE FROM tbl_news WHERE newsId=news_Id;
            DELETE FROM tbl_search WHERE newsId=news_Id;
            DELETE FROM ctbl_tag WHERE newsId=news_Id;
            DELETE FROM ctbl_region WHERE newsId=news_Id;
            DELETE FROM ctbl_brand WHERE newsId=news_Id;
            DELETE FROM ctbl_widgettag WHERE newsId=news_Id;
            DELETE FROM ctbl_connect WHERE newsId=news_Id OR newsConnect=news_Id;
            DELETE FROM ctbl_img WHERE newsId=news_Id;
            SELECT newsId FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура сохранения новости главной кнопкой SAVE
DROP PROCEDURE IF EXISTS  saveAll //
CREATE PROCEDURE `saveAll`(IN `news_Id` INT, IN `news_Header` MEDIUMTEXT, IN `news_Subheader` MEDIUMTEXT, IN `news_Text` LONGTEXT, IN `news_SeoDesc` VARCHAR(255), IN `news_SeoKeywords` VARCHAR(255), IN `news_SeoTitle` VARCHAR(255), IN `news_Url` VARCHAR(255), IN `news_Video` VARCHAR(255), IN `news_VideoDesc` TEXT, IN `news_Search` LONGTEXT, IN `category_Id` INT, IN `news_Time` DATETIME, IN `news_Status` INT, IN `news_SocText` VARCHAR(255), IN `news_Type` INT)
BEGIN

            UPDATE tbl_news SET

            	newsHeader=news_Header,

				newsSubheader=news_Subheader,

                newsStatus=news_Status,

                newsType=news_Type,

				newsText=news_Text,

				newsSeoDesc=news_SeoDesc,

				newsSeoKeywords=news_SeoKeywords,

				newsSeoTitle=news_SeoTitle,

				newsUrl=news_Url,

				newsVideo=news_Video,

				newsVideoDesc=news_VideoDesc,

				categoryId=category_Id,

                newsTimePublic=news_Time,

                newsSocText=news_SocText


            WHERE newsId=news_Id;

            DELETE FROM tbl_search WHERE newsId=news_Id;

            INSERT INTO tbl_search (newsId,newsSearch) VALUES (news_Id,news_Search);

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура установки категории
DROP PROCEDURE IF EXISTS  setNewsCategory //
CREATE PROCEDURE `setNewsCategory`(

        IN news_Id INT,

        IN news_category INT)
BEGIN

            UPDATE tbl_news SET categoryId=news_category WHERE newsId=news_Id;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура установки "Добавить новость в галереи"
DROP PROCEDURE IF EXISTS  setNewsIsGallery //
CREATE PROCEDURE `setNewsIsGallery`(
        IN news_Id INT,
        IN news_isGallery TINYINT)
BEGIN
            UPDATE tbl_news SET newsIsGallery=news_isGallery WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура установки "Добавить новость в видео"
DROP PROCEDURE IF EXISTS  setNewsIsVideo //
CREATE PROCEDURE `setNewsIsVideo`(
        IN news_Id INT,
        IN news_isVideo TINYINT)
BEGIN
            UPDATE tbl_news SET newsIsVideo=news_isVideo WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура установки "Главная новость"
DROP PROCEDURE IF EXISTS  setNewsMain //
CREATE PROCEDURE `setNewsMain`(
        IN news_Id INT,
        IN news_main TINYINT)
BEGIN
            UPDATE tbl_news SET newsMain=news_main WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура установки "Добавить публикацию в соц. сеть"
DROP PROCEDURE IF EXISTS  setNewsPostToSoc //
CREATE PROCEDURE `setNewsPostToSoc`(IN `news_Id` INT, IN `news_setNewsPostToSoc` INT, IN `news_socName` VARCHAR(255))
BEGIN

            SET @t1 = CONCAT('UPDATE tbl_news SET ', news_socName, '=',news_setNewsPostToSoc,' WHERE `newsId`=',news_Id);
            PREPARE stmt3 FROM @t1;
            EXECUTE stmt3;
            DEALLOCATE PREPARE stmt3;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура динамического сохранения параметров новости
DROP PROCEDURE IF EXISTS  setNewsParam //
CREATE PROCEDURE `setNewsParam`(IN `news_paramName` VARCHAR(255),IN `news_paramValue` VARCHAR(255), IN `news_Id` INT)
BEGIN

            SET @t1 = CONCAT('UPDATE tbl_news SET ', news_paramName, '=',news_paramValue,' WHERE `newsId`=',news_Id);
            PREPARE stmt3 FROM @t1;
            EXECUTE stmt3;
            DEALLOCATE PREPARE stmt3;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура установки "Добавить время публикации в соц. сеть"
DROP PROCEDURE IF EXISTS  setNewsSocTime //
CREATE PROCEDURE `setNewsSocTime`(IN `news_Id` INT, IN `news_SocTime` VARCHAR(55))
BEGIN

            UPDATE tbl_news SET newsSocTime=STR_TO_DATE(news_SocTime, '%H:%i %d.%m.%Y') WHERE newsId=news_Id;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура установки статуса новости
DROP PROCEDURE IF EXISTS  setNewsStatus //
CREATE PROCEDURE `setNewsStatus`(

        IN news_Id INT,

        IN news_status INT)
BEGIN

            UPDATE tbl_news SET newsStatus=news_status WHERE newsId=news_Id;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура установки типа новости
DROP PROCEDURE IF EXISTS  setNewsType //
CREATE PROCEDURE `setNewsType`(

        IN news_Id INT,

        IN news_type INT)
BEGIN

            UPDATE tbl_news SET newsType=news_type WHERE newsId=news_Id;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //



DELIMITER ;
DELIMITER //
-- ---------------------------------------------------------------------------------------------------------- Регионы



-- Хранимая процедура сохранения региона кнопкой
DROP PROCEDURE IF EXISTS  saveRegion //
CREATE PROCEDURE `saveRegion`(

      	IN Region_Id INT,

		IN Region_Name VARCHAR(255),

		IN Region_Search VARCHAR(255)

		)
BEGIN

            UPDATE tbl_region SET

            	regionName=Region_Name,

				regionSearch=Region_Search

            WHERE regionId=Region_Id;

        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура удаления региона
DROP PROCEDURE IF EXISTS  regionDel //
CREATE PROCEDURE `regionDel`(IN region_Id INT)
BEGIN
        	DELETE FROM tbl_region WHERE regionId=region_Id;
            DELETE FROM ctbl_region WHERE regionId=region_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура получения регионов новости
DROP PROCEDURE IF EXISTS  regionGet //
CREATE PROCEDURE `regionGet`(IN news_Id INT)
BEGIN
        	SELECT
        		tbl_region.regionId,
        		tbl_region.regionName FROM ctbl_region
                LEFT JOIN tbl_region
                    on ctbl_region.regionId=tbl_region.regionId
                WHERE ctbl_region.newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура удаления региона новости
DROP PROCEDURE IF EXISTS  regionRemove //
CREATE PROCEDURE `regionRemove`(IN news_Id INT,IN region_id INT)
BEGIN
        	DELETE FROM ctbl_region WHERE regionId=region_id AND newsId=news_Id;
            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура добавления региона новости
DROP PROCEDURE IF EXISTS  regionSave //
CREATE PROCEDURE `regionSave`(IN news_Id INT,IN region_id INT)
BEGIN
        	DELETE FROM ctbl_region WHERE regionId=region_id AND newsId=news_Id;
        	INSERT INTO ctbl_region (newsId,regionId) VALUES (news_Id,region_id);
            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура сохранения нового региона
DROP PROCEDURE IF EXISTS  regionSaveNew //
CREATE PROCEDURE `regionSaveNew`(IN region_name VARCHAR(255), IN region_search VARCHAR(255))
BEGIN
        	DECLARE var_count_results INT;
        	SELECT count(regionId) FROM tbl_region WHERE regionName=region_name INTO var_count_results;
            IF var_count_results <> 0 THEN
                 SELECT 1 AS 'exists';
            ELSE
        		INSERT INTO tbl_region (regionName,regionSearch) VALUES (region_name,region_search);
				SELECT LAST_INSERT_ID() AS saved;
			END IF;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура поиска региона
DROP PROCEDURE IF EXISTS  regionSearch //
CREATE PROCEDURE `regionSearch`(IN var_string TEXT,IN var_layout TEXT)
BEGIN
            DECLARE var_count_results INT;
            DROP TEMPORARY TABLE IF EXISTS search_results;
            CREATE TEMPORARY TABLE search_results
            SELECT regionId,regionName FROM tbl_region WHERE regionSearch LIKE CONCAT('%', var_string, '%');
            SELECT count(regionId) FROM search_results INTO var_count_results;
            IF var_count_results <> 0 THEN
                 SELECT * FROM search_results;
            ELSE
                DROP TEMPORARY TABLE IF EXISTS search_results;
                SELECT regionId,regionName FROM tbl_region WHERE regionSearch LIKE CONCAT('%', var_layout, '%');
            END IF;
        END; //



DELIMITER ;
DELIMITER //

-- Хранимая процедура админ поиска региона
DROP PROCEDURE IF EXISTS  regionAdminSearch //
CREATE PROCEDURE `regionAdminSearch`(IN var_string TEXT)
BEGIN           
            SELECT * FROM tbl_region WHERE regionSearch LIKE CONCAT('%', var_string, '%');
        END; //



DELIMITER ;
-- ---------------------------------------------------------------------------------------------------------- Бренды
DELIMITER //



-- Хранимая процедура сохранения бренда кнопкой
DROP PROCEDURE IF EXISTS  saveBrand //
CREATE PROCEDURE `saveBrand`(

      	IN Brand_Id INT,

		IN Brand_Name VARCHAR(255),

		IN Brand_Desc TEXT,

		IN Brand_Search VARCHAR(255),

		IN Brand_Type INT,

		IN Brand_Sort_Name VARCHAR(255),

		IN Brand_Active INT

		)
BEGIN

            UPDATE tbl_brand SET

            	brandName=Brand_Name,

				brandDesc=Brand_Desc,

				brandSearch=Brand_Search,

				brandType=Brand_Type,

				brandSortName=Brand_Sort_Name,

				brandActive=Brand_Active

            WHERE brandId=Brand_Id;

        END; //
        
        DELIMITER ;
DELIMITER //
        
        
-- Хранимая процедура удаления бренда
DROP PROCEDURE IF EXISTS  brandDel //
CREATE PROCEDURE `brandDel`(IN brand_Id INT)
BEGIN
        	DELETE FROM tbl_brand WHERE brandId=brand_Id;
            DELETE FROM ctbl_brand WHERE brandId=brand_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура получения брендов новости
DROP PROCEDURE IF EXISTS  brandGet //
CREATE PROCEDURE `brandGet`(IN news_Id INT)
BEGIN

        	SELECT

        		tbl_brand.brandId,

        		ctbl_brand.brandSmile,

        		tbl_brand.brandName FROM ctbl_brand

                LEFT JOIN tbl_brand

                    on ctbl_brand.brandId=tbl_brand.brandId

                WHERE ctbl_brand.newsId=news_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура удаления бренда новости
DROP PROCEDURE IF EXISTS  brandRemove //
CREATE PROCEDURE `brandRemove`(IN news_Id INT,IN brand_id INT)
BEGIN

        	DELETE FROM ctbl_brand WHERE brandId=brand_id AND newsId=news_Id;

            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура добавления бренда новости
DROP PROCEDURE IF EXISTS  brandSave //
CREATE PROCEDURE `brandSave`(IN news_Id INT,IN brand_id INT,IN brand_smile INT)
BEGIN

        	DELETE FROM ctbl_brand WHERE brandId=brand_id AND newsId=news_Id;

        	INSERT INTO ctbl_brand (newsId,brandId,brandSmile) VALUES (news_Id,brand_id,brand_smile);

            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура поиска брендов
DROP PROCEDURE IF EXISTS  brandSearch //
CREATE PROCEDURE `brandSearch`(IN var_string TEXT,IN var_layout1 TEXT,IN var_layout2 TEXT)
BEGIN

            DECLARE var_count_results INT;

            DROP TEMPORARY TABLE IF EXISTS search_results;

            CREATE TEMPORARY TABLE search_results

            SELECT * FROM vtbl_brand WHERE brandSearch LIKE CONCAT('%', var_string, '%') OR brandName LIKE CONCAT('%', var_string, '%');

            SELECT count(brandId) FROM search_results INTO var_count_results;

            IF var_count_results <> 0 THEN

                 SELECT * FROM search_results;

            ELSE

                DROP TEMPORARY TABLE IF EXISTS search_results;

                CREATE TEMPORARY TABLE search_results

                SELECT * FROM vtbl_brand WHERE brandSearch LIKE CONCAT('%', var_layout1, '%') OR brandName LIKE CONCAT('%', var_layout1, '%');

                SELECT count(brandId) FROM search_results INTO var_count_results;
                
                IF var_count_results <> 0 THEN

                SELECT * FROM search_results;

                ELSE

                DROP TEMPORARY TABLE IF EXISTS search_results;
                
                SELECT * FROM vtbl_brand WHERE brandSearch LIKE CONCAT('%', var_layout2, '%') OR brandName LIKE CONCAT('%', var_layout2, '%');
                
                END IF;
                
            END IF;

        END; //

DELIMITER ;
DELIMITER //
-- Хранимая процедура полученния популярных брендов
DROP PROCEDURE IF EXISTS  getPopularBrands //
CREATE PROCEDURE `getPopularBrands`(IN var_limit INT)
BEGIN

            SELECT `tbl_brand`.`brandId`, count(*) AS `score`,brandName,brandDesc,brandSearch,brandImg FROM `ctbl_brand`
            LEFT JOIN `tbl_brand`
            ON `ctbl_brand`.`brandId`=`tbl_brand`.`brandId`
            LEFT JOIN `tbl_news`
            ON `ctbl_brand`.`newsId`=`tbl_news`.`newsId` AND  `tbl_news`.`newsStatus`=4
            GROUP BY `brandId` ORDER BY `score` DESC
            LIMIT var_limit;

        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура полученния всех брендов
DROP PROCEDURE IF EXISTS  getBrandsAll //
CREATE PROCEDURE `getBrandsAll`(IN var_limit INT)
BEGIN

            SELECT * FROM `vtbl_brand`  ORDER BY `brandId` DESC LIMIT var_limit;

        END; //
       DELIMITER ;
DELIMITER //

-- Хранимая процедура получения картинок бренду
DROP PROCEDURE IF EXISTS  imgbrandGet //
CREATE PROCEDURE `imgbrandGet`(IN `brand_id` INT(11))
    NO SQL
SELECT imgId, imgName, imgDesc, imgMain FROM ctbl_brandimg WHERE brandId=brand_id;          //

DELIMITER ;
DELIMITER //
-- Хранимая процедура получения картинки бренда по айди картинки
DROP PROCEDURE IF EXISTS  imgBrandGetId //
CREATE PROCEDURE `imgBrandGetId`(IN img_Id INT)
BEGIN
        	SELECT imgId, imgName, brandId, imgMain FROM ctbl_brandimg WHERE imgId=img_id;
        END; //
DELIMITER ;
DELIMITER //
-- Хранимая процедура обновления главной картинки бренда
DROP PROCEDURE IF EXISTS  imgSetBrandMain //
CREATE PROCEDURE `imgSetBrandMain`(IN img_Id INT, IN brand_Id INT)
BEGIN
        	UPDATE ctbl_brandimg SET imgMain=0 WHERE brandId=brand_id;
            UPDATE ctbl_brandimg SET imgMain=1 WHERE imgId=img_id;
        END; //
DELIMITER ;
DELIMITER //

-- Хранимая процедура удаления картинок бренда
DROP PROCEDURE IF EXISTS  imgBrandDel //
CREATE PROCEDURE `imgBrandDel`(IN `img_id` INT(11))
    NO SQL
DELETE FROM ctbl_brandimg WHERE imgId=img_id;          //



-- ---------------------------------------------------------------------------------------------------------- Источники
DELIMITER ;
DELIMITER //


-- Хранимая процедура получения источника новости
DROP PROCEDURE IF EXISTS  sourceGet //
CREATE PROCEDURE `sourceGet`(IN news_Id INT)
BEGIN
        	SELECT
        		tbl_source.sourceId,
        		tbl_source.sourceName,
        		tbl_source.sourceLink FROM tbl_news
                LEFT JOIN tbl_source
                    on tbl_news.sourceId=tbl_source.sourceId
                WHERE tbl_news.newsId=news_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура удаления региона новости
DROP PROCEDURE IF EXISTS  sourceRemove //
CREATE PROCEDURE `sourceRemove`(IN news_Id INT,IN region_id INT)
BEGIN
        	UPDATE tbl_news SET sourceId=0, newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //



-- Хранимая процедура добавления региона новости
DROP PROCEDURE IF EXISTS  sourceSave //
CREATE PROCEDURE `sourceSave`(IN news_Id INT,IN source_id INT)
BEGIN
         	UPDATE tbl_news SET sourceId=source_id, newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура поиска региона
DROP PROCEDURE IF EXISTS  sourceSearch //
CREATE PROCEDURE `sourceSearch`(IN var_string TEXT,IN var_layout TEXT)
BEGIN
            DECLARE var_count_results INT;
            DROP TEMPORARY TABLE IF EXISTS search_results;
            CREATE TEMPORARY TABLE search_results
            SELECT sourceId,sourceName,sourceLink FROM tbl_source WHERE sourceSearch LIKE CONCAT('%', var_string, '%');
            SELECT count(sourceId) FROM search_results INTO var_count_results;
            IF var_count_results <> 0 THEN
                 SELECT * FROM search_results;
            ELSE
                DROP TEMPORARY TABLE IF EXISTS search_results;
                SELECT sourceId,sourceName,sourceLink FROM tbl_source WHERE sourceSearch LIKE CONCAT('%', var_layout, '%');
            END IF;
        END; //


DELIMITER ;
DELIMITER //


-- ---------------------------------------------------------------------------------------------------------- Автор



-- Хранимая процедура удаления бренда
DROP PROCEDURE IF EXISTS  authorDel //
CREATE PROCEDURE `authorDel`(IN author_Id INT)
BEGIN
        	DELETE FROM tbl_author WHERE authorId=author_Id;
            UPDATE tbl_news SET authorId = 0 WHERE authorId=author_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура получения автора новости
DROP PROCEDURE IF EXISTS  authorGet //
CREATE PROCEDURE `authorGet`(IN news_Id INT)
BEGIN
        	SELECT
        		tbl_author.authorId,
        		tbl_author.authorName FROM tbl_news
                LEFT JOIN tbl_author
                    on tbl_news.authorId=tbl_author.authorId
                WHERE tbl_news.newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура удаления автора новости
DROP PROCEDURE IF EXISTS  authorRemove //
CREATE PROCEDURE `authorRemove`(IN news_Id INT,IN autor_id INT)
BEGIN
        	UPDATE tbl_news SET authorId=0, newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //


DELIMITER ;
DELIMITER //


-- Хранимая процедура добавления автора новости
DROP PROCEDURE IF EXISTS  authorSave //
CREATE PROCEDURE `authorSave`(IN news_Id INT,IN author_id INT)
BEGIN
         	UPDATE tbl_news SET authorId=author_id, newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура поиска автора
DROP PROCEDURE IF EXISTS  authorSearch //
CREATE PROCEDURE `authorSearch`(IN var_string TEXT,IN var_layout TEXT)
BEGIN
            DECLARE var_count_results INT;
            DROP TEMPORARY TABLE IF EXISTS search_results;
            CREATE TEMPORARY TABLE search_results
            SELECT authorId,authorName FROM tbl_author WHERE authorSearch LIKE CONCAT('%', var_string, '%');
            SELECT count(authorId) FROM search_results INTO var_count_results;
            IF var_count_results <> 0 THEN
                 SELECT * FROM search_results;
            ELSE
                DROP TEMPORARY TABLE IF EXISTS search_results;
                SELECT authorId,authorName FROM tbl_author WHERE authorSearch LIKE CONCAT('%', var_layout, '%');
            END IF;
        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура сохранения автора кнопкой
DROP PROCEDURE IF EXISTS  saveAuthor //
CREATE PROCEDURE `saveAuthor`(

      	IN Author_Id INT,

		IN Author_Name VARCHAR(255),

		IN Author_Desc TEXT,

		IN Author_Search VARCHAR(255)

		)
BEGIN

            UPDATE tbl_author SET

            	authorName=Author_Name,

				authorDesc=Author_Desc,

				authorSearch=Author_Search

            WHERE authorId=Author_Id;

        END; //

DELIMITER ;
DELIMITER //

-- ------------------------------------------------------------------------------------------ Связи между материалами



-- Хранимая процедура получения связанных новостей
DROP PROCEDURE IF EXISTS  connectGet //
CREATE PROCEDURE `connectGet`(IN `news_Id` INT)
BEGIN
        	SELECT
            	vtbl_newsListAdmin.newsId,
            	vtbl_newsListAdmin.newsHeader,
            	vtbl_newsListAdmin.newsSubheader FROM ctbl_connect
                LEFT JOIN vtbl_newsListAdmin
                    on ctbl_connect.newsConnect=vtbl_newsListAdmin.newsId
                WHERE ctbl_connect.newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура удаления связи новости
DROP PROCEDURE IF EXISTS  connectRemove //
CREATE PROCEDURE `connectRemove`(IN news_Id INT,IN news_connect INT)
BEGIN
        	DELETE FROM ctbl_connect WHERE newsConnect=news_connect AND newsId=news_Id;
            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура добавления связи новости
DROP PROCEDURE IF EXISTS  connectSave //
CREATE PROCEDURE `connectSave`(IN news_Id INT,IN news_connect INT)
BEGIN
        	DELETE FROM ctbl_connect WHERE newsConnect=news_connect AND newsId=news_Id;
        	INSERT INTO ctbl_connect (newsId,newsConnect) VALUES (news_Id,news_connect);
            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- ---------------------------------------------------------------------------------------------------------- Теги



-- Хранимая процедура удаления тега
DROP PROCEDURE IF EXISTS  tagDel //
CREATE PROCEDURE `tagDel`(IN tag_Id INT)
BEGIN
        	DELETE FROM tbl_tag WHERE tagId=tag_Id;
            DELETE FROM ctbl_tag WHERE tagId=tag_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура получения тегов новости
DROP PROCEDURE IF EXISTS  tagGet //
CREATE PROCEDURE `tagGet`(IN news_Id INT)
BEGIN
        	SELECT
        		tbl_tag.tagId,
        		tbl_tag.tagName FROM ctbl_tag
                LEFT JOIN tbl_tag
                    on ctbl_tag.tagId=tbl_tag.tagId
                WHERE ctbl_tag.newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура удаления тега новости
DROP PROCEDURE IF EXISTS  tagRemove //
CREATE PROCEDURE `tagRemove`(IN news_Id INT,IN tag_id INT)
BEGIN
        	DELETE FROM ctbl_tag WHERE tagId=tag_id AND newsId=news_Id;
            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура добавления тега новости
DROP PROCEDURE IF EXISTS  tagSave //
CREATE PROCEDURE `tagSave`(IN news_Id INT,IN tag_id INT)
BEGIN
        	DELETE FROM ctbl_tag WHERE tagId=tag_id AND newsId=news_Id;
        	INSERT INTO ctbl_tag (newsId,tagId) VALUES (news_Id,tag_id);
            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура сохранения нового тега
DROP PROCEDURE IF EXISTS  tagSaveNew //
CREATE PROCEDURE `tagSaveNew`(IN tag_name VARCHAR(255), IN tag_search VARCHAR(255))
BEGIN
        	DECLARE var_count_results INT;
        	SELECT count(tagId) FROM tbl_tag WHERE tagName=tag_name INTO var_count_results;
            IF var_count_results <> 0 THEN
                 SELECT 1 AS 'exists';
            ELSE
        		INSERT INTO tbl_tag (tagName,tagSearch) VALUES (tag_name,tag_search);
				SELECT LAST_INSERT_ID() AS saved;
			END IF;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура поиска тега
DROP PROCEDURE IF EXISTS  tagSearch //
CREATE PROCEDURE `tagSearch`(IN var_string TEXT,IN var_layout TEXT)
BEGIN
            DECLARE var_count_results INT;
            DROP TEMPORARY TABLE IF EXISTS search_results;
            CREATE TEMPORARY TABLE search_results
            SELECT tagId,tagName FROM tbl_tag WHERE tagSearch LIKE CONCAT('%', var_string, '%');
            SELECT count(tagId) FROM search_results INTO var_count_results;
            IF var_count_results <> 0 THEN
                 SELECT * FROM search_results;
            ELSE
                DROP TEMPORARY TABLE IF EXISTS search_results;
                SELECT tagId,tagName FROM tbl_tag WHERE tagSearch LIKE CONCAT('%', var_layout, '%');
            END IF;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура админ поиска тега
DROP PROCEDURE IF EXISTS  tagAdminSearch //
CREATE PROCEDURE `tagAdminSearch`(IN var_string TEXT)
BEGIN
            SELECT * FROM vtbl_tag WHERE tagSearch LIKE CONCAT('%', var_string, '%');
        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура сохранения тега кнопкой
DROP PROCEDURE IF EXISTS  saveTag //
CREATE PROCEDURE `saveTag`(

      	IN Tag_Id INT,

		IN Tag_Name VARCHAR(255),

		IN Tag_Search VARCHAR(255)

		)
BEGIN

            UPDATE tbl_tag SET

            	tagName=Tag_Name,

				tagSearch=Tag_Search

            WHERE tagId=Tag_Id;

        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура получить все теги
DROP PROCEDURE IF EXISTS  getTagsAll //
CREATE PROCEDURE `getTagsAll`(IN var_limit INT)
BEGIN

            SELECT * FROM `vtbl_tag`  ORDER BY `tagId` DESC LIMIT var_limit;

        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура полученния популярных тегов
DROP PROCEDURE IF EXISTS  getPopularTags //
CREATE PROCEDURE `getPopularTags`(IN var_limit INT)
BEGIN

            SELECT `ctbl_tag`.`tagId`, count(*) AS `score`,tagName,tagDesc,tagSearch FROM `ctbl_tag`
            LEFT JOIN `tbl_tag`
            ON `ctbl_tag`.`tagId`=`tbl_tag`.`tagId`
            RIGHT JOIN `tbl_news`
            ON `ctbl_tag`.`newsId`=`tbl_news`.`newsId` AND  `tbl_news`.`newsStatus`=4
            WHERE `tagTime` >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) GROUP BY `tagId` ORDER BY `score` DESC
            LIMIT var_limit;

        END; //


DELIMITER ;
DELIMITER //

-- ---------------------------------------------------------------------------------------------------------- Категории



-- Хранимая процедура удаления категории
DROP PROCEDURE IF EXISTS  categoryDel //
CREATE PROCEDURE `categoryDel`(IN category_Id INT)
BEGIN
        	DELETE FROM tbl_category WHERE categoryId=category_Id;
            UPDATE tbl_news SET categoryId = 0 WHERE categoryId=category_Id;
        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура сохранения категории  кнопкой SAVE
DROP PROCEDURE IF EXISTS saveCategory //
CREATE PROCEDURE `saveCategory`(IN `category_Id` INT, IN `category_Name` VARCHAR(255), IN `category_Translit` VARCHAR(255), IN `category_Desc` TEXT, IN `category_Search` VARCHAR(255))
BEGIN
            UPDATE tbl_category SET
            	categoryName=category_Name,
				categoryTranslit=category_Translit,
				categoryDesc=category_Desc,
				categorySearch=category_Search
            WHERE categoryId=category_Id;
        END; //
        
        
DELIMITER ;
DELIMITER //
        
 -- ---------------------------------------------------------------------------------------------------------- Feeds
        
-- ---------------------------------------------------------------------------------------------------------- Themes
DELIMITER //



-- Хранимая процедура сохранения темы кнопкой
DROP PROCEDURE IF EXISTS  saveThemes //
CREATE PROCEDURE `saveThemes`(

      	IN Themes_Id INT,

		IN Themes_Name VARCHAR(255),

		IN Themes_Desc TEXT,

		IN Themes_Search VARCHAR(255),

		IN Themes_Type INT,

		IN Themes_Sort_Name VARCHAR(255),

		IN Themes_Active INT

		)
BEGIN

            UPDATE tbl_themes SET

            	themesName=Themes_Name,

				themesDesc=Themes_Desc,

				themesSearch=Themes_Search,

				themesType=Themes_Type,

				themesSortName=Themes_Sort_Name,

				themesActive=Themes_Active

            WHERE themesId=Themes_Id;

        END; //
        
        DELIMITER ;
DELIMITER //
        
        
-- Хранимая процедура удаления темы
DROP PROCEDURE IF EXISTS  themesDel //
CREATE PROCEDURE `themesDel`(IN themes_Id INT)
BEGIN
        	DELETE FROM tbl_themes WHERE themesId=themes_Id;
            DELETE FROM ctbl_themes WHERE themesId=themes_Id;
        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура получения темы новости
DROP PROCEDURE IF EXISTS  themesGet //
CREATE PROCEDURE `themesGet`(IN news_Id INT)
BEGIN

        	SELECT

        		tbl_themes.themesId,

        		ctbl_themes.themesSmile,

        		tbl_themes.themesName FROM ctbl_themes

                LEFT JOIN tbl_themes

                    on ctbl_themes.themesId=tbl_themes.themesId

                WHERE ctbl_themes.newsId=news_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура удаления темы новости
DROP PROCEDURE IF EXISTS  themesRemove //
CREATE PROCEDURE `themesRemove`(IN news_Id INT,IN themes_id INT)
BEGIN

        	DELETE FROM ctbl_themes WHERE themesId=themes_id AND newsId=news_Id;

            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура добавления темы новости
DROP PROCEDURE IF EXISTS  themesSave //
CREATE PROCEDURE `themesSave`(IN news_Id INT,IN themes_id INT,IN themes_smile INT)
BEGIN

        	DELETE FROM ctbl_themes WHERE themesId=themes_id AND newsId=news_Id;

        	INSERT INTO ctbl_themes (newsId,themesId,themesSmile) VALUES (news_Id,themes_id,themes_smile);

            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;

            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура поиска темы
DROP PROCEDURE IF EXISTS  themesSearch //
CREATE PROCEDURE `themesSearch`(IN var_string TEXT,IN var_layout1 TEXT,IN var_layout2 TEXT)
BEGIN

            DECLARE var_count_results INT;

            DROP TEMPORARY TABLE IF EXISTS search_results;

            CREATE TEMPORARY TABLE search_results

            SELECT * FROM vtbl_themes WHERE themesSearch LIKE CONCAT('%', var_string, '%') OR themesName LIKE CONCAT('%', var_string, '%');

            SELECT count(themesId) FROM search_results INTO var_count_results;

            IF var_count_results <> 0 THEN

                 SELECT * FROM search_results;

            ELSE

                DROP TEMPORARY TABLE IF EXISTS search_results;

                CREATE TEMPORARY TABLE search_results

                SELECT * FROM vtbl_themes WHERE themesSearch LIKE CONCAT('%', var_layout1, '%') OR themesName LIKE CONCAT('%', var_layout1, '%');

                SELECT count(themesId) FROM search_results INTO var_count_results;
                
                IF var_count_results <> 0 THEN

                SELECT * FROM search_results;

                ELSE

                DROP TEMPORARY TABLE IF EXISTS search_results;
                
                SELECT * FROM vtbl_themes WHERE themesSearch LIKE CONCAT('%', var_layout2, '%') OR themesName LIKE CONCAT('%', var_layout2, '%');
                
                END IF;
                
            END IF;

        END; //

DELIMITER ;
DELIMITER //
-- Хранимая процедура полученния популярных темы
DROP PROCEDURE IF EXISTS  getPopularThemes //
CREATE PROCEDURE `getPopularThemes`(IN var_limit INT)
BEGIN

            SELECT `tbl_themes`.`themesId`, count(*) AS `score`,themesName,themesDesc,themesSearch,themesImg FROM `ctbl_themes`
            LEFT JOIN `tbl_themes`
            ON `ctbl_themes`.`themesId`=`tbl_themes`.`themesId`
            LEFT JOIN `tbl_news`
            ON `ctbl_themes`.`newsId`=`tbl_news`.`newsId` AND  `tbl_news`.`newsStatus`=4
            GROUP BY `themesId` ORDER BY `score` DESC
            LIMIT var_limit;

        END; //

DELIMITER ;
DELIMITER //

-- Хранимая процедура полученния всех темы
DROP PROCEDURE IF EXISTS  getThemesAll //
CREATE PROCEDURE `getThemesAll`(IN var_limit INT)
BEGIN

            SELECT * FROM `vtbl_themes`  ORDER BY `themesId` DESC LIMIT var_limit;

        END; //
DELIMITER ;
DELIMITER //

-- Хранимая процедура получения картинок темы
DROP PROCEDURE IF EXISTS  imgthemesGet //
CREATE PROCEDURE `imgthemesGet`(IN `themes_id` INT(11))
    NO SQL
SELECT imgId, imgName, imgDesc, imgMain FROM ctbl_themesimg WHERE themesId=themes_id;          //

DELIMITER ;
DELIMITER //
-- Хранимая процедура получения картинки темы по айди картинки
DROP PROCEDURE IF EXISTS  imgThemesGetId //
CREATE PROCEDURE `imgThemesGetId`(IN img_Id INT)
BEGIN
        	SELECT imgId, imgName, themesId, imgMain FROM ctbl_themesimg WHERE imgId=img_id;
        END; //
DELIMITER ;
DELIMITER //
-- Хранимая процедура обновления главной картинки темы
DROP PROCEDURE IF EXISTS  imgSetThemesMain //
CREATE PROCEDURE `imgSetThemesMain`(IN img_Id INT, IN themes_Id INT)
BEGIN
        	UPDATE ctbl_themesimg SET imgMain=0 WHERE themesId=themes_id;
            UPDATE ctbl_themesimg SET imgMain=1 WHERE imgId=img_id;
        END; //
DELIMITER ;
DELIMITER //

-- Хранимая процедура удаления картинок темы
DROP PROCEDURE IF EXISTS  imgThemesDel //
CREATE PROCEDURE `imgThemesDel`(IN `img_id` INT(11))
    NO SQL
DELETE FROM ctbl_themesimg WHERE imgId=img_id;          //


DELIMITER ;

-----------------------------------------------------------------------------------------------------------------Themes
 DELIMITER //       
        
-- Хранимая процедура создания rss источника
DROP PROCEDURE IF EXISTS  addNewFeed //
CREATE PROCEDURE `addNewFeed`(IN `feed_link` VARCHAR(255), IN `feed_fields` TEXT)
BEGIN

            INSERT INTO ctbl_feed SET `feedLink`=feed_link, `feedFields`=feed_fields;


        END; //       

DELIMITER ;
DELIMITER //


-- Хранимая процедура автосохранения новости в чорновику(20 секунд)
DROP PROCEDURE IF EXISTS  adminAutoSave //
CREATE PROCEDURE `adminAutoSave`( IN `var_userId` INT, IN `var_newsId` INT, IN `var_newstext` TEXT,IN `var_newsheader` TEXT,IN `var_newssubheader` TEXT,IN `var_time` DATETIME)
BEGIN
            DECLARE var_count_results INT;
            DROP TEMPORARY TABLE IF EXISTS tbl_autosave;
            CREATE TEMPORARY TABLE tbl_autosave
            
            SELECT * FROM `ctbl_autosavenews` WHERE `newsId`=var_newsId;
            SELECT count(newsId) FROM tbl_autosave INTO var_count_results;

            IF var_count_results = 0 THEN 
                INSERT INTO ctbl_autosavenews SET `newsId`=var_newsId, `userId`=var_userId,`newstext`=var_newstext,`newsHeader`=var_newsheader,`newsSubheader`=var_newsSubheader,`autosaveTime`=var_time;  
            ELSE
                SELECT count(ctbl_autosavenews.`newsId`) FROM `ctbl_autosavenews` WHERE `newsId`=var_newsId AND `userId`=var_userId INTO var_count_results;
                IF var_count_results > 0 THEN   
                UPDATE ctbl_autosavenews SET `newstext`=var_newstext,`newsHeader`=var_newsheader,`newsSubheader`=var_newsSubheader,`autosaveTime`=var_time WHERE `newsId`=var_newsId AND `userId`=var_userId; 
                ELSE
                   SELECT count(ctbl_autosavenews.`newsId`) FROM `ctbl_autosavenews` WHERE `newsId`=var_newsId AND `userId`=0 INTO var_count_results; 
                   IF var_count_results > 0 THEN 
                   UPDATE ctbl_autosavenews SET `userId`=var_userId,`newstext`=var_newstext,`newsHeader`=var_newsheader,`newsSubheader`=var_newsSubheader,`autosaveTime`=var_time WHERE `newsId`=var_newsId;
                   ELSE 
                   SELECT count(ctbl_autosavenews.`userId`) FROM `ctbl_autosavenews` WHERE `newsId`=var_newsId;
                   END IF;
                END IF;        
            END IF;        
            DROP TEMPORARY TABLE IF EXISTS tbl_autosave;
            END; //
 

DELIMITER ;

DELIMITER //


-- Хранимая процедура занесения юзерІд и невсІд при заходе на новость
DROP PROCEDURE IF EXISTS  adminUserEditNews //
CREATE PROCEDURE `adminUserEditNews`( IN `var_userId` INT, IN `var_newsId` INT,IN `var_time` DATETIME)
BEGIN
            DECLARE var_count_results INT;
            DROP TEMPORARY TABLE IF EXISTS tbl_usereditnews;
            CREATE TEMPORARY TABLE tbl_usereditnews
            
            SELECT * FROM `ctbl_usereditnews` WHERE `newsId`=var_newsId;
            SELECT count(newsId) FROM tbl_usereditnews INTO var_count_results;

            IF var_count_results = 0 THEN 
                INSERT INTO ctbl_usereditnews SET `newsId`=var_newsId, `userId`=var_userId,`inTime`=var_time;  
            ELSE             
                UPDATE ctbl_usereditnews SET `userId`=var_userId,`inTime`=var_time WHERE `newsId`=var_newsId;     
            END IF;        
            DROP TEMPORARY TABLE IF EXISTS tbl_usereditnews;
            END; //
 

DELIMITER ;

--------------------------------------------------------
DELIMITER //


-- Хранимая процедура удаления тега опроса(виджета) новости
DROP PROCEDURE IF EXISTS  tagWidgetRemove //
CREATE PROCEDURE `tagWidgetRemove`(IN news_Id INT,IN tag_id INT)
BEGIN
        	DELETE FROM ctbl_widgettag WHERE tagId=tag_id AND newsId=news_Id;
            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //

DELIMITER ;
DELIMITER //


-- Хранимая процедура добавления тега опроса(виджета) новости
DROP PROCEDURE IF EXISTS  tagWidgetSave //
CREATE PROCEDURE `tagWidgetSave`(IN news_Id INT,IN tag_id INT, IN tag_name TEXT)
BEGIN
        	DELETE FROM ctbl_widgettag WHERE  newsId=news_Id;
        	INSERT INTO ctbl_widgettag (newsId,tagId,tagName) VALUES (news_Id,tag_id,tag_name);
            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;
            SELECT newsTimeUpdate AS saved FROM vtbl_newsListAdmin WHERE newsId=news_Id;
        END; //


DELIMITER ;

DELIMITER //


-- Хранимая процедура получения тегов новости
DROP PROCEDURE IF EXISTS  tagWidgetGet //
CREATE PROCEDURE `tagWidgetGet`(IN news_Id INT)
BEGIN
        	SELECT
        		ctbl_widgettag.tagId,
        		ctbl_widgettag.tagName FROM ctbl_widgettag
                WHERE ctbl_widgettag.newsId=news_Id;
        END; //

DELIMITER ;

DELIMITER //


-- Хранимая процедура добавления редактора в бд(ctbl_redactorinnews), если он зашол на конкретную новость
DROP PROCEDURE IF EXISTS  redactorInNews //
CREATE PROCEDURE `redactorInNews`(IN user_id INT,IN news_id INT, IN news_name TEXT,IN news_Time DATETIME)
BEGIN           
                DELETE FROM ctbl_redactorinnews WHERE  newsId=news_id AND userId=user_id;
        	INSERT INTO ctbl_redactorinnews (userId,newsId,newsName,goTime,updateTime) VALUES (user_id,news_id,news_name,news_Time,news_Time);
        END; //


DELIMITER ;

DELIMITER //

-- Хранимая процедура удаления редактора с бд(ctbl_redactorinnews), если он зашол с новости
DROP PROCEDURE IF EXISTS  delRedactorInNews //
CREATE PROCEDURE `delRedactorInNews`(IN user_id INT,IN news_id INT)
BEGIN           
                DELETE FROM ctbl_redactorinnews WHERE  newsId=news_id AND userId=user_id;
        END; //


DELIMITER ;

DELIMITER //

-- Хранимая процедура удаления привязаного бренда
DROP PROCEDURE IF EXISTS  brandRemoveBrand //
CREATE PROCEDURE `brandRemoveBrand`(IN brand_Id INT,IN connect_id INT)
BEGIN

        	DELETE FROM ctbl_brandtobrand WHERE brandConnect=connect_id AND brandId=brand_Id;
                DELETE FROM ctbl_brandtobrand WHERE brandConnect=brand_Id AND brandId=connect_id;
                UPDATE tbl_brand SET brandTimeUpdate=now() WHERE brandId=brand_Id;
            SELECT brandTimeUpdate AS saved FROM vtbl_brand WHERE brandId=brand_Id;

        END; //


DELIMITER ;
DELIMITER //

-- Хранимая процедура добавления привязаного бренда
DROP PROCEDURE IF EXISTS  brandSaveBrand //
CREATE PROCEDURE `brandSaveBrand`(IN brand_id INT,IN connect_id INT,IN brand_smile INT)
BEGIN

        	DELETE FROM ctbl_brandtobrand WHERE brandConnect=connect_id AND brandId=brand_id;
        	INSERT INTO ctbl_brandtobrand (brandId,brandConnect,brandSmile) VALUES (brand_id,connect_id,brand_smile);
                
                DELETE FROM ctbl_brandtobrand WHERE brandConnect=brand_id AND brandId=connect_id;
                INSERT INTO ctbl_brandtobrand (brandId,brandConnect,brandSmile) VALUES (connect_id,brand_id,brand_smile);

             UPDATE tbl_brand SET brandTimeUpdate=now() WHERE brandId=brand_Id;

            SELECT brandTimeUpdate AS saved FROM vtbl_brand WHERE brandId=brand_Id;

        END; //


DELIMITER ;

DELIMITER //

-- Хранимая процедура получения брендов новости
DROP PROCEDURE IF EXISTS  brandGetBrand //
CREATE PROCEDURE `brandGetBrand`(IN brand_Id INT)
BEGIN

        	SELECT

        		tbl_brand.brandId,

        		ctbl_brandtobrand.brandSmile,

                        ctbl_brandtobrand.connectId,

        		tbl_brand.brandName FROM ctbl_brandtobrand

                LEFT JOIN tbl_brand

                    on ctbl_brandtobrand.brandConnect=tbl_brand.brandId

                WHERE ctbl_brandtobrand.brandId=brand_Id;

        END; //


DELIMITER ;