<?php

include_once('libs/simple_html_dom.php');  // библиотека для парсинга с HTML

/**
 * Class Parser
 * Основной класс парсера, сам процесс парсинга написан здесь
 */

class Parser {

    /**
     * Получаем поля элементов источника по ссылке
     * @param $link
     * @return array
     */

    protected function getFeedFieldsByLink( $link ) {

        $result_arr = array();  // fields of feed item
        $test_item = null;  // for initialize namespaces

        $xml = simplexml_load_file( $link );

        if( isset( $xml->item[0] ) )  $test_item = $xml->item[0];
        if( isset( $xml->channel->item[0] ) )  $test_item = $xml->channel->item[0];


        if( $test_item ) {

            foreach( $test_item as $tag=>$val ) {

                $attr_arr = [];

                if( count($test_item->$tag->attributes()) > 0 ) {

                    foreach( $test_item->$tag->attributes() as $attr=>$val ) {
                        $attr_arr[] = $attr;
                    }
                }

                $result_arr[$tag] = $attr_arr;
            }

            $namespaces = $test_item->getNameSpaces(true);

            foreach( $namespaces as $namespace_name=>$val ) {

                $namespace_obj = $test_item->children($namespaces[$namespace_name]);

                foreach( $namespace_obj as $namespace_field=>$val ) {

                    $attr_arr = [];

                    if( count($namespace_obj->$namespace_field->attributes()) > 0 ) {

                        foreach( $namespace_obj->$namespace_field->attributes() as $attr=>$val ) {
                            $attr_arr[] = $attr;
                        }
                    }

                    $result_arr[$namespace_name.':'.$namespace_field] = $attr_arr;
                }
            }
        }

        return $result_arr;
    }


    /**
     * Проверяем источник на валидность XML
     * @param $link
     * @return bool
     */

    public function checkFeed( $link ) {

        $xml = @simplexml_load_file( $link );

        if ($xml===false)
            return false;

        return $xml;
    }


    /**
     * Обработка XML строки
     * @param $str
     * @return string
     */

    private function xml_str( $str ) {

        $result = trim( strip_tags( (string)$str ) );

        return $result;
    }


    private function emptyFieldCatcher( $link, $newsField ) {

        var_dump( 'Пустой результат: '.$link.' - '.$newsField );
        
        return false;
    }


    /**
     * Проверка нестандартных полей rss-ленты
     * @param $link - ссылка на источник
     * @param $tag - тег текущего поля элемента источника в цикле
     * @param $val - значение тега текущего поля элемента источника в цикле
     * @param $newsField - поле таблицы новостей для парсинга
     * @param $feedField - тег источника для парсинга
     * @param $feedFieldAttr - атрибут тега источника для парсинга
     * @return bool|string
     */

    private function checkCustomFeedFields( $link, $tag, $val, $newsField, $feedField, $feedFieldAttr ) {

        /*
         * Если картинка передается тегом <img ... />
         */
        if( $newsField == 'newsImg' && strpos ((string)$val,'src=') != false ) {

            if( !empty($feedFieldAttr) )
                $val = $tag->getAttribute($feedFieldAttr);

            $linkDomainArr = parse_url( $link );

            $doc = new DOMDocument();
            $doc->loadHTML((string)$val);
            $imageTags = $doc->getElementsByTagName('img');

            foreach($imageTags as $tag) {
                $tmp_check = parse_url( $tag->getAttribute('src') );
                if( !$tmp_check['host'] ) {
                    $src = $linkDomainArr['scheme'] .'://'. $linkDomainArr['host'] . $tag->getAttribute('src');
                    return $src;
                }
                return $tag->getAttribute('src');
            }
        }

        return false;
    }


    /**
     * Проверяем элемент источника на нужные для парсинга пространства имен и их атрибуты
     * @param $feedItem - элемент источника
     * @param $feedField - поле для парсинга
     * @param $feedFieldAttr - атрибут поля для париснга
     * @return bool|string
     */

    private function checkNameSpaces( $feedItem, $feedField, $feedFieldAttr ) {

        $namespaces = $feedItem->getNameSpaces(true);
        foreach( $namespaces as $namespace_name=>$val ) {

            $namespace_obj = $feedItem->children($namespaces[$namespace_name]);

            foreach( $namespace_obj as $namespace_field=>$val ) {

                if( $namespace_name.':'.$namespace_field == $feedField ) {
                    if( !empty($feedFieldAttr) ) {
                        return $this->xml_str( $val->attributes()->$feedFieldAttr );
                    }
                    return $this->xml_str($namespace_obj->{$namespace_field});
                }
            }
        }

        return false;
    }


    /**
     * Парсим элементы источника по ссылке
     * @param $link
     * @param $parsingFields
     * @param $parsingSelectors
     * @param array $check_time_arr
     * @return array|bool
     */

    protected function parseFeed( $link, $parsingFields, $parsingSelectors, $check_time_arr=array() ) {

        $xml = $this->checkFeed( $link );
        if( $xml === false ) return false;

        $resultItems = array();
        $feedItems = array();

        if( isset( $xml->item ) ) $feedItems = $xml;
        if( isset( $xml->channel->item ) ) $feedItems = $xml->channel;

        $k=0;

        foreach( $feedItems->item as $feedItem ) {

            if( !empty($check_time_arr) ) {

                $pubTime = strtotime($feedItem->pubDate);
                if( time() - $check_time_arr['parsing_interval'] > $pubTime ) continue; // for testing
//                if( $check_time_arr['last_parsing_time'] < $pubTime ) continue; // for production
                $feedItem->pubDate = date("Y-m-d H:i:s",$pubTime);
            }


            /*
             * Перебираем поля спарсенного источника
             */
            $k++;
            foreach( $feedItem as $tag => $val ) {

                /*
                 * Перебираем поля для парсинга и ищем совпадение с нужными нам полями источника
                 */
                foreach( $parsingFields as $newsField => $feedFieldArr ) {

                    $feedField = key( $feedFieldArr );              // тег для париснга
                    $feedFieldAttr = $feedFieldArr[$feedField];     // атрибут для парсинга, может = ''

                    if( $feedField == $tag ) {

                        if( $this->checkCustomFeedFields( $link, $tag, $val, $newsField, $feedField, $feedFieldAttr ) != false ) {
                            $resultItems[$k][$newsField] = $this->checkCustomFeedFields( $link, $tag, $val, $newsField, $feedField, $feedFieldAttr );
                        }
                        else {
                            if( !empty($feedFieldAttr) ) {
                                $resultItems[$k][$newsField] = $this->xml_str( $val->attributes()->$feedFieldAttr );
                            }
                            else $resultItems[$k][$newsField] = $this->xml_str($val);
                        }

                        if( empty($resultItems[$k][$newsField]) ) $this->emptyFieldCatcher( $link, $newsField );
                    }
                    else if( $this->checkNameSpaces( $feedItem, $feedField, $feedFieldAttr ) != false ) {

                        $resultItems[$k][$newsField] = $this->checkNameSpaces( $feedItem, $feedField, $feedFieldAttr );
                        if( empty($resultItems[$k][$newsField]) ) $this->emptyFieldCatcher( $link, $newsField );
                    }
                    else continue;
                }
            }

            /*
             *  Парсинг по селекторам из HTML кода страницы
             */
            if( !empty($parsingSelectors) && isset($parsingFields['newsUrl']) ) {

                $feedLink = key($parsingFields['newsUrl']);
                $link = (string)$feedItem->$feedLink;
                $html = file_get_html($link);

                foreach( $parsingSelectors as $tag=>$selector ) {
                    $tmp_res = '';
                    $res = $html->find($selector);
                    foreach( $res as $item ) {
                        $tmp_res .= $item->plaintext;
                    }

                    $resultItems[$k][$tag] = trim( $tmp_res );

                    if( empty($resultItems[$k][$tag]) ) $this->emptyFieldCatcher( $link, $tag );
                }
            }


        }

//        var_dump( $resultItems );
//        exit();

        var_dump( $link .' - '. count( $resultItems ) );

        return $resultItems;
    }



}