{% extends "layout/template.volt" %}

{% block title %}従業員編集{% endblock %}
{% block css_include %}
<link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
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

    .errorMessage{
        color: lightcoral;
    }

    table.loans tr th:nth-of-type(1){width: 25%;}
    table.loans tr th:nth-of-type(2){width: 15%;}
    table.loans tr th:nth-of-type(3){width: 15%;}
    table.loans tr th:nth-of-type(4){width: 45%;}

    table.loans tr td:nth-of-type(2),
    table.loans tr td:nth-of-type(3)
    {text-align: right;}

    table.loans tr td:nth-of-type(4)
    {text-align: left;}

</style>

<div class="content_root">

    <p class="border border-secondary rounded btn-like">登録情報</p>

    {{ form('/employees/edit/check', 'method': 'post') }}

    {{ form.render('id') }}

    <div class="form-element-wrap">
        {{ form.label('first_name') }}
        {{ form.render('first_name') }}
        {{ form.messages('first_name') }}
    </div>

    <div class="form-element-wrap">
        {{ form.label('last_name') }}
        {{ form.render('last_name') }}
        {{ form.messages('last_name') }}
    </div>

    <div class="form-element-wrap">
        {{ form.label('Transportation_expenses') }}
        {{ form.render('Transportation_expenses') }}
        {{ form.messages('Transportation_expenses') }}
    </div>

    <div class="form-element-wrap">
        <ul>
            <li><input type="button" value="キャンセル" class="btn-outline-secondary form-control"></li>
            <li>{{ form.render('submit') }}</li>
        </ul>
    </div>

    {{ endform() }}

    <hr>

    <p class="border border-secondary rounded btn-like">貸付明細</p>

    <h3 style="margin-bottom: 20px;">貸付残高　{{ loansAmmount.ammount | number_format }} 円</h3>

    <table class="table-hover table table-main loans">
        <thead>
        <th>日付</th>
        <th>貸付金額</th>
        <th>返済金額</th>
        <th>コメント</th>
        </thead>
        <tbody>

        <?php foreach($loans as $loan): ?>
        <tr>
            <td>{{ date('Y年m月n日', loan.created | strtotime) }}</td>
            <td>{% if loan.io_type == 1 %}{{ loan.ammount | number_format }}円{% endif %}</td>
            <td>{% if loan.io_type == 2 %}{{ loan.ammount | number_format }}円{% endif %}</td>
            <td>{{ loan.comment }}</td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

{% endblock %}