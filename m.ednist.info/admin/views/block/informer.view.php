<input id="button_informer" type="button" value="Информер" data-show="0">
<div class="menu_informer">
  <?
    if (is_array($page_data['users']))
        foreach ($page_data['users'] as $key=>$item) {
            include Config::getAdminRoot() . '/views/users/user_informer.view.php';
        }

    ?>
</div>
