{% extends "layout/template_in_service.volt" %}

{% block title %}従業員一覧{% endblock %}
{% block css_include %}{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}
<style>
.table-employee td:nth-of-type(1){width: 8%;}
.table-employee td:nth-of-type(2){width: 8%;}
.table-employee td:nth-of-type(3){width: 8%;}
.table-employee td:nth-of-type(4){width: 19%;}
.table-employee td:nth-of-type(5){width: 19%;}
.table-employee td:nth-of-type(6){width: 19%;}
.table-employee td:nth-of-type(7){width: 19%;}
</style>

<div class="content_root">

    <h1 class="title row">
        <div class="col-8 flex_box">
            <span>従業員一覧</span>
        </div>
        <div class="col-4 flex_box flex_right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">新規登録</button>
        </div>

    </h1>

    <table class="table-hover table table-employee">
        <thead>
        <th>状態</th>
        <th>雇用種別</th>
        <th>社員番号</th>
        <th>名前</th>
        <th>入社日</th>
        <th>最終更新日</th>
        <th>労務編集</th>
        </thead>
        <tbody>
            <?php foreach($employee_info as $employee): ?>
                <tr>
                    {% if employee.employee_status === 'active' %}
                        {% set status = '<span class="badge-success">雇用中</span>' %}
                    {% elseif employee.employee_status === 'dismiss' %}
                        {% set status = '<span class="badge-disable">解雇済</span>' %}
                    {% elseif employee.employee_status === 'suspend' %}
                        {% set status = '<span class="badge-disable">休職中</span>' %}
                    {% endif %}
                    <td>{{ status }}</td>
                    {% if employee.employee_type === 'pro' %}
                        {% set type = '<span class="badge-info">社員</span>' %}
                    {% elseif employee.employee_type === 'part' %}
                        {% set type = '<span class="badge-info">パート</span>' %}
                    {% endif %}
                    <td>{{ type }}</td>
                    <td>{{ employee.getEmployeeNo() }}</td>
                    <td>{{ employee.first_name }} {{ employee.last_name }}</td>
                    <td>{{ date('Y年n月d日', employee.hire_date | strtotime) }}</td>
                    <td>{{ date('Y年n月d日', employee.updated | strtotime) }}</td>
                    <td><a class="btn btn-primary" href="/employees/edit/{{ employee.id }}" role="button">確認する</a></td>
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
                    <h5 class="modal-title" id="staticBackdropLabel">社員を登録します</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ form('/employees/create', 'method': 'post', 'id': 'id-form-new-employee') }}

                        <ul>

                            <li class="form-element-wrap">
                                {{ form.label('first_name', ['class' : 'form-label']) }}
                                {{ form.render('first_name') }}
                                {{ form.messages('first_name') }}
                            </li>

                            <li class="form-element-wrap">
                                {{ form.label('last_name', ['class' : 'form-label']) }}
                                {{ form.render('last_name') }}
                                {{ form.messages('last_name') }}
                            </li>

                            <li class="form-element-wrap">
                                {{ form.label('employee_no', ['class' : 'form-label']) }}
                                {{ form.render('employee_no') }}
                                {{ form.messages('employee_no') }}
                            </li>

                            <li class="form-element-wrap">
                                {{ form.label('hire_date', ['class' : 'form-label']) }}
                                {{ form.render('hire_date') }}
                                {{ form.messages('hire_date') }}
                            </li>

                            <li class="form-element-wrap">
                                {{ form.label('employee_type', ['class' : 'form-label']) }}
                                {{ form.render('employee_type') }}
                                {{ form.messages('employee_type') }}
                            </li>

                            <li class="form-element-wrap">
                                {{ form.label('skill_id', ['class' : 'form-label']) }}
                                {{ form.render('skill_id') }}
                                {{ form.messages('skill_id') }}
                            </li>

                            <li class="form-element-wrap">
                                {{ form.label('employee_status', ['class' : 'form-label']) }}
                                {{ form.render('employee_status') }}
                                {{ form.messages('employee_status') }}
                            </li>
                        </ul>
                    {{ endform() }}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <button class="btn btn-primary" id="id-create-employee" data-method="create">登録する</button>
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

    $("#id-create-employee").on("click", function () {

        $(document).triggerHandler('ajaxStart');

        var a_action = $("#id-form-new-employee").attr("action");
        var a_method = $("#id-form-new-employee").attr("method");

        $.ajax({
            url: a_action,
            method: a_method,
            global: false,
            data: $("#id-form-new-employee").serialize(),
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
})
</script>
{% endblock %}