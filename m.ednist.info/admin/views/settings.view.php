<!DOCTYPE html>
<html>
<head>

    <script src="http://assets.sortnews.net/js/jquery-1.10.1.min.js"></script>

    <script>


    </script>

</head>
<body>

<h2>
    Настройки парсинга
</h2>

<form method="post" action="">

    <p>
        <label for="parcing_time">Время обновления новостей (часов)</label>
        <input type="text" name="parcing_time" id="parcing_time" value="<?= $result[0]['settingsParceTime']; ?>"/>
    </p>

    <p>
        <input type="checkbox" value="1" name="enable_parcing"
               id="enable_parcing" <?= (1 == $result[0]['settingsEnableParcing']) ? 'checked="checked"' : ''; ?> />
        <label for="enable_parcing">вкл парсинг</label>
    </p>

    <p>
        <input type="submit" value="Сохранить"/>
    </p>

</form>


</body>
</html>