{% extends "layout/template_in_service.volt" %}

{% block title %}顧客一覧{% endblock %}
{% block css_include %}{% endblock %}
{% block content_body %}

<style>
    .content_root table tr td:nth-of-type(1){width: 40%;}
    .content_root table tr td:nth-of-type(2){width: 20%;}
    .content_root table tr td:nth-of-type(3){width: 20%;}
    .content_root table tr td:nth-of-type(4){width: 20%;}
    .content_root td{
        font-size: 1rem;
        vertical-align: middle;
    }
</style>

<div class="content_root">
    <h1 class="title row">
        <div class="col-8 flex_box">
            顧客一覧
        </div>
        <div class="col-4 flex_box flex_right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">新規登録</button>
        </div>
    </h1>

    <table class="table-hover table">
        <thead>
        <th>顧客名</th>
        <th>登録日</th>
        <th>更新日</th>
        <th>編集</th>
        </thead>
        <tbody>
            {% for customer in customers %}
                <tr>
                    <td class="cell">{{ customer.name }}</td>
                    <td class="cell">{{ date('Y年m月d日', customer.created | strtotime) }}</td>
                    <td class="cell">{{ date('Y年m月d日', customer.updated | strtotime) }}</td>
                    <td class="cell"><button class="btn btn-primary cls-site-update"
                                             data-toggle="modal" data-target="#staticBackdrop" data-customer-id="{{ customer.id }}">編集する</button></td>
                </tr>
           {% endfor %}
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
                    <h5 class="modal-title" id="staticBackdropLabel">顧客を登録します</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ form('/customers/update', 'method': 'post', 'id': 'id-form-new-site') }}
                    {{ form.render('id') }}

                    <ul>
                        <li class="form-element-wrap">
                            {{ form.label('name', ['class' : 'form-label']) }}
                            {{ form.render('name') }}
                            {{ form.messages('name') }}
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

            var a_action = $("#id-form-new-site").attr("action");
            var a_method = $("#id-form-new-site").attr("method");

            $.ajax({
                url: a_action,
                method: a_method,
                global: false,
                data: $("#id-form-new-site").serialize(),
                beforeSend: $(document).triggerHandler('ajaxStart')
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
            $("input[name='name']").val("");

            const customer_id = $(e.relatedTarget).data("customer-id");
            {# 編集モードセットアップ #}
            if( customer_id ){
                const customer_id = $(e.relatedTarget).data("customer-id");
                const customer_name = $(e.relatedTarget).parents("tr").find("td:eq(0)").text();

                $("input[name='id']").val(customer_id);
                $("input[name='name']").val(customer_name);

                $(".modal-title").text("顧客を編集します");
            }
            {# 追加モードセットアップ #}
            else{
                $(".modal-title").text("顧客を追加します");
            }

        })

    })
</script>
{% endblock %}