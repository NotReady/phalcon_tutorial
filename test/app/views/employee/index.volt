{% extends "layout/template_in_service.volt" %}

{% block title %}従業員一覧{% endblock %}
{% block css_include %}
<link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}
<style>
.table-employee tr td:nth-of-type(1){width: 40%;}
.table-employee tr td:nth-of-type(2){width: 15%;}
.table-employee tr td:nth-of-type(3){width: 15%;}
.table-employee tr td:nth-of-type(4){width: 15%;}
.table-employee tr td:nth-of-type(5){width: 15%;}
</style>

<div class="content_root">
<h1 class="title">従業員一覧</h1>
<table class="table-hover table table-employee">
    <thead>
    <th>名前</th>
    <th>最新出勤日</th>
    <th>登録日</th>
    <th>更新日</th>
    <th>労務編集</th>
    </thead>
    <tbody>
        <?php foreach($employee_info as $employee): ?>
            <tr>
                <td>{{ employee.first_name }} {{ employee.last_name }}</td>
                <td>{{ employee.last_input_day }}</td>
                <td>{{ date('Y年n月d日', employee.created | strtotime) }}</td>
                <td>{{ date('Y年n月d日', employee.updated | strtotime) }}</td>
                <td><a class="btn btn-outline-primary" href="/employees/edit/{{ employee.employee_id }}" role="button">移動する</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

{% endblock %}