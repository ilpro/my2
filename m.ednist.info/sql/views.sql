DROP VIEW IF EXISTS vtbl_news;  -- ДОБАВЛЕНО!

CREATE VIEW vtbl_news AS            

SELECT DISTINCT                

tbl_news.newsId,                             

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

newsPostVk,                

newsPostFb,                

newsPostTw,                

newsSocText,                                

newsSocTime,                

(select imgName from ctbl_img where tbl_news.newsId = ctbl_img.newsId AND ctbl_img.imgMain=1)as imgMain,                

(select imgDesc from ctbl_img where tbl_news.newsId = ctbl_img.newsId AND ctbl_img.imgMain=1)as imgMainDesc,                

(select GROUP_CONCAT(imgName SEPARATOR '|') as imgName from ctbl_img where tbl_news.newsId = ctbl_img.newsId )as imgNames,                

(select GROUP_CONCAT(imgDesc SEPARATOR '|') as imgDesc from ctbl_img where tbl_news.newsId = ctbl_img.newsId )as imgDescs,                

(select GROUP_CONCAT(tagId SEPARATOR '|') as tagId from ctbl_tag where tbl_news.newsId = ctbl_tag.newsId )as tagIds,                

(select GROUP_CONCAT(brandId SEPARATOR '|') as brandId from ctbl_brand where tbl_news.newsId = ctbl_brand.newsId )as brandIds,                

(select GROUP_CONCAT(newsConnect SEPARATOR '|') as newsConnect from ctbl_connect where tbl_news.newsId = ctbl_connect.newsId )as connectIds,                

(select GROUP_CONCAT(regionId SEPARATOR '|') as regionId from ctbl_region where tbl_news.newsId = ctbl_region.newsId )as regionIds,                

tbl_news.categoryId,                               

tbl_category.categoryName AS categoryName,                

tbl_category.categoryTranslit AS categoryTranslit,                

tbl_news.sourceId,                

tbl_source.sourceName AS sourceName,                

tbl_source.sourceLink AS sourceLink,                

tbl_news.authorId,                

tbl_author.authorName AS authorName,                

newsSeoTitle,                

newsSeoDesc,                

newsSeoKeywords,                

tbl_search.newsSearch AS newsSearch	,                

UNIX_TIMESTAMP(newsTimeUpdate) AS newsTimeUpdate,                

UNIX_TIMESTAMP(newsTimePublic) AS newsTimePublic,               

 UNIX_TIMESTAMP(newsTime) AS newsTime            

 FROM tbl_news                                                        

 LEFT JOIN tbl_category                

 on tbl_news.categoryId=tbl_category.categoryId                

 LEFT JOIN tbl_source                

 on tbl_news.sourceId=tbl_source.sourceId                

 LEFT JOIN tbl_author                

 on tbl_news.authorId=tbl_author.authorId                

 LEFT JOIN tbl_search                

 on tbl_news.newsId=tbl_search.newsId;        
 
 
 
 
 
DROP VIEW IF EXISTS vtbl_newsListAdmin;  -- ДОБАВЛЕНО!

CREATE VIEW vtbl_newsListAdmin AS            

SELECT

tbl_news.newsId,

newsHeader,

newsSubheader,

newsText,

newsStatus,

newsType,

newsMain,

newsUrl,
newsVideo,
newsIsGallery,
newsIsVideo,
newsVideoDesc,
tbl_news.categoryId,

tbl_category.categoryName AS categoryName,

tbl_news.sourceId,

tbl_source.sourceName AS sourceName,

tbl_source.sourceLink AS sourceLink,

tbl_news.authorId,

tbl_author.authorName AS authorName,

tbl_news.userId,

tbl_user.userName AS userName,

newsSeoTitle,
newsSeoDesc,
newsSeoKeywords,
tbl_news.newsTimePublic AS newsTimePublic,
tbl_news.newsTimeUpdate AS newsTimeUpdate,
tbl_news.newsTime AS newsTime,
DATE_FORMAT(newsTime,'%H:%i %d.%m.%Y') AS newsTimeFormat

 FROM tbl_news                                                        

 LEFT JOIN tbl_category                

 on tbl_news.categoryId=tbl_category.categoryId                

 LEFT JOIN tbl_source                

 on tbl_news.sourceId=tbl_source.sourceId                

 LEFT JOIN tbl_author                

 on tbl_news.authorId=tbl_author.authorId                
 
  LEFT JOIN tbl_user                
 on tbl_news.userId=tbl_user.userId;




DROP VIEW IF EXISTS vtbl_newsListClient;

CREATE VIEW vtbl_newsListClient AS            

SELECT

tbl_news.newsId,

newsHeader,

newsSubheader,

newsText,

newsStatus,

newsType,

newsMain,

newsUrl,
newsVideo,
newsIsGallery,
newsIsVideo,
newsVideoDesc,
tbl_news.categoryId,

tbl_category.categoryName AS categoryName,

tbl_news.sourceId,

tbl_source.sourceName AS sourceName,

tbl_source.sourceLink AS sourceLink,

tbl_news.authorId,

tbl_author.authorName AS authorName,

newsSeoTitle,
newsSeoDesc,
newsSeoKeywords,
tbl_news.newsTimePublic AS newsTimePublic,
DATE_FORMAT(newsTimePublic,'%H:%i %d.%m.%Y') AS newsTimePublicFormat,
tbl_news.newsTime AS newsTime,
DATE_FORMAT(newsTime,'%H:%i %d.%m.%Y') AS newsTimeFormat

 FROM tbl_news                                                        

 LEFT JOIN tbl_category                

 on tbl_news.categoryId=tbl_category.categoryId                

 LEFT JOIN tbl_source                

 on tbl_news.sourceId=tbl_source.sourceId                

 LEFT JOIN tbl_author                

 on tbl_news.authorId=tbl_author.authorId                
 
WHERE tbl_news.newsStatus='4';




DROP VIEW IF EXISTS vtbl_newsClient;  -- ДОБАВЛЕНО!

CREATE VIEW vtbl_newsClient AS            

SELECT                

tbl_news.newsId,                             

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
newsSeoTitle,
newsSeoDesc,
newsSeoKeywords,                
                                
tbl_news.categoryId,                               

tbl_category.categoryName AS categoryName,                

tbl_news.authorId,                

tbl_author.authorName AS authorName,                                         

UNIX_TIMESTAMP(newsTimePublic) AS newsTimePublic                      

 FROM tbl_news                                                        

 LEFT JOIN tbl_category                

 on tbl_news.categoryId=tbl_category.categoryId                              

 LEFT JOIN tbl_author                

 on tbl_news.authorId=tbl_author.authorId
 WHERE tbl_news.newsStatus='4';                              


DROP VIEW IF EXISTS vtbl_newslight;
CREATE VIEW `vtbl_newslight` AS select
`tbl_news`.`newsId` AS `newsId`,
`tbl_news`.`newsHeader` AS `newsHeader`,
`tbl_news`.`newsSubheader` AS `newsSubheader`,
`tbl_news`.`newsText` AS `newsText`,
`tbl_news`.`newsStatus` AS `newsStatus`,
`tbl_news`.`newsType` AS `newsType`,
`tbl_news`.`newsMain` AS `newsMain`,
`tbl_news`.`newsIsGallery` AS `newsIsGallery`,
`tbl_news`.`newsIsVideo` AS `newsIsVideo`,
`tbl_news`.`newsImg` AS `newsImg`,
`tbl_news`.`newsVideo` AS `newsVideo`,
`tbl_news`.`newsVideoDesc` AS `newsVideoDesc`,
`tbl_news`.`categoryId` AS `categoryId`,
`tbl_news`.`categoryName` AS `categoryName`,
`tbl_news`.`authorId` AS `authorId`,
`tbl_author`.`authorName` AS `authorName`,
`tbl_news`.`newsSeoTitle` AS `newsSeoTitle`,
`tbl_news`.`newsSeoDesc` AS `newsSeoDesc`,
`tbl_news`.`newsSeoKeywords` AS `newsSeoKeywords`,
`tbl_news`.`newsTime` AS `newsTime` from ((`tbl_news`)
left join `tbl_author` on((`tbl_news`.`authorId` = `tbl_author`.`authorId`)));
