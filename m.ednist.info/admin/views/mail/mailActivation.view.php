<?php



$message = "<h1>Уважаемый пользователь!</h1><h4>Для активации учетной записи, перейдите, пожалуйста, по этой ссылке:</h4><a href='" . UserEmail::emailGenerateConfirm($db_salt) . "'><p>" . UserEmail::emailGenerateConfirm($db_salt) . "</p></a><h3>Искренне Ваш, ednist.info</h3><hr><hr><p>Это сообщение сгенерировано автоматически, пожалуйста, не отвечайте на него.</p>";



?>

