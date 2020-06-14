{% extends "layout/template_in_service.volt" %}

{% block title %}従業員一覧{% endblock %}
{% block css_include %}
<link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>
.table-employees tr td:nth-of-type(1){width: 10%;}
.table-employees tr td:nth-of-type(2){width: 40%;}
.table-employees tr td:nth-of-type(3){width: 15%;}
.table-employees tr td:nth-of-type(4){width: 15%;}
.table-employees tr td:nth-of-type(5){width: 15%;}
.table-employees tr td:nth-of-type(6){width: 15%;}
</style>

<div class="content_root">
<h1 class="title row">
    <div class="col-6 flex_box">
        {{ year }}年{{ month }}月の勤務表入力
    </div>
    <div class="col-6 flex_box flex_right">
        <span class="highlight">
            <a href="{{ previousUrl }}" class="btn btn-outline-primary">＜ 前月</a>
            <a href="{{ nextUrl }}" class="btn btn-outline-primary">翌月 ＞</a>
        </span>
    </div>
</h1>
<table class="table-hover table table-employees">
    <thead>
    <th>給与確定</th>
    <th>名前</th>
    <th>出勤日数</th>
    <th>最終出勤日</th>
    <th>勤務表</th>
    <th>給与</th>
    </thead>
    <tbody>
    {% for employee in employees %}
        <tr>
            <td>
                {% if employee.salary_fixed is 'fixed'%}
                    <span class="badge-info">確定済</span>
                {% else %}
                    <span class="badge-alert">未確定</span>
                {% endif %}
            </td>
            <td>{{ employee.employee_name }}</td>
            <td>{{ employee.days_worked }} 日</td>
            <td>
                {% if employee.last_worked_day is not empty %}
                    {{ date('n月d日', employee.last_worked_day | strtotime) }}
                {% endif %}
            </td>
            <td><a class="btn btn-outline-primary" href="/report/{{ employee.employee_id }}/{{ year }}/{{ month }}/edit" role="button">移動する</a></td>
            <td><a class="btn btn-outline-primary" href="/salary/{{ employee.employee_id }}/{{ year }}/{{ month }}" role="button">移動する</a></td>
        </tr>
    {% endfor %}
    </tbody>
</table>

</div>

{% endblock %}