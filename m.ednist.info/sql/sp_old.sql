-- Хранимая процедура получения поискового текста для полнотекстового поиска                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  getSearchText //



    CREATE PROCEDURE getSearchText



    (IN var_newsId INT)



    BEGIN



SELECT



                vtbl_news.newsId,



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



FROM vtbl_news



                LEFT JOIN ctbl_img



                    on vtbl_news.newsId=ctbl_img.newsId



                LEFT JOIN ctbl_tag



                    on vtbl_news.newsId=ctbl_tag.newsId



                LEFT JOIN tbl_tag



                    on ctbl_tag.tagId=tbl_tag.tagId



                LEFT JOIN ctbl_brand



                    on vtbl_news.newsId=ctbl_brand.newsId



                LEFT JOIN tbl_brand



                    on ctbl_brand.brandId=tbl_brand.brandId



                LEFT JOIN ctbl_region



                    on vtbl_news.newsId=ctbl_region.newsId



                LEFT JOIN tbl_region



                    on ctbl_region.regionId=tbl_region.regionId



                    WHERE vtbl_news.newsId=var_newsId;



    END;



//



DELIMITER ;















-- Хранимая процедура установки "Главная новость"                                                                                    -- ДОБАВЛЕНО



DELIMITER //



    DROP PROCEDURE IF EXISTS  setNewsMain //



    CREATE PROCEDURE setNewsMain(



        IN news_Id INT,



        IN news_main TINYINT)



        BEGIN



            UPDATE tbl_news SET newsMain=news_main WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура установки "Добавить новость в галереи"                                                                                    -- ДОБАВЛЕНО



DELIMITER //



    DROP PROCEDURE IF EXISTS  setNewsIsGallery //



    CREATE PROCEDURE setNewsIsGallery(



        IN news_Id INT,



        IN news_isGallery TINYINT)



        BEGIN



            UPDATE tbl_news SET newsIsGallery=news_isGallery WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура установки "Добавить новость в видео"                                                                                    -- ДОБАВЛЕНО



DELIMITER //



    DROP PROCEDURE IF EXISTS  setNewsIsVideo //



    CREATE PROCEDURE setNewsIsVideo(



        IN news_Id INT,



        IN news_isVideo TINYINT)



        BEGIN



            UPDATE tbl_news SET newsIsVideo=news_isVideo WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура установки категории                                                                                    -- ДОБАВЛЕНО



DELIMITER //



    DROP PROCEDURE IF EXISTS  setNewsCategory //



    CREATE PROCEDURE setNewsCategory(



        IN news_Id INT,



        IN news_category INT)



        BEGIN



            UPDATE tbl_news SET categoryId=news_category WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура установки типа новости                                                                                    -- ДОБАВЛЕНО



DELIMITER //



    DROP PROCEDURE IF EXISTS  setNewsType //



    CREATE PROCEDURE setNewsType(



        IN news_Id INT,



        IN news_type INT)



        BEGIN



            UPDATE tbl_news SET newsType=news_type WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура установки статуса новости                                                                                    -- ДОБАВЛЕНО



DELIMITER //



    DROP PROCEDURE IF EXISTS  setNewsStatus //



    CREATE PROCEDURE setNewsStatus(



        IN news_Id INT,



        IN news_status INT)



        BEGIN



            UPDATE tbl_news SET newsStatus=news_status WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура установки "Добавить публикацию в соц. сеть"

--  ДОБАВЛЕНО



DELIMITER //



    DROP PROCEDURE IF EXISTS  setNewsPostToSoc //



    CREATE PROCEDURE setNewsPostToSoc(



        IN news_Id INT,



        IN news_setNewsPostToSoc INT,



        IN news_socName VARCHAR(255))



        BEGIN



            SET @t1 = CONCAT('UPDATE tbl_news SET ', news_socName, '=',news_setNewsPostToSoc,' WHERE `newsId`=',news_Id);

            PREPARE stmt3 FROM @t1;

            EXECUTE stmt3;

            DEALLOCATE PREPARE stmt3;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;





-- Хранимая процедура установки "Добавить время публикации в соц. сеть"

--  ДОБАВЛЕНО



DELIMITER //



    DROP PROCEDURE IF EXISTS  setNewsSocTime //



    CREATE PROCEDURE setNewsSocTime(



        IN news_Id INT,



        IN news_SocTime DATETIME)



        BEGIN



            UPDATE tbl_news SET newsSocTime=STR_TO_DATE(news_SocTime, '%H:%i %d.%m.%Y') WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;











-- Хранимая процедура создания rss источника                                                                -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  addNewFeed //



    CREATE PROCEDURE addNewFeed(



        IN feed_link VARCHAR(255))



        BEGIN



            INSERT INTO tbl_feed SET `link`=feed_link;





        END;



//



DELIMITER ;









-- Хранимая процедура создания новости                                                                -- Добавлена





DELIMITER //



    DROP PROCEDURE IF EXISTS  insertNew //



    CREATE PROCEDURE insertNew()



        BEGIN



            INSERT INTO tbl_news SET `newsTime`=now(), `newsTimePublic`=now(), newsStatus=2;



            SELECT `newsTime` AS saved, newsId AS id FROM tbl_news ORDER BY newsId DESC LIMIT 1;



        END;



//



DELIMITER ;







-- Хранимая процедура сохранения новости главной кнопкой SAVE                                                                -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  saveAll //



    CREATE PROCEDURE saveAll(



      	IN news_Id INT,



		IN news_Header MEDIUMTEXT,



		IN news_Subheader MEDIUMTEXT,



		IN news_Text LONGTEXT,



		IN news_SeoDesc VARCHAR(255),



		IN news_SeoKeywords VARCHAR(255),



		IN news_SeoTitle VARCHAR(255),



		IN news_Url VARCHAR(255),



		IN news_Video VARCHAR(255),



		IN news_VideoDesc TEXT,



		IN news_Search LONGTEXT,



		IN category_Id INT,



        IN news_Time_Public DATETIME,



        IN news_Status INT,



        IN news_Soc_Text TEXT



		)



        BEGIN



            UPDATE tbl_news SET



            	newsHeader=news_Header,



				newsSubheader=news_Subheader,



                newsStatus=news_Status,



				newsText=news_Text,



				newsSeoDesc=news_SeoDesc,



				newsSeoKeywords=news_SeoKeywords,



				newsSeoTitle=news_SeoTitle,



				newsUrl=news_Url,



				newsVideo=news_Video,



				newsVideoDesc=news_VideoDesc,



				categoryId=category_Id,



                newsSocText=news_Soc_Text,



                newsTimePublic=news_Time_Public



            WHERE newsId=news_Id;



            DELETE FROM tbl_search WHERE newsId=news_Id;



            INSERT INTO tbl_search (newsId,newsSearch) VALUES (news_Id,news_Search);



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура сохранения категории  кнопкой SAVE                                                                -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  saveCategory //



    CREATE PROCEDURE saveCategory(



      	IN category_Id INT,



		IN category_Name VARCHAR(255),



		IN category_Translit VARCHAR(255),



		IN category_Desc TEXT,



		IN category_Search VARCHAR(255)



		)



        BEGIN



            UPDATE tbl_category SET



            	categoryName=category_Name,



				categoryTranslit=category_Translit,



				categoryDesc=category_Desc,



				categorySearch=category_Search



            WHERE categoryId=category_Id;



        END;



//



DELIMITER ;





-- Хранимая процедура сохранения бренда кнопкой                                                                 -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  saveBrand //



    CREATE PROCEDURE saveBrand(



      	IN Brand_Id INT,



		IN Brand_Name VARCHAR(255),



		IN Brand_Desc TEXT,



		IN Brand_Search VARCHAR(255)



		)



        BEGIN



            UPDATE tbl_brand SET



            	brandName=Brand_Name,



				brandDesc=Brand_Desc,



				brandSearch=Brand_Search



            WHERE brandId=Brand_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура сохранения региона кнопкой                                                                 -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  saveRegion //



    CREATE PROCEDURE saveRegion(



      	IN Region_Id INT,



		IN Region_Name VARCHAR(255),



		IN Region_Search VARCHAR(255)



		)



        BEGIN



            UPDATE tbl_region SET



            	regionName=Region_Name,



				regionSearch=Region_Search



            WHERE regionId=Region_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура сохранения тега кнопкой                                                                 -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  saveTag //



    CREATE PROCEDURE saveTag(



      	IN Tag_Id INT,



		IN Tag_Name VARCHAR(255),



		IN Tag_Search VARCHAR(255)



		)



        BEGIN



            UPDATE tbl_tag SET



            	tagName=Tag_Name,



				tagSearch=Tag_Search



            WHERE tagId=Tag_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура сохранения автора кнопкой                                                                 -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  saveAuthor //



    CREATE PROCEDURE saveAuthor(



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



        END;



//



DELIMITER ;





-- Хранимая процедура поиска тега                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  tagSearch //



    CREATE PROCEDURE tagSearch (IN var_string TEXT,IN var_layout TEXT)



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



        END;



//



DELIMITER ;







-- Хранимая процедура добавления тега новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  tagSave //



    CREATE PROCEDURE tagSave (IN news_Id INT,IN tag_id INT)



        BEGIN



        	DELETE FROM ctbl_tag WHERE tagId=tag_id AND newsId=news_Id;



        	INSERT INTO ctbl_tag (newsId,tagId) VALUES (news_Id,tag_id);



            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура удаления тега новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  tagRemove //



    CREATE PROCEDURE tagRemove (IN news_Id INT,IN tag_id INT)



        BEGIN



        	DELETE FROM ctbl_tag WHERE tagId=tag_id AND newsId=news_Id;



            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура получения тегов новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  tagGet //



    CREATE PROCEDURE tagGet (IN news_Id INT)



        BEGIN



        	SELECT



        		tbl_tag.tagId,



        		tbl_tag.tagName FROM ctbl_tag



                LEFT JOIN tbl_tag



                    on ctbl_tag.tagId=tbl_tag.tagId



                WHERE ctbl_tag.newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура сохранения нового тега                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  tagSaveNew //



    CREATE PROCEDURE tagSaveNew (IN tag_name VARCHAR(255), IN tag_search VARCHAR(255))



        BEGIN



        	DECLARE var_count_results INT;



        	SELECT count(tagId) FROM tbl_tag WHERE tagName=tag_name INTO var_count_results;



            IF var_count_results <> 0 THEN



                 SELECT 1 AS 'exists';



            ELSE



        		INSERT INTO tbl_tag (tagName,tagSearch) VALUES (tag_name,tag_search);



				SELECT LAST_INSERT_ID() AS saved;



			END IF;



        END;



//



DELIMITER ;















-- ----------------------------------------------------------------------------------------------------------Регионы







-- Хранимая процедура поиска региона                                                              -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  regionSearch //



    CREATE PROCEDURE regionSearch (IN var_string TEXT,IN var_layout TEXT)



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



        END;



//



DELIMITER ;







-- Хранимая процедура добавления региона новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  regionSave //



    CREATE PROCEDURE regionSave (IN news_Id INT,IN region_id INT)



        BEGIN



        	DELETE FROM ctbl_region WHERE regionId=region_id AND newsId=news_Id;



        	INSERT INTO ctbl_region (newsId,regionId) VALUES (news_Id,region_id);



            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура удаления региона новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  regionRemove //



    CREATE PROCEDURE regionRemove (IN news_Id INT,IN region_id INT)



        BEGIN



        	DELETE FROM ctbl_region WHERE regionId=region_id AND newsId=news_Id;



            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура получения регионов новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  regionGet //



    CREATE PROCEDURE regionGet (IN news_Id INT)



        BEGIN



        	SELECT



        		tbl_region.regionId,



        		tbl_region.regionName FROM ctbl_region



                LEFT JOIN tbl_region



                    on ctbl_region.regionId=tbl_region.regionId



                WHERE ctbl_region.newsId=news_Id;



        END;



//



DELIMITER ;















-- Хранимая процедура сохранения нового региона                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  regionSaveNew //



    CREATE PROCEDURE regionSaveNew (IN region_name VARCHAR(255), IN region_search VARCHAR(255))



        BEGIN



        	DECLARE var_count_results INT;



        	SELECT count(regionId) FROM tbl_region WHERE regionName=region_name INTO var_count_results;



            IF var_count_results <> 0 THEN



                 SELECT 1 AS 'exists';



            ELSE



        		INSERT INTO tbl_region (regionName,regionSearch) VALUES (region_name,region_search);



				SELECT LAST_INSERT_ID() AS saved;



			END IF;



        END;



//



DELIMITER ;



-- ========================================================================================================Конец регионов



-- ----------------------------------------------------------------------------------------------------------Бренды

-- Хранимая процедура поиска брендов                                                              -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  brandSearch //



    CREATE PROCEDURE brandSearch (IN var_string TEXT,IN var_layout TEXT)



        BEGIN



            DECLARE var_count_results INT;



            DROP TEMPORARY TABLE IF EXISTS search_results;



            CREATE TEMPORARY TABLE search_results



            SELECT brandId,brandName FROM tbl_brand WHERE brandSearch LIKE CONCAT('%', var_string, '%');



            SELECT count(brandId) FROM search_results INTO var_count_results;



            IF var_count_results <> 0 THEN



                 SELECT * FROM search_results;



            ELSE



                DROP TEMPORARY TABLE IF EXISTS search_results;



                SELECT brandId,brandName FROM tbl_brand WHERE brandSearch LIKE CONCAT('%', var_layout, '%');



            END IF;



        END;



//



DELIMITER ;







-- Хранимая процедура добавления бренда новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  brandSave //



    CREATE PROCEDURE brandSave (IN news_Id INT,IN brand_id INT,IN brand_smile INT)



        BEGIN



        	DELETE FROM ctbl_brand WHERE brandId=brand_id AND newsId=news_Id;



        	INSERT INTO ctbl_brand (newsId,brandId,brandSmile) VALUES (news_Id,brand_id,brand_smile);



            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура удаления бренда новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  brandRemove //



    CREATE PROCEDURE brandRemove (IN news_Id INT,IN brand_id INT)



        BEGIN



        	DELETE FROM ctbl_brand WHERE brandId=brand_id AND newsId=news_Id;



            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура получения брендов новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  brandGet //



    CREATE PROCEDURE brandGet (IN news_Id INT)



        BEGIN



        	SELECT



        		tbl_brand.brandId,



        		ctbl_brand.brandSmile,



        		tbl_brand.brandName FROM ctbl_brand



                LEFT JOIN tbl_brand



                    on ctbl_brand.brandId=tbl_brand.brandId



                WHERE ctbl_brand.newsId=news_Id;



        END;



//



DELIMITER ;

-- ========================================================================================================Конец брендов



-- ----------------------------------------------------------------------------------------------------------Источники







-- Хранимая процедура поиска региона                                                              -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  sourceSearch //



    CREATE PROCEDURE sourceSearch (IN var_string TEXT,IN var_layout TEXT)



        BEGIN



            DECLARE var_count_results INT;



            DROP TEMPORARY TABLE IF EXISTS search_results;



            CREATE TEMPORARY TABLE search_results



            SELECT sourceId,sourceName FROM tbl_source WHERE sourceSearch LIKE CONCAT('%', var_string, '%');



            SELECT count(sourceId) FROM search_results INTO var_count_results;



            IF var_count_results <> 0 THEN



                 SELECT * FROM search_results;



            ELSE



                DROP TEMPORARY TABLE IF EXISTS search_results;



                SELECT sourceId,sourceName FROM tbl_source WHERE sourceSearch LIKE CONCAT('%', var_layout, '%');



            END IF;



        END;



//



DELIMITER ;







-- Хранимая процедура добавления региона новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  sourceSave //



    CREATE PROCEDURE sourceSave (IN news_Id INT,IN source_id INT)



        BEGIN



         	UPDATE tbl_news SET sourceId=source_id, newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура удаления региона новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  sourceRemove //



    CREATE PROCEDURE sourceRemove (IN news_Id INT,IN region_id INT)



        BEGIN



        	UPDATE tbl_news SET sourceId=0, newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура получения источника новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  sourceGet //



    CREATE PROCEDURE sourceGet (IN news_Id INT)



        BEGIN



        	SELECT



        		tbl_source.sourceId,



        		tbl_source.sourceName,



        		tbl_source.sourceLink FROM tbl_news



                LEFT JOIN tbl_source



                    on tbl_news.sourceId=tbl_source.sourceId



                WHERE tbl_news.newsId=news_Id;



        END;



//



DELIMITER ;







-- ========================================================================================================Конец источников







-- ----------------------------------------------------------------------------------------------------------Автор







-- Хранимая процедура поиска автора                                                              -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  authorSearch //



    CREATE PROCEDURE authorSearch (IN var_string TEXT,IN var_layout TEXT)



        BEGIN



            DECLARE var_count_results INT;



            DROP TEMPORARY TABLE IF EXISTS search_results;



            CREATE TEMPORARY TABLE search_results



            SELECT authorId,authorName FROM tbl_author WHERE authorSearch LIKE CONCAT('%', var_string, '%') || authorName LIKE CONCAT('%', var_string, '%');



            SELECT count(authorId) FROM search_results INTO var_count_results;



            IF var_count_results <> 0 THEN



                 SELECT * FROM search_results;



            ELSE



                DROP TEMPORARY TABLE IF EXISTS search_results;



                SELECT authorId,authorName FROM tbl_author WHERE authorSearch LIKE CONCAT('%', var_layout, '%') || authorName LIKE CONCAT('%', var_layout, '%');



            END IF;



        END;



//



DELIMITER ;







-- Хранимая процедура добавления автора новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  authorSave //



    CREATE PROCEDURE authorSave (IN news_Id INT,IN author_id INT)



        BEGIN



         	UPDATE tbl_news SET authorId=author_id, newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура удаления автора новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  authorRemove //



    CREATE PROCEDURE authorRemove (IN news_Id INT,IN autor_id INT)



        BEGIN



        	UPDATE tbl_news SET authorId=0, newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;



-- Хранимая процедура получения автора новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  authorGet //



    CREATE PROCEDURE authorGet (IN news_Id INT)



        BEGIN



        	SELECT



        		tbl_author.authorId,



        		tbl_author.authorName FROM tbl_news



                LEFT JOIN tbl_author



                    on tbl_news.authorId=tbl_author.authorId



                WHERE tbl_news.newsId=news_Id;



        END;



//



DELIMITER ;







-- ========================================================================================================Конец автора







-- ----------------------------------------------------------------------------------------------------------Связь









-- Хранимая процедура добавления связи новости                                                              -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  connectSave //



    CREATE PROCEDURE connectSave (IN news_Id INT,IN news_connect INT)



        BEGIN



        	DELETE FROM ctbl_connect WHERE newsConnect=news_connect AND newsId=news_Id;



        	INSERT INTO ctbl_connect (newsId,newsConnect) VALUES (news_Id,news_connect);



            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура удаления связи новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  connectRemove //



    CREATE PROCEDURE connectRemove (IN news_Id INT,IN news_connect INT)



        BEGIN



        	DELETE FROM ctbl_connect WHERE newsConnect=news_connect AND newsId=news_Id;



            UPDATE tbl_news SET newsTimeUpdate=now() WHERE newsId=news_Id;



            SELECT newsTimeUpdate AS saved FROM vtbl_news WHERE newsId=news_Id;



        END;



//



DELIMITER ;







-- Хранимая процедура получения связанных новостей                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  connectGet //



    CREATE PROCEDURE connectGet (IN news_Id INT)



        BEGIN



        	SELECT



            	vtbl_news.newsId,



            	vtbl_news.newsHeader,



            	vtbl_news.newsSubheader FROM ctbl_connect



                LEFT JOIN vtbl_news



                    on ctbl_connect.newsConnect=vtbl_news.newsId



                WHERE ctbl_connect.newsId=news_Id;



        END;



//



DELIMITER ;











-- ========================================================================================================Конец связи



















-- Хранимая процедура полнотекстового поиска администратора                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS fulltextAdmin //



    CREATE PROCEDURE fulltextAdmin (IN var_search TEXT,IN var_layout TEXT)



        BEGIN



            DECLARE var_max_score FLOAT;



            DECLARE var_count_results INT;



            DROP TEMPORARY TABLE IF EXISTS search_results;



            CREATE TEMPORARY TABLE search_results



            SELECT



            	vtbl_news.newsId,



            	vtbl_news.newsHeader,



            	vtbl_news.newsSubheader,



            	vtbl_news.newsText,



            	vtbl_news.newsStatus,



            	vtbl_news.newsType,



            	vtbl_news.newsMain,



            	vtbl_news.newsIsGallery,



            	vtbl_news.newsIsVideo,



            	vtbl_news.newsImg,



            	vtbl_news.newsVideo,



            	vtbl_news.newsVideoDesc,



            	vtbl_news.newsUrl,



            	vtbl_news.imgNames,



            	vtbl_news.imgDescs,



            	vtbl_news.tagIds,



            	vtbl_news.brandIds,



            	vtbl_news.connectIds,



            	vtbl_news.regionIds,



            	vtbl_news.categoryId,



            	vtbl_news.categoryName,



            	vtbl_news.sourceId,



            	vtbl_news.sourceName,



            	vtbl_news.sourceLink,



            	vtbl_news.authorId,



            	vtbl_news.authorName,



            	vtbl_news.newsSeoTitle,



            	vtbl_news.newsSeoDesc,



            	vtbl_news.newsSeoKeywords,



            	vtbl_news.newsSearch,



            	vtbl_news.newsTimeUpdate,



            	vtbl_news.newsTimePublic,



            	vtbl_news.newsTime,



            MATCH (tbl_search.newsSearch) AGAINST (var_search IN NATURAL LANGUAGE MODE) AS score



                FROM vtbl_news



                LEFT JOIN tbl_search



                	ON tbl_search.newsId= vtbl_news.newsId



                WHERE MATCH (tbl_search.newsSearch)



                AGAINST (var_search IN BOOLEAN MODE);



            SELECT count(newsId) FROM search_results INTO var_count_results;



            IF var_count_results <> 0 THEN



                SELECT max(score) from search_results INTO var_max_score;



                IF var_max_score > 0 THEN



                 SELECT * FROM search_results ORDER BY score DESC;



                ELSE



                 SELECT * FROM search_results ORDER BY newsId DESC;



                END IF;



            ELSE



	                DROP TEMPORARY TABLE IF EXISTS search_results;



	                CREATE TEMPORARY TABLE search_results



	            SELECT



	            	vtbl_news.newsId,



	            	vtbl_news.newsHeader,



	            	vtbl_news.newsSubheader,



	            	vtbl_news.newsText,



	            	vtbl_news.newsStatus,



	            	vtbl_news.newsType,



	            	vtbl_news.newsMain,



	            	vtbl_news.newsIsGallery,



	            	vtbl_news.newsIsVideo,



	            	vtbl_news.newsImg,



	            	vtbl_news.newsVideo,



	            	vtbl_news.newsVideoDesc,



	            	vtbl_news.newsUrl,



	            	vtbl_news.imgNames,



	            	vtbl_news.imgDescs,



	            	vtbl_news.tagIds,



	            	vtbl_news.brandIds,



	            	vtbl_news.connectIds,



	            	vtbl_news.regionIds,



	            	vtbl_news.categoryId,



	            	vtbl_news.categoryName,



	            	vtbl_news.sourceId,



	            	vtbl_news.sourceName,



	            	vtbl_news.sourceLink,



	            	vtbl_news.authorId,



	            	vtbl_news.authorName,



	            	vtbl_news.newsSeoTitle,



	            	vtbl_news.newsSeoDesc,



	            	vtbl_news.newsSeoKeywords,



	            	vtbl_news.newsSearch,



	            	vtbl_news.newsTimeUpdate,



	            	vtbl_news.newsTimePublic,



	            	vtbl_news.newsTime,



	            MATCH (tbl_search.newsSearch) AGAINST (var_layout IN NATURAL LANGUAGE MODE) AS score



	                FROM vtbl_news



	                LEFT JOIN tbl_search



	                	ON tbl_search.newsId= vtbl_news.newsId



	                WHERE MATCH (tbl_search.newsSearch)



	                AGAINST (var_layout IN BOOLEAN MODE);



	            SELECT count(newsId) FROM search_results INTO var_count_results;



	            SELECT max(score) from search_results INTO var_max_score;



	            IF var_max_score > 0 THEN



	            	SELECT * FROM search_results ORDER BY score DESC;



	            ELSE



	                SELECT * FROM search_results ORDER BY newsId DESC;



	            END IF;



            END IF;



            DROP TEMPORARY TABLE IF EXISTS search_results;



        END;



//



DELIMITER ;











-- Хранимая процедура получения картинок новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  imgGet //



    CREATE PROCEDURE imgGet (IN news_Id INT)



        BEGIN



        	SELECT imgId, imgName, imgDesc, imgMain FROM ctbl_img WHERE newsId=news_id;



        END;



//



DELIMITER ;











-- Хранимая процедура получения картинки новости по айди картинки                                                           -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  imgGetId //



    CREATE PROCEDURE imgGetId (IN img_id INT)



        BEGIN



        	SELECT imgId, imgName, newsId, imgMain FROM ctbl_img WHERE imgId=img_id;



        END;



//



DELIMITER ;





-- Хранимая процедура получения главной картинки новости                                                          -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  imgGetMain //



    CREATE PROCEDURE imgGetMain (IN news_id INT)



        BEGIN



        	SELECT imgId, imgName, imgDesc  FROM ctbl_img WHERE newsId=news_id AND imgMain=1;



        END;



//



DELIMITER ;









-- Хранимая процедура удаления картинок новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  imgDel //



    CREATE PROCEDURE imgDel (IN img_id INT)



        BEGIN



        	DELETE FROM ctbl_img WHERE imgId=img_id;



        END;



//



DELIMITER ;









-- Хранимая процедура обновления описания картинок новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  imgSetDesc //



    CREATE PROCEDURE imgSetDesc (IN img_id INT, IN img_Desc TEXT)



        BEGIN



        	UPDATE ctbl_img SET imgDesc=img_desc WHERE imgId=img_id;



        END;



//



DELIMITER ;







-- Хранимая процедура обновления главной картинки новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  imgSetMain //



    CREATE PROCEDURE imgSetMain (IN img_id INT, IN news_id INT)



        BEGIN



        	UPDATE ctbl_img SET imgMain=0 WHERE newsId=news_id;



            UPDATE ctbl_img SET imgMain=1 WHERE imgId=img_id;



        END;



//



DELIMITER ;



-- Хранимая процедура удаления новости                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  newsDel //



    CREATE PROCEDURE newsDel (IN news_Id INT)



        BEGIN

        	DELETE FROM tbl_news WHERE newsId=news_Id;

            DELETE FROM tbl_search WHERE newsId=news_Id;

            DELETE FROM ctbl_tag WHERE newsId=news_Id;

            DELETE FROM ctbl_region WHERE newsId=news_Id;

            DELETE FROM ctbl_brand WHERE newsId=news_Id;

            DELETE FROM ctbl_connect WHERE newsId=news_Id;

            DELETE FROM ctbl_img WHERE newsId=news_Id;

            SELECT newsId FROM vtbl_news WHERE newsId=news_Id;

        END;



//



DELIMITER ;



-- Хранимая процедура удаления бренда                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  brandDel //



    CREATE PROCEDURE brandDel (IN brand_Id INT)



        BEGIN

        	DELETE FROM tbl_brand WHERE brandId=brand_Id;

            DELETE FROM ctbl_brand WHERE brandId=brand_Id;

        END;



//



DELIMITER ;



-- Хранимая процедура удаления тега                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  tagDel //



    CREATE PROCEDURE tagDel (IN tag_Id INT)



        BEGIN

        	DELETE FROM tbl_tag WHERE tagId=tag_Id;

            DELETE FROM ctbl_tag WHERE tagId=tag_Id;

        END;



//



DELIMITER ;



-- Хранимая процедура удаления региона                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  regionDel //



    CREATE PROCEDURE regionDel (IN region_Id INT)



        BEGIN

        	DELETE FROM tbl_region WHERE regionId=region_Id;

            DELETE FROM ctbl_region WHERE regionId=region_Id;

        END;



//



DELIMITER ;



-- Хранимая процедура удаления бренда                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  authorDel //



    CREATE PROCEDURE authorDel (IN author_Id INT)



        BEGIN

        	DELETE FROM tbl_author WHERE authorId=author_Id;

            UPDATE tbl_news SET authorId = 0 WHERE authorId=author_Id;

        END;



//



DELIMITER ;



-- Хранимая процедура удаления бренда                                                               -- Добавлена



DELIMITER //



    DROP PROCEDURE IF EXISTS  categoryDel //



    CREATE PROCEDURE categoryDel (IN category_Id INT)



        BEGIN

        	DELETE FROM tbl_category WHERE categoryId=category_Id;

            UPDATE tbl_news SET categoryId = 0 WHERE categoryId=category_Id;

        END;



//
-- Хранимая процедура поиска брендов
CREATE PROCEDURE `brandSearch`(IN var_string TEXT,IN var_layout TEXT)
BEGIN

            DECLARE var_count_results INT;

            DROP TEMPORARY TABLE IF EXISTS search_results;

            CREATE TEMPORARY TABLE search_results

            SELECT brandId,brandName FROM tbl_brand WHERE brandSearch LIKE CONCAT('%', var_string, '%');

            SELECT count(brandId) FROM search_results INTO var_count_results;

            IF var_count_results <> 0 THEN

                 SELECT * FROM search_results;

            ELSE

                DROP TEMPORARY TABLE IF EXISTS search_results;

                SELECT brandId,brandName FROM tbl_brand WHERE brandSearch LIKE CONCAT('%', var_layout, '%');

            END IF;

        END$$

-- Хранимая процедура полнотекстового поиска пользователя
CREATE PROCEDURE `fulltextClient`(IN var_search TEXT,IN var_layout TEXT)
BEGIN

            DECLARE var_max_score FLOAT;

            DECLARE var_count_results INT;

            DROP TEMPORARY TABLE IF EXISTS search_results;

            CREATE TEMPORARY TABLE search_results

            SELECT

            	vtbl_news.newsId,

            	vtbl_news.newsHeader,

            	vtbl_news.newsSubheader,

            	vtbl_news.newsText,

            	vtbl_news.newsStatus,

            	vtbl_news.newsType,

            	vtbl_news.newsMain,

            	vtbl_news.newsIsGallery,

            	vtbl_news.newsIsVideo,

            	vtbl_news.newsImg,

(select imgName from ctbl_img where vtbl_news.newsId = ctbl_img.newsId AND ctbl_img.imgMain=1)as imgMain,
(select imgDesc from ctbl_img where vtbl_news.newsId = ctbl_img.newsId AND ctbl_img.imgMain=1)as imgMainDesc,
            	vtbl_news.newsVideo,

            	vtbl_news.newsVideoDesc,

            	vtbl_news.newsUrl,

            	vtbl_news.imgNames,

            	vtbl_news.imgDescs,

            	vtbl_news.tagIds,

            	vtbl_news.brandIds,

            	vtbl_news.connectIds,

            	vtbl_news.regionIds,

            	vtbl_news.categoryId,

            	vtbl_news.categoryName,

            	vtbl_news.sourceId,

            	vtbl_news.sourceName,

            	vtbl_news.sourceLink,

            	vtbl_news.authorId,

            	vtbl_news.authorName,

            	vtbl_news.newsSeoTitle,

            	vtbl_news.newsSeoDesc,

            	vtbl_news.newsSeoKeywords,

            	vtbl_news.newsSearch,

            	vtbl_news.newsTimeUpdate,

            	vtbl_news.newsTimePublic,

            	vtbl_news.newsTime,

            MATCH (tbl_search.newsSearch) AGAINST (var_search IN NATURAL LANGUAGE MODE) AS score

                FROM vtbl_news

                LEFT JOIN tbl_search

                	ON tbl_search.newsId= vtbl_news.newsId AND vtbl_news.newsStatus='4'

                WHERE MATCH (tbl_search.newsSearch)

                AGAINST (var_search IN BOOLEAN MODE);

            SELECT count(newsId) FROM search_results INTO var_count_results;

            IF var_count_results <> 0 THEN

                SELECT max(score) from search_results INTO var_max_score;

                IF var_max_score > 0 THEN

                 SELECT * FROM search_results ORDER BY score DESC;

                ELSE

                 SELECT * FROM search_results ORDER BY newsId DESC;

                END IF;

            ELSE

	                DROP TEMPORARY TABLE IF EXISTS search_results;

	                CREATE TEMPORARY TABLE search_results

	            SELECT

	            	vtbl_news.newsId,

	            	vtbl_news.newsHeader,

	            	vtbl_news.newsSubheader,

	            	vtbl_news.newsText,

	            	vtbl_news.newsStatus,

	            	vtbl_news.newsType,

	            	vtbl_news.newsMain,

	            	vtbl_news.newsIsGallery,

	            	vtbl_news.newsIsVideo,

	            	vtbl_news.newsImg,
(select imgName from ctbl_img where vtbl_news.newsId = ctbl_img.newsId AND ctbl_img.imgMain=1)as imgMain,
(select imgDesc from ctbl_img where vtbl_news.newsId = ctbl_img.newsId AND ctbl_img.imgMain=1)as imgMainDesc,

	            	vtbl_news.newsVideo,

	            	vtbl_news.newsVideoDesc,

	            	vtbl_news.newsUrl,

	            	vtbl_news.imgNames,

	            	vtbl_news.imgDescs,

	            	vtbl_news.tagIds,

	            	vtbl_news.brandIds,

	            	vtbl_news.connectIds,

	            	vtbl_news.regionIds,

	            	vtbl_news.categoryId,

	            	vtbl_news.categoryName,

	            	vtbl_news.sourceId,

	            	vtbl_news.sourceName,

	            	vtbl_news.sourceLink,

	            	vtbl_news.authorId,

	            	vtbl_news.authorName,

	            	vtbl_news.newsSeoTitle,

	            	vtbl_news.newsSeoDesc,

	            	vtbl_news.newsSeoKeywords,

	            	vtbl_news.newsSearch,

	            	vtbl_news.newsTimeUpdate,

	            	vtbl_news.newsTimePublic,

	            	vtbl_news.newsTime,

	            MATCH (tbl_search.newsSearch) AGAINST (var_layout IN NATURAL LANGUAGE MODE) AS score

	                FROM vtbl_news

	                LEFT JOIN tbl_search

	                	ON tbl_search.newsId= vtbl_news.newsId AND vtbl_news.newsStatus='4'

	                WHERE MATCH (tbl_search.newsSearch)

	                AGAINST (var_layout IN BOOLEAN MODE);

	            SELECT count(newsId) FROM search_results INTO var_count_results;

	            SELECT max(score) from search_results INTO var_max_score;

	            IF var_max_score > 0 THEN

	            	SELECT * FROM search_results ORDER BY score DESC;

	            ELSE

	                SELECT * FROM search_results ORDER BY newsId DESC;

	            END IF;

            END IF;

            DROP TEMPORARY TABLE IF EXISTS search_results;

        END$$






DELIMITER ;

