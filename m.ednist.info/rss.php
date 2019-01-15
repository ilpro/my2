<?php

require_once 'config/iniParse.class.php';
require_once Config::getRoot()."/config/header.inc.php";

$servName = $_SERVER['HTTP_HOST'];

header("Content-type: text/xml");

if(isset($_GET['act']) && $_GET['act']!='') {

    Map::genRss($_GET['act']);

} else {

    echo '<?xml version="1.0" encoding="UTF-8"?>
            <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_categories.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_important.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_dossier.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_tags.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_news.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_public.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_ukrNet.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_blogs.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>
               <sitemap>
                  <loc>http://'.$servName.'/sitemap_interviews.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>';

    db::sql("SELECT * FROM `tbl_category` ORDER BY `categoryId`");
    $result = db::query();

    foreach( $result as $category )
        echo '<sitemap>
                  <loc>http://'.$servName.'/sitemap_category_'.$category['categoryTranslit'].'.xml</loc>
                  <lastmod>'.date('Y-m-d').'</lastmod>
               </sitemap>';

    echo'</sitemapindex>';

}