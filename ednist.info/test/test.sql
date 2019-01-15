SELECT
           news.newsId,
           newsHeader,
           newsTimePublic AS newsTime,
           img.imgName AS newsImage,
           (
               SELECT
                 (GROUP_CONCAT(tags.tagName)) AS newsTags
               FROM ctbl_tag AS news_tags
               JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId
               WHERE news_tags.newsId = news.newsId
           ) AS newsTags
         FROM tbl_news AS news
         JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1
         WHERE newsStatus = 4
         ORDER BY newsTimePublic DESC
         LIMIT 50 OFFSET 0

--

SELECT
           news.newsId,
           newsHeader,
           newsSubheader,
           newsText,
           newsSeoTitle,
           newsSeoDesc,
           newsSeoKeywords,
           newsTimePublic AS newsTime,
           img.imgName AS newsImage,
           (GROUP_CONCAT(tags.tagName)) AS newsTags,
           (
               SELECT
                 CONCAT('[',
                   GROUP_CONCAT(
                     CONCAT('{
                       "newsId":"', related.newsId, '",
                       "newsHeader":"', news2.newsHeader, '",
                       "newsImage":"', img2.imgName, '"
                     }') SEPARATOR ","
                   ), '
                 ]')
               FROM ctbl_connect AS related
               LEFT JOIN tbl_news AS news2 ON related.newsId = news2.newsId
               LEFT JOIN ctbl_img AS img2 ON img2.newsId = news2.newsId
               WHERE related.newsId = news.newsId AND img2.imgMain = 1
           ) AS relatedNews
         FROM tbl_news AS news
         JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1
         JOIN ctbl_tag AS news_tags ON news_tags.newsId = news.newsId
         JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId
         WHERE news.newsId = 86765