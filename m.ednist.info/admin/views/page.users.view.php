<?php
if($_SESSION['user']['role']==1){
?>
    <div class="wrapper box users" id="auth">
        <div class="head">
            <div class="clear"></div>

            <?
            include Config::getAdminRoot() . "/views/block/logo_item.view.php";
            include Config::getAdminRoot() . "/views/block/menu_item.view.php";
            ?>

            <div class="search ui-widget">
                <i class="search-icon"></i>
                <input type="text" name="country" id="tags" placeholder="Поиск">
            </div>
            <div class="filter right">
                <div class="add" id="addUser"><i></i></div>
                <div class="exit" id="logout"><i></i></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content" id="all-flavors">
            <div class="row flavor all adv filter-user add-line ">
                <div class="filter-name"><input tabindex='1' type="text" value="" id="userName" placeholder="Имя"></div>
                <div class="filter-ability">
                    <select id="userRole" class="dropDown" width='200'>
                        <option value=''>Права пользователя</option>
                        <?
                        foreach ($ini['user.role'] as $i => $v) {
                            echo "<option value='$i'>$v</option>";
                        }
                        ?>
                    </select>

                </div>
                <input id="register" type="hidden" checked>

                <div class="filter-email"><input tabindex='3' type="text" id="email" value="" placeholder="Email"></div>
                <div class="filter-password"><input tabindex='4' type="text" value="" id="pass" placeholder="Пароль">
                </div>
                <div class="filter-active"><input id='active' type="checkbox"/>
                    <label for="active"><span></span></label>
                </div>
                <div class="pull-right">
                    <div class="delete-src"><a tabindex='6' href="javascript:;" value='' id="clear"><i>&nbsp;</i></a>
                    </div>
                    <div class="check-top"><a tabindex='5' href="javascript:;" value='' id='login'><i>&nbsp;</i></a>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div id="listing"></div>
        </div>
    </div>
    <div class="helper"></div>

<? } ?>