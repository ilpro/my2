<script type="application/x-javascript">

    function showMessage(message, type) {

        if (message == '') {

            $('#message').html("");

        } else {

            if (type == 'ok') {

                $('#message').html("<i class='notice-ok'></i><p>" + message + "</p>");

            } else {

                $('#message').html("<i class='notice-err'></i><p>" + message + "</p>");

            }

        }

    }

    $(function () {

        <? if("reset"==$act):?>

        <? if(isset($_GET['hash']) and $_GET['hash']!=''):?>

        $(window).mkLogin({restore: 1, oldSalt: '<?=$_GET['hash']?>'});

        <? else:?>

        $(window).mkLogin({reset: 1});

        <? endif;?>

        <? else:?>

        $(window).mkLogin();

        <? endif;?>

    });//Конец ready

</script>


<div class="login box" id="auth">

    <?   if ("error" == $act) {

        //$mess = "<i class=\"notice-err\"></i>";

        //$mess .= "<p>Неверный email или пароль</p>";

        $btnSubmit = "Войти";

        $errClass = "-err";

    } elseif ("reset" == $act) {

        if (isset($_GET['hash']) and $_GET['hash'] != '') {

            $mess = "<i class=\"notice-ok\"></i><p>Введите Ваш email и новый пароль для восстановления Вашего  аккаунта</p>";

        }

        $btnSubmit = "Сбросить пароль";

        $errClass = "";

    } elseif ("change" == $act) {

        $mess = "";

        $btnSubmit = "Поменять пароль";

        $errClass = "";

    } elseif ("confirm" == $act) {

        if (isset($_GET['hash'])) {

            $users = new User;

            $result = $users->activate(Clear::dataS($_GET['hash']));

            if (!$result)

                $mess = "<i class=\"notice-ok\"></i><p>Ваш аккаунт активирован. Введите логин/пароль для входа.</p>";

        } else

            $mess = "";

        $btnSubmit = "Войти";

        $errClass = "";

    } else {

        $mess = "";

        $btnSubmit = "Войти";

        $errClass = "";

    }

    ?>

    <small class="err-msg" id="message">

        <?// $mess; ?>

    </small>
    <div class="clear"></div>

    <h1><span>SORT</span>NEWS</h1>



    <? if ($act != "change"): ?>

        <span class="box-icon"><i class="login-ico log<?= $errClass ?>"></i></span>

        <input type="text" id="email" placeholder="Email"/>

    <? endif; ?>

    <? if ($act != "reset" || ($act == "reset" and isset($_GET['hash']))): ?>

        <span class="box-icon"><i class="pass-ico pass<?= $errClass ?>"></i></span>

        <input type="password" id="pass" placeholder="Пароль"/>

        <input type="text" id="passShow" placeholder="Пароль"/>

    <? endif; ?>

    <br/>

    <? if ($act != "reset" || ($act == "reset" and isset($_GET['hash']))): ?>

        <div class="checkLight">

            <input id='showPass' type="checkbox"/>

            <label for="showPass"><span></span>

                Показать пароль

            </label></div>

        <div class="checkLight">

            <input id='remember' type="checkbox"/>

            <label for="remember"><span></span>

                Запомнить

            </label></div>

    <? endif; ?>

    <input type="submit" id='login' value="<?= $btnSubmit; ?>"/>

    <form method="POST"></form>

    <div class="clear"></div>

    <? if ($act != 'reset'): ?>

        <small><p><a href="<?= $ini['url.admin'] ?>account/reset">Забыли пароль ?</a></p></small>

    <? endif; ?>

</div>