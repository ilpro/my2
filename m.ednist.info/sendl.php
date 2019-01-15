
<?

if ($_POST['submit'])
{

    $to      = $_POST['to'];
    $subject = $_POST['subj'];
    $message = $_POST['msg'];

    // Заголовки сообщения, в них определяется кодировка сообщения, поля From, To и т.д.
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=windows-1251\r\n";
    $headers .= "To: $to\r\n";
    $headers .= "From: Имя отправителя <postmaster@domain.tld>";

//  mail ($to, $subject, $message, $headers);

    require_once "modules/SmtpMail.class.php";
    $out = MailSmtp ($to, $subject, $message, $headers);
}

?>

<form action="" method="post">
  <pre>
    To:   <input type="text" name="to">
    Subj: <input type="text" name="subj">
    Msg:  <input type="text" name="msg">
    <input type="submit" value="Send mail!" name="submit">
  </pre>
</form>