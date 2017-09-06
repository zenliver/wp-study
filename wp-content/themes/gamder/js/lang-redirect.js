// 根据浏览器语言自动跳转到对应语言版本
function userLangRedirect() {
    var userLang = navigator.language?navigator.language:navigator.browserLanguage;
    var pageUrl = window.location.href;
    console.log(userLang);
    if (userLang.indexOf("es") >= 0 && pageUrl.indexOf("?lang=spn") < 0) {
        window.location.href = pageUrl+"?lang=spn";
    } else {
        // window.location.href = "https://www.baidu.com";
    }
}
userLangRedirect();
