{% extends "layout/template_in_service.volt" %}

{% block title %}現場編集{% endblock %}
{% block css_include %}
{% endblock %}

{% block content_body %}

<style>
    .form-element-wrap{
        margin: 0 auto 15px 0;
    }

    .form-element-wrap ul{
        padding: 0;
        text-align: right;
    }

    .form-element-wrap ul li{
        display: inline-block;
        width: 100px;
        margin-left: 10px;
    }

    .v-center{
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 100%;
    }

    .tbl-hourly-charge td:nth-of-type(1){width: 30%;}
    .tbl-hourly-charge td:nth-of-type(2){width: 40%;}
    .tbl-hourly-charge td:nth-of-type(3){width: 30%;}

</style>

<div class="container">

    <h1 class="title">登録情報</h1>

    {{ form('/sites/edit/check', 'method': 'post', 'class': 'row') }}

    {{ form.render('id') }}

    <span class="col-12">
        <h2 class="subtitle">基本情報管理</h2>
    </span>

    <div class="form-element-wrap col-3">
        {{ form.label('customer_id', ['class' : 'form-label']) }}
        {{ form.render('customer_id') }}
        {{ form.messages('customer_id') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('sitename', ['class' : 'form-label']) }}
        {{ form.render('sitename') }}
        {{ form.messages('sitename') }}
    </div>

    <div class="form-element-wrap col-3">
        {#blank#}
    </div>

    <div class="form-element-wrap col-3">
        {#blank#}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('time_from', ['class' : 'form-label']) }}
        {{ form.render('time_from') }}
        {{ form.messages('time_from') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('time_to', ['class' : 'form-label']) }}
        {{ form.render('time_to') }}
        {{ form.messages('time_to') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('breaktime_from', ['class' : 'form-label']) }}
        {{ form.render('breaktime_from') }}
        {{ form.messages('breaktime_from') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('breaktime_to', ['class' : 'form-label']) }}
        {{ form.render('breaktime_to') }}
        {{ form.messages('breaktime_to') }}
    </div>

    <div class="form-element-wrap col-12 text-right mt-3">
        <ul>
            <li>{{ form.render('submit') }}</li>
        </ul>
    </div>

    {{ endform() }}

    <div class="row">

        {# header #}
        <span class="col-12">
            <h2 class="subtitle">時給管理</h2>
        </span>

        {# nav contents #}
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="id-hourly-charge-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-1-tab" data-toggle="pill" href="#v-charge-1" role="tab" aria-controls="v-charge-1" aria-selected="true" data-site-id="1" data-work-id="1">一般作業</a>
                <a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#v-charge-2" role="tab" aria-controls="v-charge-2" aria-selected="false">フォークリフト</a>
                <a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-charge-3" role="tab" aria-controls="v-charge-3" aria-selected="false">特殊作業</a>
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="id-hourly-charge-content">

                {# 現場 - 時給ボディ #}
                <div class="tab-pane fade show active" id="v-charge-1" role="tabpanel" aria-labelledby="v-pills-1-tab">
                    {# title #}
                    <h2 class="table-title">一般作業の時給一覧</h2>
                    <table class="table tbl-hourly-charge">
                        <tbody class="cls-hourly-charge-body">
                        {# ajaxでローディング #}
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="v-charge-2" role="tabpanel" aria-labelledby="v-pills-2-tab">...</div>
                <div class="tab-pane fade" id="v-charge-3" role="tabpanel" aria-labelledby="v-pills-3-tab">...</div>
            </div>
        </div>
    </div>

</div>
{% include "includes/spinner.volt" %}
{% endblock %}

{% block js_include %}
<script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script>
$(function() {

    {# タブの選択イベント #}
    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        $newTab = $(e.target); // newly activated tab
        e.relatedTarget; // previous active tab

        const siteId = $newTab.data("site-id");
        const workId = $newTab.data("work-id");
        const tabContentId = $newTab.attr("aria-controls");
        onSelectedTab(tabContentId, siteId, workId);
    });

    {# 時給の取得実装 #}
    function onSelectedTab(tabContendId, siteId, workId) {
        $.ajax({
            url: "/hourlycharges/get",
            method: "POST",
            global: false,
            data: {
                site_id: siteId,
                work_id: workId
            },
            beforeSend: $(document).triggerHandler('ajaxStart'),
        })
        .then(function (data, textStatus, jqXHR) {
            console.log(data);

            // ステータスが無い
            if( !data["result"] ){
                throw new Error("システムエラーです");
            }

            // 失敗
            if( data["result"] === "failure"){
                if( data["message"] ) {
                    throw new Error(data['message']);
                }
                throw new Error("システムエラーです");
            }

            // ステータスが無い
            if( !data["hourly_charge"] ){
                throw new Error("システムエラーです");
            }

            // tabcontentにはめる
            $(`#${tabContendId} .cls-hourly-charge-body`).empty();
            $.each(data["hourly_charge"], function (key, value) {
                $("<tr>").appendTo(`#${tabContendId} .cls-hourly-charge-body`)
                         .append($("<td>").text(value["skill_name"]))
                         .append($("<td>").append(
                             $(`<input type="number">`).addClass("form-control text-right").val(value['hourly_charge']).css({"display": "inline"}).attr("placeholder", "時給を設定してください")).append(
                                 $("<span>").text("円").addClass("ml-2")
                             )
                         )
                         .append($("<td>").append($("<button>").addClass("btn btn-primary cls-charge-handler").text("保存").attr({"data-site-id":siteId, "data-work-id": workId, "data-action-method": "update"}))
                                            .append($("<button>").addClass("btn btn-danger cls-charge-handler ml-2").text("削除").attr({"data-site-id":siteId, "data-work-id": workId, "data-action-method": "delete"}))
                         )
            });

            $(document).triggerHandler('ajaxStop', [ true ]);
        })
        .catch(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            $(document).triggerHandler('ajaxStop', [ false, jqXHR]);
        });
    }

    {# 時給の更新・削除実装 #}
    $(".cls-charge-handler").on('click', function () {
        const actionMethod = $(this).data("action-method")
        $.ajax({
            url: `/hourlycharges/${actionMethod}`,
            method: "POST",
            global: false,
            data: {
                site_id: siteId,
                work_id: workId,
                skill_id: skillId
            },
            beforeSend: $(document).triggerHandler('ajaxStart'),
        })
            .then(function (data, textStatus, jqXHR) {
                console.log(data);

                // ステータスが無い
                if( !data["result"] ){
                    throw new Error("システムエラーです");
                }

                // 失敗
                if( data["result"] === "failure"){
                    if( data["message"] ) {
                        throw new Error(data['message']);
                    }
                    throw new Error("システムエラーです");
                }

                // ステータスが無い
                if( !data["hourly_charge"] ){
                    throw new Error("システムエラーです");
                }

                // tabcontentにはめる
                $(`#${tabContendId} .cls-hourly-charge-body`).empty();
                $.each(data["hourly_charge"], function (key, value) {
                    $("<tr>").appendTo(`#${tabContendId} .cls-hourly-charge-body`)
                        .append($("<td>").text(value["skill_name"]))
                        .append($("<td>").append(
                            $(`<input type="number">`).addClass("form-control text-right").val(value['hourly_charge']).css({"display": "inline"}).attr("placeholder", "時給を設定してください")).append(
                            $("<span>").text("円").addClass("ml-2")
                            )
                        )
                        .append($("<td>").append($("<button>").addClass("btn btn-primary cls-charge-handler").text("保存").attr({"data-site-id":siteId, "data-work-id": workId, "data-action-method": "update"}))
                            .append($("<button>").addClass("btn btn-danger cls-charge-handler ml-2").text("削除").attr({"data-site-id":siteId, "data-work-id": workId, "data-action-method": "delete"}))
                        )
                });

                $(document).triggerHandler('ajaxStop', [ true ]);
            })
            .catch(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                $(document).triggerHandler('ajaxStop', [ false, jqXHR]);
            });

    });

    {# 先頭タブの時給を取得する #}
    function getHourlyChargeAtFirstView(){
        $firstTab = $("#id-hourly-charge-tab a:first-child")
        const siteId = $firstTab.data("site-id");
        const workId = $firstTab.data("work-id");
        const tabContentId = $firstTab.attr("aria-controls");
        onSelectedTab(tabContentId, siteId, workId);
    }

    getHourlyChargeAtFirstView();


});
</script>

{% endblock %}