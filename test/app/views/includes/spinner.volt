{# spinner start #}
<style>

    .loader,
    .loader:before,
    .loader:after {
        background: #0088ff;
        -webkit-animation: load1 1s infinite ease-in-out;
        animation: load1 1s infinite ease-in-out;
        width: 1em;
        height: 4em;
    }
    .loader {
        color: #0088ff;
        text-indent: -9999em;
        margin: auto;
        position: relative;
        font-size: 11px;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
    }
    .loader:before,
    .loader:after {
        position: absolute;
        top: 0;
        content: '';
    }
    .loader:before {
        left: -1.5em;
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
    }
    .loader:after {
        left: 1.5em;
    }
    @-webkit-keyframes load1 {
        0%,
        80%,
        100% {
            box-shadow: 0 0;
            height: 4em;
        }
        40% {
            box-shadow: 0 -2em;
            height: 5em;
        }
    }
    @keyframes load1 {
        0%,
        80%,
        100% {
            box-shadow: 0 0;
            height: 4em;
        }
        40% {
            box-shadow: 0 -2em;
            height: 5em;
        }
    }

    #spinner-base{
        position: fixed;
        top: 0; bottom: 0; left: 0; right: 0;
        background-color: rgba(0, 0, 0, .5);
        /*z-index: 1032;*/
        z-index: 10000;
        font-size: 1rem;
    }

    .cotnent-body{
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .modal-window{
        width: 50vw;
        background-color: #fff;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        box-shadow: #212729;
        padding: 2rem;
    }

    .modal-window.loading,
    .modal-window.success {
        /*height: 50vh;*/
    }

    .loader-wrap{
        height: 4em;
    }

    .error-caption{
        height: 8rem;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        overflow: scroll;
        padding: 0.4rem;
    }

    .p-relative{
        position: relative;
        height: 100%;
    }

    .vh-center-loading{
        position: relative;
        top: 15px;
    }

    .vh-center-success{
        position: relative;
        top: calc(50% - 20px);
    }

</style>
<link rel="stylesheet" type="text/css" href="/assets/css/IconAnimation.css">
<div id="spinner-base" style="display: none;">
    <div class="cotnent-body">

        {# loading animation #}
        <div class="modal-window loading text-center" id="id_icon_loading">
            <div class="p-relative">
                <div class="vh-center-loading">
                    {# .loader-wrap アニメーションに引っ張られるのでheight固定 #}
                    <div class="loader-wrap">
                        <div class="loader"></div>
                    </div>
                    <div class="highlight-text text-disable mt-2">しばらくおまちください</div>
                </div>
            </div>
        </div>

        {# success animation #}
        <div class="modal-window success text-center" id="id_icon_success" style="display: none;">
            <div class="p-relative">
                <div class="vh-center-success">
                    <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                        <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                        <span class="swal2-success-line-tip"></span>
                        <span class="swal2-success-line-long"></span>
                        <div class="swal2-success-ring"></div>
                        <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                        <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                    </div>
                    <div class="caption-result text-center mt-4 highlight-text text-disable">成功</div>
                </div>
            </div>
        </div>

        {# error animation #}
        <div class="modal-window text-center" id="id_icon_error" style="display: none;">
            <div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;">
                <span class="swal2-x-mark">
                    <span class="swal2-x-mark-line-left"></span>
                    <span class="swal2-x-mark-line-right"></span>
                </span>
            </div>
            <div class="caption-result text-center mt-4 highlight-text text-disable">エラー！</div>
            {# error caption #}
            <div class="error-caption text-left text-danger mt-4" id="id-txt-fail"></div>
            {# 閉じるボタン #}
            <button id="id-btn-loader-close" class="btn btn-primary mt-4">閉じる</button>
        </div>

    </div>
</div>

<script>
$(function () {

    $(document)
        .on("ajaxStart", function() {
            toggleLoadingIcon();
            $("#spinner-base").show();
        })
        .on("ajaxStop", function(e, status, message, callback) {
            setTimeout(
                function () {
                    if( status === false ){
                        toggleErrorIcon();
                        if( Array.isArray(message) ) message = function(message){var invalMsg=""; $.each(message, function(idx,elm){invalMsg+=(elm+"<br />")}); return invalMsg;}(message);
                        $("#id-txt-fail").html( message );
                    }else{

                        {# 通知メッセージがない場合はシームレス #}
                        if( !message ){
                            if( callback ){
                                callback();
                                return;
                            }
                            $("#spinner-base").hide();
                            return;
                        }

                        {# キャプションをセットする #}
                        $("#id_icon_success .caption-result").text(message);

                        {# 成功を通知する #}
                        toggleSuccessIcon();

                        {# 通知する後のアクション #}
                        setTimeout(function () {
                            if( callback ){
                                callback();
                                return;
                            }
                            $("#spinner-base").hide();
                        }, 1000);
                    }
                }
                ,0);
        });

    $("#id-btn-loader-close").on("click", function () {
        $("#spinner-base").hide();
    });

    function toggleLoadingIcon(){
        $("#id_icon_loading").show();
        $("#id_icon_success").hide();
        $("#id_icon_error").hide();
    }

    function toggleErrorIcon(){
        $("#id_icon_loading").hide();
        $("#id_icon_error").show();
    }

    function toggleSuccessIcon(){
        $("#id_icon_loading").hide();
        $("#id_icon_success").show();
    }

});
</script>
{# spinner end #}