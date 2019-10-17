{% extends "layout/template.volt" %}

{% block title %}従業員一覧{% endblock %}
{% block css_include %}
<link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}
<style>
.content_root table tr td:nth-of-type(1){width: 40px;}
.content_root table tr td:nth-of-type(2){width: 40px;}
.content_root table tr td:nth-of-type(3){width: 50%;}
.content_root table tr td:nth-of-type(4){width: 15%;}
.content_root table tr td:nth-of-type(5){width: 15%;}
.content_root table tr td:nth-of-type(6){width: 15%;}
</style>

<div class="content_root">
<h3>従業員一覧</h3>
<table class="table-hover table">
    <thead>
    <th>勤怠入力</th>
    <th>労務編集</th>
    <th>名前</th>
    <th>最新出勤日</th>
    <th>登録日</th>
    <th>更新日</th>
    </thead>
    <tbody>
        <?php foreach($employee_info as $employee): ?>
            <tr>
                <td class="cell"><a class="btn btn-outline-primary" href="/report/{{ employee.employee_id }}/2019/05" role="button">勤怠入力</a></td>
                <td class="cell"><a class="btn btn-outline-primary" href="/employees/edit/{{ employee.employee_id }}" role="button">労務編集</a></td>
                <td class="cell">{{ employee.first_name }} {{ employee.last_name }}</td>
                <td class="cell">{{ employee.last_input_day }}</td>
                <td class="cell">{{ date('Y-m-d', employee.created | strtotime) }}</td>
                <td class="cell">{{ date('Y-m-d', employee.updated | strtotime) }}</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

{% endblock %}