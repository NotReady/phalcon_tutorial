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

    .errorMessage{
        color: red;
    }

    .cls-tabcontent-table td:nth-of-type(1){width: 30%;}
    .cls-tabcontent-table td:nth-of-type(2){width: 40%;}
    .cls-tabcontent-table td:nth-of-type(3){width: 30%;}

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
            <h2 class="subtitle">請求と時給の管理</h2>
        </span>

        {# nav contents #}
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="id-hourly-charge-tab" role="tablist" aria-orientation="vertical">
                {% set setActive = true %}
                {% for worktype in work_types %}
                <a class="nav-link {% if setActive is true %}active{% set setActive = false %}{% endif %}" id="v-pills-{{ worktype.worktype_id }}-tab" data-toggle="pill" href="#v-charge-{{ worktype.worktype_id }}"
                   role="tab" aria-controls="v-charge-{{ worktype.worktype_id }}" aria-selected="true" data-site-id="{{ worktype.site_id }}"
                   data-work-id="{{ worktype.worktype_id }}">{{ worktype.name }}</a>
                {% endfor %}
                <a class="nav-link {% if setActive is true %}active{% set setActive = false %}{% endif %}" id="v-pills-add-tab" data-toggle="pill" href="#v-charge-add"
                   role="tab" aria-controls="v-charge-add" aria-selected="true">作業を追加する</a>
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="id-hourly-charge-content">
                {% set setActive = true %}
                {% for worktype in work_types %}
                    {# 現場 - 時給ボディ #}
                    <div class="tab-pane fade {% if setActive is true %}show active{% set setActive = false %}{% endif %}" id="v-charge-{{ worktype.worktype_id }}" role="tabpanel" aria-labelledby="v-pills-{{ worktype.worktype_id }}-tab">
                        <input type="hidden" name="site-id" value="{{ worktype.site_id }}">
                        <input type="hidden" name="worktype-id" value="{{ worktype.worktype_id }}">
                        {# 請求 #}
                        <h2 class="table-title">{{ worktype.name }}の請求単価</h2>
                        <table class="table tbl-hourly-bill cls-tabcontent-table">
                            <tbody class="cls-hourly-bill-body">
                            {# ajaxでローディング #}
                            </tbody>
                        </table>
                        {# 時給 #}
                        <h2 class="table-title">{{ worktype.name }}の時給一覧</h2>
                        <table class="table tbl-hourly-charge cls-tabcontent-table">
                            <tbody class="cls-hourly-charge-body">
                            {# ajaxでローディング #}
                            </tbody>
                        </table>
                    </div>
                {% endfor %}

                <div class="tab-pane fade {% if setActive is true %}show active{% set setActive = false %}{% endif %}" id="v-charge-add" role="tabpanel" aria-labelledby="v-pills-add-tab">
                    {# title #}
                    <div class="row">
                        <div class="col-12">
                            <h2 class="table-title">作業を追加します</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <select name="add-work-type" class="form-control">
                                <option value="">作業を選択してください</option>
                                {% for work in add_work_types %}
                                <option value="{{ work.worktype_id }}">{{ work.worktype_name }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-primary" id="id-btn-add-worktype">追加する</button>
                        </div>
                    </div>
                </div>

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

            // 時給テーブルを構築
            $(`#${tabContendId} .cls-hourly-charge-body`).empty();
            $.each(data["hourly_charge"], function (key, value) {
                $("<tr>").appendTo(`#${tabContendId} .cls-hourly-charge-body`)
                         .append($("<td>").text(value["skill_name"]))
                         .append($("<td>").append(
                             $(`<input type="number">`).addClass("form-control text-right").val(value['hourly_charge']).css({"display": "inline"}).attr({"placeholder": "時給を設定してください", "name": "charge"})).append(
                                 $("<span>").text("円").addClass("ml-2")
                             )
                         )
                         .append($("<td>").append($("<button>").addClass("btn btn-primary cls-charge-handler").text("保存").attr({"data-skill-id": value["skill_id"], "data-action-method": "update"}))
                                            .append($("<button>").addClass("btn btn-danger cls-charge-handler ml-2").text("削除").attr({"data-skill-id": value["skill_id"],"data-action-method": "delete"}))
                         )
            });

            // 請求テーブルを構築する
            $(`#${tabContendId} .cls-hourly-bill-body`).empty();
            $("<tr>").appendTo(`#${tabContendId} .cls-hourly-bill-body`)
                .append($("<td>"))
                .append($("<td>").append(
                    $(`<input type="number">`).addClass("form-control text-right").val(data["hourly_bill"]).css({"display": "inline"}).attr({"placeholder": "時間単価を設定してください", "name": "bill"})).append(
                    $("<span>").text("円").addClass("ml-2")
                    )
                )
                .append($("<td>").append($("<button>").addClass("btn btn-primary cls-bill-handler").text("保存").attr({"data-action-method": "update"}))
                .append($("<button>").addClass("btn btn-danger cls-bill-handler ml-2").text("削除").attr({"data-action-method": "delete"})));

            $(document).triggerHandler('ajaxStop', [ true ]);
        })
        .catch(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            $(document).triggerHandler('ajaxStop', [ false, jqXHR]);
        });
    }

    {# 時給の更新・削除実装 #}
    $(document).on("click", ".cls-charge-handler", function () {
        const actionMethod = $(this).data("action-method");
        const skillId = $(this).data("skill-id");
        const siteId = $(this).parents(".tab-pane").find("input[name='site-id']").val();
        const workId = $(this).parents(".tab-pane").find("input[name='worktype-id']").val();
        const charge = $(this).parents("tr").find("input[name='charge']").val();

        $.ajax({
            url: `/hourlycharges/${actionMethod}`,
            method: "POST",
            global: false,
            data: {
                site_id: siteId,
                work_id: workId,
                skill_id: skillId,
                charge: charge
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

                $(document).triggerHandler('ajaxStop', [ true, actionMethod == "update" ? "保存しました" : "削除しました", ()=>{location.reload();}]);

            })
            .catch(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                $(document).triggerHandler('ajaxStop', [ false, jqXHR]);
            });

    });

    {# 請求単価の更新・削除実装 #}
    $(document).on("click", ".cls-bill-handler", function () {
        const actionMethod = $(this).data("action-method");
        const siteId = $(this).parents(".tab-pane").find("input[name='site-id']").val();
        const workId = $(this).parents(".tab-pane").find("input[name='worktype-id']").val();
        const bill = $(this).parents("tr").find("input[name='bill']").val();

        $.ajax({
            url: `/hourlybill/${actionMethod}`,
            method: "POST",
            global: false,
            data: {
                site_id: siteId,
                work_id: workId,
                bill: bill
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

                $(document).triggerHandler('ajaxStop', [ true, actionMethod == "update" ? "保存しました" : "削除しました", ()=>{location.reload();}]);

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

    {# 現場の追加 #}
    $(document).on("click", "#id-btn-add-worktype", function () {
       const worktype_id= $(this).parents(".row").find("select[name='add-work-type']").val();
       $.ajax({
           url: "/sites/associate",
           method: "post",
           global: false,
           data: {
               site_id: {{ site_id }},
               work_id: worktype_id,
           },
           beforeSend: $(document).triggerHandler('ajaxStart')
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
           location.reload();
       })
       .catch(function (jqXHR, textStatus, errorThrown) {
           console.log(jqXHR);
           $(document).triggerHandler('ajaxStop', [ false, jqXHR]);
       });
    });

});
</script>

{% endblock %}