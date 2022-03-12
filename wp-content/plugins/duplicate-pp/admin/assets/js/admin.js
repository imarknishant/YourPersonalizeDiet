;(function($){

    $(document).ready(function(){
        $("body").on("click", "#dpp-notice", function(){
            SetCookie( "dpp-notice", "1", 60*60*24*30 );
        });
    });

})(jQuery);

function SetCookie(cname, cvalue, experyInSeconds) {
    const d = new Date();
    d.setTime(d.getTime() + 1000 * experyInSeconds );
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}