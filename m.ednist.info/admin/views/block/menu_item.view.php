<div id="dropdown-1" class="dropdown dropdown-tip dropdown-anchor-right dropdown-relative">

    <ul class="dropdown-menu">

        <? $nav = '';

        foreach ($ini['mainmenu'] as $k => $v) {
            if($_SESSION['user']['role']!=1&&($k=='settings'||$k=='users'||$k=='statistics')){
                continue;
            }
            echo "<li><a href=\"$k\">$v</a></li>";

            if ($page == $k and $k != 'news') $nav = $v;

        }?>

    </ul>

</div>

<div class="nav" data-dropdown="#dropdown-1">

    <i class="nav-icon"></i>

    <p><?= $nav; ?></p>

</div>