DROP VIEW IF EXISTS vtbl_tag;
CREATE VIEW vtbl_tag AS
SELECT  
ctbl_tag.tagId,
count(*) AS 'tagScore', 
tagName,
tagDesc,
tagSearch
FROM ctbl_tag
    LEFT JOIN tbl_tag
            ON ctbl_tag.tagId=tbl_tag.tagId
GROUP BY tagId;

DROP VIEW IF EXISTS vtbl_brand;
CREATE VIEW vtbl_brand AS
SELECT  
tbl_brand.brandId,
count(*) AS 'brandScore', 
brandName,
brandImg,
brandDesc,
(select GROUP_CONCAT(newsId SEPARATOR '|') as newsIds from ctbl_brand where ctbl_brand.brandId=tbl_brand.brandId )as newsIds,                
brandSearch
FROM ctbl_brand
    RIGHT JOIN tbl_brand
            ON ctbl_brand.brandId=tbl_brand.brandId
GROUP BY brandId;


DROP VIEW IF EXISTS vtbl_region;
CREATE VIEW vtbl_region AS
SELECT  
ctbl_region.regionId,
count(*) AS 'regionScore', 
regionName,
regionSearch
FROM ctbl_region
    LEFT JOIN tbl_region
            ON ctbl_region.regionId=tbl_region.regionId
GROUP BY regionId;
