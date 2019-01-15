function baseName(str) {

    var base = new String(str).substring(str.lastIndexOf('/') + 1);

    return base;

}

function showMessage(message, type) {

    if (message == '') {

        $('#condition').mkCondition({action: 'fade'});

    } else {

        if (type == 'ok') {

            $('#condition').mkCondition({
                condition: 'tooltip',
                text: message,
                action: 'fade',
                color: 'green',
                fadeout: 2500
            });

        } else {

            $('#condition').mkCondition({condition: 'tooltip', text: message, action: 'show', color: 'red'});

        }

    }

}

function hideMessage() {
    showMessage('', '')
}

function getSaltedHash(password, salt, iterationCount) {

    var saltedHash = password;

    if (iterationCount < 1) iterationCount = 1;

    for (var i = 0; i < iterationCount; i++)

        saltedHash = hex_md5(salt + saltedHash);

    return saltedHash;

}

function validateEmail(email) { //����� ����� �� ���������?

    var re = /\S+@\S+\.\S+/;

    return re.test(email);

};