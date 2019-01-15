



 --  Вью для вытаскивания слов поиска товара            

DROP VIEW IF EXISTS vtbl_news_search;

CREATE VIEW vtbl_news_search AS

SELECT

                vtbl_news.newsId,             

                newsHeader,

                newsSubheader,

                newsText,

                newsStatus,

                newsType,

                newsMain,

                newsIsGallery,

                newsIsVideo,

                newsImg,

                newsVideo,

                newsVideoDesc,

                newsUrl,

                sourceName,

                sourceLink,

                authorName,

                newsSeoTitle,

                newsSeoDesc,

                newsSeoKeywords,

                newsTimeUpdate,

                newsTimePublic,

                newsTime,

                newsTimestamp,

                 GROUP_CONCAT(DISTINCT IFNULL(tbl_tag.tagSearch,'') SEPARATOR ' ') AS tags,

                GROUP_CONCAT(DISTINCT IFNULL(tbl_brand.brandSearch,'') SEPARATOR ' ') AS brands,

                GROUP_CONCAT(DISTINCT IFNULL(tbl_region.regionSearch,'') SEPARATOR ' ') AS regions

FROM vtbl_news

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

                    WHERE vtbl_news.newsId=363;

                    

                    



tbl_product.product_id,

tbl_brand.brand_search,

tbl_sex.sex_search,

tbl_product.product_name,

tbl_product.product_desc,

tbl_product.product_text,

tbl_cat.cat_id_parent AS cat_parent,

tbl_cat.cat_search,

tbl_product.product_color,

tbl_color.color_search AS color_acc_search

FROM tbl_product

    LEFT JOIN tbl_brand 

        ON tbl_product.brand_id=tbl_brand.brand_id

    LEFT JOIN tbl_sex 

        ON tbl_product.product_sex=tbl_sex.sex_id    

    LEFT JOIN ctbl_product_cat

        ON tbl_product.product_id=ctbl_product_cat.product_id

    LEFT JOIN tbl_cat

        ON ctbl_product_cat.cat_id=tbl_cat.cat_id

    LEFT JOIN ctbl_color_acc

        ON tbl_product.product_id=ctbl_color_acc.product_id

    LEFT JOIN tbl_color

        ON ctbl_color_acc.color_id=tbl_color.color_id;























































                

                LEFT JOIN ctbl_tag

                on tbl_news.newsId=ctbl_tag.newsId

                LEFT JOIN tbl_tag

                on ctbl_tag.tagId=tbl_tag.tagId;

                

                

                

SELECT DISTINCT vtbl_news.newsId as newsId, 

(select GROUP_CONCAT(tagId SEPARATOR '|') as tagId from ctbl_tag where vtbl_news.newsId = ctbl_tag.newsId )as tagIds

FROM vtbl_news                

                





                

                

                LEFT JOIN ctbl_tag

                on tbl_news.newsId=ctbl_tag.newsId

                LEFT JOIN tbl_tag

                on ctbl_tag.tagId=tbl_tag.tagId;

                

                

                

DELIMITER //

    DROP PROCEDURE IF EXISTS  getNewsInfo //

    CREATE PROCEDURE getNewsInfo()

BEGIN

            DECLARE var_count_results INT;

            DROP TEMPORARY TABLE IF EXISTS vtbl_fullNews;

            CREATE TEMPORARY TABLE vtbl_fullNews                  

            SELECT * FROM vtbl_news WHERE productPage = search_string AND orderId<>exclude_id ORDER BY orderId DESC

            ;  

            DROP TEMPORARY TABLE IF EXISTS search_results;                                               

        END

//

DELIMITER ;                 

                

                

                

                

SELECT tbl_news.newsId, GROUP_CONCAT(ctbl_tag.tagId ORDER BY ctbl_tag.tagId SEPARATOR '|') AS tagId FROM vtbl_news 

WHERE vtbl_news.newsId=ctbl_tag.newsId

                AND ctbl_tag.tagId=tbl_tag.tagId;  



LEFT JOIN ctbl_tag on vtbl_news.newsId=ctbl_tag.newsId

LEFT JOIN tbl_tag on ctbl_tag.tagId=tbl_tag.tagId;                

                

SELECT k.id,m.name,t.name,c.name FROM kvart AS k,metro AS m, type AS t, city AS c

WHERE k.metro_id = m.metro_id AND k.type_id=t.type_id AND k.city_id=c.city_id

              

                

                

SELECT DISTINCT vtbl_news.newsId,

(select GROUP_CONCAT(tagName SEPARATOR '|') as tagName from tbl_tag where ctbl_tag.tagId= tbl_tag.tagId )as tagNames,

FROM vtbl_news

LEFT JOIN ctbl_tag on vtbl_news.newsId=ctbl_tag.newsId

LEFT JOIN tbl_tag on ctbl_tag.tagId=tbl_tag.tagId; 

              

SELECT DISTINCT vtbl_news.newsId as newsId`, 

(select GROUP_CONCAT(tagId) as tagId from tbl_tag where vtbl_news.newsId = tbl_tag.newsId )as tag

FROM vtbl_news



                

SELECT DISTINCT `name` as `name1`, 

(select GROUP_CONCAT(`tagId`) as `tagId` from ctbl_tagv where `name` = `name1` )as `pet`

FROM `test`                

 

2

3

4

5

SELECT *

FROM kvart

LEFT JOIN metro ON kvart.metro_id=metro.metro_id

LEFT JOIN TYPE ON kvart.type_id=TYPE.type_id

LEFT JOIN city ON kvart.city_id=city.city_id               

                

                

                

                

                







                

GROUP_CONCAT(DISTINCT b.book ORDER BY b.book ASC SEPARATOR ', ') AS books

02

    -> FROM `author`