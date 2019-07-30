grecaptcha.ready(function() {
    console.log("RECAPTCHA LOADED");
});
function validateCaptcha(callback) {
    grecaptcha.execute('6LdeNbAUAAAAAHooW_a98lAfARf1alSBCKVVmexn', {action:'validate_captcha'}).then(function(token) {
        callback(token);
    });
}