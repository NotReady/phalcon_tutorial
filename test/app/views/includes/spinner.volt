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
        background-color: rgba(255, 255, 255, .8);
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

    .loader-wrap{
        height: 4em;
    }

    .loader-fix-container{
        height: 4em;
    }

</style>
<div id="spinner-base" style="display: none;">
    <div class="cotnent-body">
        <div id="id-loader-fix-content" class="mb-4">
            <div class="loader-wrap">
                <div class="loader"></div>
            </div>
            <div class="text-center mt-4 highlight-text text-disable">しばらくおまちください</div>
        </div>
        <div class="loader-fix-container">
            <div id="id-loader-fail-content" class="text-center" style="display: none;">
                <div id="id-txt-fail" class="text-center text-danger mb-2"></div>
                <button id="id-btn-loader-close" class="btn btn-primary">閉じる</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {

    $(document)
        .on("ajaxStart", function() {
            $("#spinner-base").show();
            $("#id-loader-fail-content").hide();
        })
        .on("ajaxStop", function(e, status, message) {
            setTimeout(
                function () {
                    if( status === false ){
                        if( Array.isArray(message) ) message = function(message){var invalMsg=""; $.each(message, function(idx,elm){invalMsg+=(elm+"<br />")}); return invalMsg;}(message);
                        $("#id-txt-fail").html( message );
                        $("#id-loader-fail-content").show();
                    }else{
                        $("#spinner-base").hide();
                    }
                }
                ,0);
        });

    $("#id-btn-loader-close").on("click", function () {
        $("#spinner-base").hide();
    });
});
</script>
{# spinner end #}