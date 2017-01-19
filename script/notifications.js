(function (a) {

setInterval(pingServer, 60000);
pingServer();

function pingServer () {
    var goToLogin = function () {
        console.log('Going to logout');
        window.location.href = 'logout.php';
    };
    var req = new Request.JSON({
        url: 'functions/events.php',
        secure: true,
        onSuccess: function (data) {
            if (!data.connected)
                goToLogin();
        },
        onError: goToLogin,
        onFailure: goToLogin
    });
    req.get();
}

})(adminifier);
