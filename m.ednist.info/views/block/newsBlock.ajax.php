<?php

            $a1=1;$a2=1;$html='';

            if($items)

            foreach($items as $i=>$item){

             switch($item['variant'])  {

                case 2: case 1: $a2++;

                $html .= '<div class="relative-block">';

                $html .= '<a href="/news/'.$item['newsId'].'">';

                $html .= '<div  class="six-block-item '.($a2%2==0?'gray2':'gray1').'">';

                $html .= '<div class="small-date">'.$item['newsTimePublic'].'</div>';

                $html .= '<div class="news-title">'.$item['newsHeader'].'</div>';

                $html .= '</div></a><div class="absolute"><div class="news-them">';

                $html .= '<a href="/category/'.$item['categoryTranslit'].'" >'.$item['categoryName'].'</a>';

                $html .= '</div>';

                if($item['newsMain']>0)

                $html .= '<a href = "/important" class="oclick"></a>';

                $html .= '</div></div>';

                break;



                case 0: $a2++;

                $urlImg = $ini['url.media']."images/".$item['newsId']."/main/240.jpg";

                $html .= '<div class="relative-block"><a href="/news/'.$item['newsId'].'" >';

                $html .= '<div class="six-block-item ';

                $html .= $a2%2==0?'news-info1 color-news-info1':'news-info2';

                $html .= ' "><div class="small-date">'.$item['newsTimePublic'].'</div>';

                $html .= '<div  class="news-title">'.$item['newsHeader'].'</div>';

                $html .= '<div class="news-desc">'.$item['newsSubheader'].'</div>';

                $html .= '</div><div class="six-block-item news-photo">';

                $html .= '<img src="'.$urlImg.'" alt="'.$item['newsHeader'].'" style="width: 240px; height:240px">';

                $html .= '<div class="rectangle-';

                $html .= $a2%2==0?'r':'l';

                $html .= ' color-news-info';

                $html .= $a2%2==0?'1':'2';

                $html .= '"></div></div></a><div class="absolute';

                $html .= $a2%2==0?'':' move-right';

                $html .= '"><div class="news-them"><a href="/category/'.$item['categoryTranslit'].'" >'.$item['categoryName'].'</a></div>';

                if($item['newsMain']>0)

                $html .= '<a href = "/important" class="oclick"></a>';

                $html .= '</div></div>';

                break;

             }

             }