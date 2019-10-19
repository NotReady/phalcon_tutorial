{% extends "layout/template.volt" %}

{% block title %}勤怠入力{% endblock %}
{% block css_include %}
    <link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>

    .content_root table tr td:nth-of-type(n+4):nth-of-type(-n+7){width: 75px;}
    .content_root table tr td:nth-of-type(1){width: 55px;}
    .content_root table tr td:nth-of-type(2){width: 40px;}
    .content_root table tr td:nth-of-type(3){width: 150px;}
    .content_root table tr td:nth-of-type(4){width: 130px;}
    .content_root table tr td:nth-of-type(8){width: 50px;}

    .timeinput {
        width: 100%;
        box-sizing: border-box;
    }

    td.sat {background: cornflowerblue;}
    td.sun {background: lightcoral;}
    select {width: 100%;}

    .content_root td{
        font-size: 1rem;
        vertical-align: middle;
    }

</style>

<div class="content_root">
<h4>{{ "%s %sさん %d年 %d月 の勤務表" |format(employee.first_name, employee.last_name ,thisyear, thismonth) }}</h4>
    <hr>
<?php
$week = ['日','月','火','水','木','金','土'];
?>
    <table class="table-hover table">
        <thead>
        <th>日付</th>
        <th>曜日</th>
        <th>勤務先</th>
        <th>作業分類</th>
        <th>開始時間</th>
        <th>終了時間</th>
        <th>休憩時間</th>
        <th>保存</th>
        </thead>
        <tbody>

        <?php foreach($reports as $day => $report): ?>
            <tr>
                <form method="post" action="/report/save" class="asyncForm">
                <input type="hidden" name="nm_date" value="{{thisyear}}-{{day}}" />
                <input type="hidden" name="nm_employee_id" value="{{employee.id}}" />
                <td class="cell">{{day}}</td>
                <td>
                    <?php
                        echo $week[date('w',  strtotime("${thisyear}-${day}"))];
                    ?>
                </td>


                <td><select class="form-control" name="nm_site_id"; ?>">
                        <?php foreach($sites as $id => $name): ?>
                            <option value="{{id}}" <?php if($id==$report->site_id){echo 'selected';}?>>{{name}}</option>
                        <?php endforeach;?>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="nm_wtype_id"; ?>">
                        <?php foreach($wtypes as $id => $name): ?>
                        <option value="{{id}}" <?php if($id==$report->worktype_id){echo 'selected';}?>>{{name}}</option>
                        <?php endforeach;?>
                    </select>
                </td>

                {% if report is empty %}
                    <td><input class="form-control" name="nm_timefrom" class="timeinput" type="text"></td>
                    <td><input class="form-control" name="nm_timeto" class="timeinput" type="text"></td>
                    <td><input class="form-control" name="nm_breaktime" class="timeinput" type="text"></td>
                {% else %}
                    <td><input class="form-control" name="nm_timefrom" class="timeinput" type="text" value="{{date('H:i', report.time_from | strtotime)}}"></td>
                    <td><input class="form-control" name="nm_timeto" class="timeinput" type="text" value="{{date('H:i', report.time_to | strtotime)}}"></td>
                    <td><input class="form-control" name="nm_breaktime" class="timeinput" type="text" value="{{date('H:i', report.breaktime | strtotime)}}"></td>
                {% endif %}

                <td><input class="btn btn-primary btn-submit" type="submit" value="保存"></td>

                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
{% endblock %}

{% block js_include %}
<script>

    $(function(){
        $('.asyncForm').submit(function (event) {
            // ポストキャンセル
            event.preventDefault();

            const $thisForm = $(this);
            const $submit = $thisForm.find('.btn-submit');

            // 非同期ポスト実装
            $.ajax({
                url: $thisForm.attr("action"),
                type: $thisForm.attr("method"),
                data: $thisForm.serialize(),
                timeout: 1000 * 10,
                beforeSend: function(xhr, settings){
                    $submit.attr("disable", true);
                },
                complete: function(xhr, textStatus){
                    $submit.attr("disable", false);
                },
                success: function (result, textStatus, xhr) {
                    alert('保存しました。');
                },
                error: function(xhr, textStatus, error){
                    alert('失敗しました。');
                }
            });
        })
    });

</script>

{% endblock %}