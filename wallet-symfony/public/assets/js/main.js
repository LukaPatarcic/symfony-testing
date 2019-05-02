var pathname = window.location.pathname;
var match = /\/user\/dashboard\/((\w)*)/.exec(pathname);
if(match[1] == '') {
    $('#home').addClass('active');
} else {
    $('#'+match[1]).addClass('active');

}