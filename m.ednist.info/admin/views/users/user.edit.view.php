<?php

$echo = '<div class="filter-name"><input tabindex="' . ($df['userId'] * 10 + 1) . '" type="text" value="' . $df['userName'] . '" class="userName" placeholder="Имя" ></div>
                 <div class="filter-ability" >
            			<select class="userRole" tabindex="' . ($df['userId'] * 10 + 2) . '"  width="200">';
foreach ($ini['user.role'] as $i => $v) {
    $echo .= "<option value='$i'";
    if ($df['userRole'] == $i)
        $echo .= " selected";
    $echo .= ">$v</option>";
}

$echo .= '</select>
                </div>
                <input class="edit" type="hidden" checked >
                <div class="filter-email"><input tabindex="' . ($df['userId'] * 10 + 3) . '" type="text" class="email" value="' . $df['userEmail'] . '" placeholder="Email"></div>
                <div class="filter-password"><input tabindex="' . ($df['userId'] * 10 + 4) . '" type="text" value="" class="password" placeholder="Новый пароль" ></div>
                    <div class="filter-action">
            <input class="active" id="active' . $df['userId'] . '"   type="checkbox"  ' . $isActive . '/>
            <label for="active' . $df['userId'] . '"><span></span>
            </label></div>
                <div class="pull-right">
                    <div class="delete-src"><a tabindex="' . ($df['userId'] * 10 + 6) . '" href="javascript:getUser(' . $df['userId'] . ');" ><i>&nbsp;</i></a></div>
                    <div class="check-top"><a tabindex="' . ($df['userId'] * 10 + 5) . '"  href="javascript:editUser(' . $df['userId'] . ');" class="edit-user"><i>&nbsp;</i></a></div>
                </div>';


                        