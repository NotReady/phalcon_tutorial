{% extends "layout/template_in_service.volt" %}

{% block title %}現場一覧{% endblock %}
{% block css_include %}{% endblock %}
{% block content_body %}

<style>
    .content_root table tr td:nth-of-type(1){width: 25%;}
    .content_root table tr td:nth-of-type(2){width: 25%;}
    .content_root table tr td:nth-of-type(3){width: 40px;}
    .content_root table tr td:nth-of-type(4){width: 40px;}
    .content_root table tr td:nth-of-type(5){width: 40px;}
    .content_root table tr td:nth-of-type(6){width: 40px;}
    .content_root table tr td:nth-of-type(7){width: 40px;}
    .content_root td{
        font-size: 1rem;
        vertical-align: middle;
    }
</style>

<div class="content_root">
    <h1 class="title row">
        <div class="col-8 flex_box">
            現場一覧
        </div>
        <div class="col-4 flex_box flex_right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">新規登録</button>
        </div>
    </h1>

    <table class="table-hover table">
        <thead>
        <th>現場名</th>
        <th>顧客名</th>
        <th>始業時間</th>
        <th>就業時間</th>
        <th>休憩時間</th>
        <th>編集</th>
        </thead>
        <tbody>
            <?php foreach($site_info as $site): ?>
                <tr>
                    <td class="cell">{{ site.sitename }}</td>
                    <td class="cell">{{ site.customername }}</td>
                    <td class="cell">{{ date('H:i', site.time_from | strtotime) }}</td>
                    <td class="cell">{{ date('H:i', site.time_to | strtotime) }}</td>
                    <td class="cell">{{ date('H:i', site.breaktime | strtotime) }}</td>
                    <td class="cell"><a href="/sites/edit/{{ site.site_id }}" class="btn btn-primary">編集</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    {# モーダルウインドウ #}
    <style>
        form li:not(:last-child){
            margin-bottom: 0.8rem;
        }
    </style>

    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">現場を登録します</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ form('/sites/create', 'method': 'post', 'id': 'id-form-new-site') }}
                    {{ form.render('id') }}

                    <ul>
                        <li class="form-element-wrap">
                            {{ form.label('customer_id', ['class' : 'form-label']) }}
                            {{ form.render('customer_id') }}
                            {{ form.messages('customer_id') }}
                        </li>

                        <li class="form-element-wrap">
                            {{ form.label('sitename', ['class' : 'form-label']) }}
                            {{ form.render('sitename') }}
                            {{ form.messages('sitename') }}
                        </li>

                        <li class="form-element-wrap">
                            {{ form.label('business_type', ['class' : 'form-label']) }}
                            {{ form.render('business_type') }}
                            {{ form.messages('business_type') }}
                        </li>

                        <li class="form-element-wrap">
                            <div class="row">
                                <div class="col-6">
                                    {{ form.label('time_from', ['class' : 'form-label']) }}
                                    {{ form.render('time_from') }}
                                    {{ form.messages('time_from') }}
                                </div>
                                <div class="col-6">
                                    {{ form.label('time_to', ['class' : 'form-label']) }}
                                    {{ form.render('time_to') }}
                                    {{ form.messages('time_to') }}
                                </div>
                            </div>
                        </li>

                        <li class="form-element-wrap">
                            <div class="row">
                                <div class="col-6">
                                    {{ form.label('breaktime', ['class' : 'form-label']) }}
                                    {{ form.render('breaktime') }}
                                    {{ form.messages('breaktime') }}
                                </div>
                            </div>
                        </li>

                        <li class="form-element-wrap">
                            {{ form.label('monthly_bill_amount', ['class' : 'form-label']) }}
                            {{ form.render('monthly_bill_amount') }}
                            {{ form.messages('monthly_bill_amount') }}
                        </li>

                    </ul>
                    {{ endform() }}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <button class="btn btn-primary" id="id-create-site" data-method="create">登録する</button>
                </div>
            </div>
        </div>
    </div>
</div>
{% include "includes/spinner.volt" %}
{% endblock %}
{% block js_include %}
<script>
    $(function () {

        $("#id-create-site").on("click", function () {

            $(document).triggerHandler('ajaxStart');

            var a_action = $("#id-form-new-site").attr("action");
            var a_method = $("#id-form-new-site").attr("method");

            $.ajax({
                url: a_action,
                method: a_method,
                global: false,
                data: $("#id-form-new-site").serialize(),
            })
            .then(function (data, textStatus, jqXHR) {
                console.log(data);
                if( data['result'] ){
                    if( data['result'] == "success" ) {
                        location.reload();
                    }
                    if( data['result'] == "failure" ) {
                        $(document).triggerHandler('ajaxStop', [ false, data['messages']]);
                    }
                }else{
                    $(document).triggerHandler('ajaxStop', [ false, "システムエラーが発生しました。"]);
                }
            })
            .catch(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                $(document).triggerHandler('ajaxStop', [ false, "システムエラーが発生しました。"]);
            })
        });

        {# モーダルの表示イベント #}
        $("#staticBackdrop").on("show.bs.modal", function (e) {

            {# フォームのクリア #}
            $("input[name='id']").val("");
            $("select[name='customer_id']").val("");
            $("input[name='sitename']").val("");
            $("select[name='business_type']").val("");
            $("input[name='time_from']").val("");
            $("input[name='time_to']").val("");
            $("input[name='breaktime']").val("");
            $("input[name='monthly_bill_amount']").val("");

            const site_id = $(e.relatedTarget).data("site-id");
            {# todo: 編集モードセットアップ #}
            if( site_id ){
                const customer_id = $(e.relatedTarget).data("customer-id");
                const site_name = $(e.relatedTarget).parents("tr").find("td:eq(0)").text();
                const time_from = $(e.relatedTarget).parents("tr").find("td:eq(2)").text();
                const time_to = $(e.relatedTarget).parents("tr").find("td:eq(3)").text();
                const breaktime = $(e.relatedTarget).parents("tr").find("td:eq(4)").text();

                $("input[name='id']").val(site_id);
                $("select[name='customer_id']").val(customer_id);
                $("input[name='sitename']").val(site_name);
                $("input[name='time_from']").val(time_from);
                $("input[name='time_to']").val(time_to);
                $("input[name='breaktime']").val(breaktime);

                $(".modal-title").text("現場を編集します");
            }
            {# 追加モードセットアップ #}
            else{
                $(".modal-title").text("現場を追加します");
            }

        })

    })
</script>
{% endblock %}