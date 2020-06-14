{% extends "layout/template_in_service.volt" %}

{% block title %}現場一覧{% endblock %}
{% block css_include %}
    <link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>
    .content_root table tr td:nth-of-type(1){width: 25%;}
    .content_root table tr td:nth-of-type(2){width: 25%;}
    .content_root table tr td:nth-of-type(3){width: 40px;}
    .content_root table tr td:nth-of-type(4){width: 40px;}
    .content_root table tr td:nth-of-type(5){width: 40px;}
    .content_root table tr td:nth-of-type(6){width: 40px;}
    .content_root td{
        font-size: 1rem;
        vertical-align: middle;
    }
</style>
<div class="content_root">
<h1 class="title">現場一覧</h1>
<table class="table-hover table">
    <thead>
    <th>現場名</th>
    <th>顧客名</th>
    <th>始業時間</th>
    <th>就業時間</th>
    <th>休憩開始時間</th>
    <th>休憩終了時間</th>
    </thead>
    <tbody>
        <?php foreach($site_info as $site): ?>
            <tr>
                <td class="cell">{{ site.sitename }}</td>
                <td class="cell">{{ site.customername }}</td>
                <td class="cell">{{ date('H:i', site.time_from | strtotime) }}</td>
                <td class="cell">{{ date('H:i', site.time_to | strtotime) }}</td>
                <td class="cell">{{ date('H:i', site.breaktime_from | strtotime) }}</td>
                <td class="cell">{{ date('H:i', site.breaktime_to | strtotime) }}</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>
{% endblock %}