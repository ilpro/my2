<?
class NewsLineBanner{

    public function getRandBanner($news, $ini, $controller){
        if(isset($_SESSION['curr_sidebar']) && $_SESSION['curr_sidebar'] < 5){
            $_SESSION['curr_sidebar'] ++;
        }
        else
            $_SESSION['curr_sidebar'] = 1;
        $k = 0;
        $banner = '<div class="clearfix"></div><div class="pull-right line-sidebar">';
        include '../views/ajaxBlock/newBanner.ajax.php';
        do{
            switch($_SESSION['curr_sidebar']){
                case 1:{
                    if($controller != 'exclusive'){
                        $title = 'Ексклюзив';
                        $newsList = $news->getNews(' WHERE `newsStatus`=4 AND `newsExclusive`=1 ORDER BY `newsTimePublic` DESC LIMIT 15 ');
                        include '../views/ajaxBlock/category.ajax.php';
                        $k = 1;
                    }
                    else
                        $_SESSION['curr_sidebar']++;
                    break;
                }
                case 2:{
                    if($controller != 'category_15'){
                        $title = 'Резонанс';
                        $newsList = $news->getNews(' WHERE `newsStatus`=4 AND `categoryId`=15 ORDER BY `newsTimePublic` DESC LIMIT 15');
                        include '../views/ajaxBlock/category.ajax.php';
                        $k = 1;
                    }
                    else
                        $_SESSION['curr_sidebar']++;
                    break;
                }
                case 3:{
                    if($controller != 'category_14'){
                        $title = 'Інфографіка';
                        $newsList = $news->getNewsByTranslit('infografika',15);
                        include '../views/ajaxBlock/category.ajax.php';
                        $k = 1;
                    }
                    else
                        $_SESSION['curr_sidebar']++;
                    break;
                }
                case 4:{
                    if($controller != 'important'){
                        $title = 'Важливо!';
                        $newsList = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 15 ');
                        include '../views/ajaxBlock/category.ajax.php';
                        $k = 1;
                    }
                    else
                        $_SESSION['curr_sidebar']++;
                    break;
                }
                case 5:{
                    if($controller != 'popular'){
                        $title = 'Популярне';
                        $newsList = $news->getPopularNews();
                        include '../views/ajaxBlock/category.ajax.php';
                        $k = 1;
                    }
                    else
                        $_SESSION['curr_sidebar']=1;
                    break;
                }
            }
        }while($k==0);
        $banner .= '</div>';
        return $banner;
    }
}