<?php

$html_view = '<div class="row flavor all adv" id="line' . $item['userId'] . '">
                <div class="filter-name"><input type="text" value="' . $item['userName'] . '" disabled></div>
                <div class="filter-ability"><input type="text" value="' . $ini['user.role'][$item['userRole']] . '" disabled></div>
                <div class="filter-email"><input type="text" value="' . $item['userEmail'] . '" disabled></div>
                <div class="filter-password"><input type="password" value="xxxxxx" disabled></div>
                <div class="pull-right">
                    <div class="edit"><a tabindex="' . ($item['userId'] * 10 + 5) . '" href="javascript: getEditUser(' . $item['userId'] . ');"><i></i></a></div>
                    <div class="delete"><a tabindex="' . ($item['userId'] * 10 + 6) . '" href="javascript: deleteUser(' . $item['userId'] . ');"><i></i></a></div>
                </div>
                <div class="clear"></div>
            </div>';
