<?
$title = $settings['settingsSiteName'];
$description = $settings['settingsSiteTitle'];
$keywords = $settings['settingsDescription'];

switch ($page) {
    case 'news':
    case 'article':
        if (isset($page_data['material'])) {
            $item = $page_data['material'];
            $title = 'ЄДНІСТЬ : ' .strip_tags($item['newsSeoTitle']).' | Є!';
            $description = strip_tags($item['newsSeoDesc']);
            $keywords = strip_tags($item['newsSeoKeywords']);
            break;
        }
        else{
        $title = 'ЄДНІСТЬ : Новини | Є!';
        $description = '';
        $keywords = '';
        break;
        }

    case 'tag':
        $title = 'ЄДНІСТЬ : #' . $page_data['tag']['tagName']. ' | Є!';
        $description = '';
        $keywords = '';
        break;
    case 'dossier':
        if (isset($page_data['brand'])) {
            $item = $page_data['brand'];
            $title = strip_tags($item['brandName']). ' | досьє ЄДНІСТЬ | ЗГАДУВАННЯ | УСІ НОВИНИ | Є!';
            $description = strip_tags($item['brandName'] . " - новини, біографія, компромат, інтерв'ю, фото, досьє «ЄДНІСТЬ.ІНФО»");
            $keywords = strip_tags($item['brandSearch']);
            break;
        }
        else{
            $title = 'ЄДНІСТЬ : Досьє | Є!';
            $description = '';
            $keywords = '';
            break;
        }
    case 'category':
        if ($id) {
            $item = $page_data['category'];
            $title = 'ЄДНІСТЬ : ' . $item['categoryName'].' | Є!';
            $description = '';
            $keywords = '';
        } break;
    case 'important':
        $title = 'ЄДНІСТЬ : Важливо | Є!';
        $description = '';
        $keywords = '';
        break;
    case 'video':
        $title = 'ЄДНІСТЬ : Відео | Є!';
        $description = '';
        $keywords = '';
        break;
    case 'photo':
        $title = 'ЄДНІСТЬ : Фотогалереї | Є!';
        $description = '';
        $keywords = '';
        break;
    case 'public':
        $title = 'ЄДНІСТЬ : Публікації | Є!';
        $description = '';
        $keywords = '';
        break;
    case 'rss':
        $title = 'ЄДНІСТЬ : RSS | Є!';
        $description = '';
        $keywords = '';
        break;
}
?>
<title><?= $title; ?></title>
<meta name="description" content="<?= $description; ?>">
<meta name="keywords" content="<?= $keywords; ?>">
<meta property="fb:pages" content="1640600432838075" />
<!--<meta name=viewport content="width=device-width">-->



<link rel="icon" type="image/png" href="/favicon.ico">
<!-- <link rel="shortcut icon" type="image/png" href="/img/favicon.ico"> -->
