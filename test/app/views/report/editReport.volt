{% extends "layout/template_in_service.volt" %}

{% block title %}勤怠入力{% endblock %}
{% block css_include %}{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>

    /* 出勤テーブル */
    .table-main th:nth-of-type(1) {width: 10%;}
    .table-main th:nth-of-type(2) {width: 5%;}
    .table-main th:nth-of-type(3) {width: 20%;}
    .table-main th:nth-of-type(4) {width: 20%;}
    .table-main th:nth-of-type(n+5):nth-of-type(-n+7){width: 10%;}
    .table-main th:nth-of-type(8) {width: 20%;}

    /* 時間内訳テーブル */
    .table_timeunit td:nth-of-type(1),
    .table_timeunit th:nth-of-type(1)
    {width: 50%;}
    .table_timeunit td:nth-of-type(2),
    .table_timeunit th:nth-of-type(2)
    {width: 50%;}

    .table_timeunit td:nth-of-type(1){
        text-align: right;
    }

    .table_timeunit td:nth-of-type(2){
        text-align: left;
    }

    /* 現場別 出勤内訳 */
    table_timedetail td:nth-of-type(1),
    table_timedetail th:nth-of-type(1)
    {width: 25%;}
    table_timedetail td:nth-of-type(2),
    table_timedetail th:nth-of-type(2)
    {width: 25%;}
    table_timedetail td:nth-of-type(3),
    table_timedetail th:nth-of-type(3)
    {width: 15%;}
    table_timedetail td:nth-of-type(4),
    table_timedetail th:nth-of-type(4)
    {width: 15%;}
    table_timedetail td:nth-of-type(5),
    table_timedetail th:nth-of-type(5)
    {width: 20%;}

    .timeinput {
        width: 100%;
        box-sizing: border-box;
    }

    select {width: 100%;}

    .content_root td{
        font-size: 1rem;
        vertical-align: middle;
    }

    border *{
        box-sizing: border-box;
    }

    /* table option */

    .sticky-table{
        height: 500px;
    }

    .sat-decoration{
        border-radius: 50%;
        padding: 8px 9px;
        background-color: dodgerblue;
        color: #ffffff;
    }

    .sun-decoration{
        border-radius: 50%;
        padding: 8px 9px;
        background-color: crimson;
        color: #ffffff;
    }
</style>

<div class="content_root">

    <h1 class="title row">
        <div class="col-8 flex_box">
            <span>
                <a href="/employees/edit/{{ employee.id }}">{{ "%s %s" | format(employee.first_name, employee.last_name) }}</a>さん
                {{ "%d年 %d月の勤務レポート" | format(thisyear, thismonth) }}
            </span>
            <span class="highlight">
                <a href="{{ previousUrl }}" class="btn btn-outline-primary">＜ 前月</a>
                <a href="{{ nextUrl }}" class="btn btn-outline-primary">翌月 ＞</a>
            </span>
        </div>
        <div class="col-4 flex_box flex_right">
            <a href="/salary/{{ employee.id }}/{{ thisyear }}/{{ thismonth }}" class="btn btn-primary">給与を確認する</a>
        </div>
    </h1>

    {% set week = ['日','月','火','水','木','金','土'] %}

    <div class="sticky-table mb-3">
    <table class="table-hover table table-main">
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

        <?php foreach($reports as $day => $report): ?>
        <?php $windex = date('w',  strtotime("${thisyear}-${day}")); ?>
        <tr>

            <form method="post" action="/report/save" class="asyncForm">
                <input type="hidden" name="nm_date" value="{{thisyear}}-{{day}}" />
                <input type="hidden" name="nm_employee_id" value="{{employee.id}}" />
                <td class="cell">{{day}}</td>
                <td>
                    <span class="{% if windex is 6 %}sat-decoration{% elseif windex is 0 %}sun-decoration{% endif %}">
                    <?php
                        echo $week[date('w',  strtotime("${thisyear}-${day}"))];
                    ?>
                    </span>
                </td>
                <td><select class="form-control" name="nm_site_id"; ?>">
                        <?php foreach($sites as $id => $name): ?>
                            <option value="{{ id }}" {% if id is report.site_id  %}selected{% endif %} >{{ name }}</option>
                        <?php endforeach;?>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="nm_wtype_id"; ?>">
                        <?php
                            $worktypes = Worktypes::getWorkTypesByEmployeeAtSite($employee->id, $report->site_id);
                        ?>
                        <?php foreach($worktypes as $worktype): ?>
                            <option value="{{ worktype['worktype_id'] }}" {% if worktype['worktype_id'] is report.worktype_id %}selected{% endif %} >{{ worktype["name"] }}</option>
                        <?php endforeach;?>
                    </select>
                </td>

                {% if report is empty %}
                    <td><input class="form-control" name="nm_timefrom" class="timeinput" type="time"></td>
                    <td><input class="form-control" name="nm_timeto" class="timeinput" type="time"></td>
                    <td><input class="form-control" name="nm_breaktime" class="timeinput" type="time"></td>
                {% else %}
                    <td><input class="form-control" name="nm_timefrom" class="timeinput" type="time" value="{{date('H:i', report.time_from | strtotime)}}"></td>
                    <td><input class="form-control" name="nm_timeto" class="timeinput" type="time" value="{{date('H:i', report.time_to | strtotime)}}"></td>
                    <td><input class="form-control" name="nm_breaktime" class="timeinput" type="time" value="{{date('H:i', report.breaktime | strtotime)}}"></td>
                {% endif %}

                <td>
                    <input class="btn btn-primary btn-submit" type="button" value="保存" data-report-method="update">
                    <input class="btn btn-danger btn-submit" type="button" value="削除" data-report-method="delete">
                </td>

            </form>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <h2 class="title flex_box flex_left">
        <span>出勤統計</span>
        <span class="highlight">出勤日数　<span class="highlight-text">{{ days_worked }}</span> 日</span>
        (
        {% for unitname, time in howDaysWorkedOfDay %}
            <span class="highlight">{{ unitname }}　<span class="highlight-text">{{ time }}</span> 日</span>
        {% endfor %}
        )
    </h2>
    <div class="row">
        <div class="col-12">
            <table class="table table_timeunit">
                <thead>
                <th>項目</th>
                <th>時間</th>
                </thead>
                <tbody>
                {% for categoryName, time in summary['timeunits'] %}
                    <tr>
                        <td>{{ categoryName }}</td>
                        <td>{{ time }}</td>
                    </tr>
                {% endfor %}
                </tbody>
                <tfoot>
                <tr>
                    <td>出勤時間合計</td>
                    <td><span class="highlight-text">{{ summary['timeAll'] }}</span></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <h2 class="title">現場別 出勤内訳</h2>
    <div class="row">
        <div class=" col-12">
            <table class="table table_timedetail">
                <thead>
                    <th>現場</th>
                    <th>作業</th>
                    <th></th>
                    <th>時間計</th>
                    <th>金額</th>
                </thead>
                <tbody>
                {% for row in summary['site'] %}
                    <tr>
                        <td>{{ row.sitename }}</td>
                        <td>{{ row.worktype_name }}</td>
                        <td class="{% if row.label == '時間外' %}text-danger{% endif %}" >{{ row.label }}</td>
                        <td>{{ row.sum_time }}</td>
                        <td class="text-right">{{ row.sum_charge | number_format }} 円</td>
                    </tr>
                {% endfor %}
                </tbody>
                <tfoot>
                <tr>
                    <td>合計</td>
                    <td></td>
                    <td></td>
                    <td><span class="highlight-text">{{ summary['timeAll'] }}</span></td>
                    <td class="text-right"><span class="highlight-text">{{ summary['chargeAll'] | number_format }} 円</span></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>


</div>
{% include "includes/spinner.volt" %}
{% endblock %}

{% block js_include %}
<script>

    $(function(){

        {# 現場の選択イベント 有効な作業分類を取得する #}
        $("select[name='nm_site_id']").on("change", function (event) {

            // ポストキャンセル
            event.preventDefault();

            const siteId = $(this).val();
            const employeeId = $(this).parents("tr").find("input[name='nm_employee_id']").val();
            const $workTypeSelectForm = $(this).parents("tr").find("select[name='nm_wtype_id']");
            const $timeFromForm = $(this).parents("tr").find("input[name='nm_timefrom']");
            const $timeToForm = $(this).parents("tr").find("input[name='nm_timeto']");
            const $breaktimeForm = $(this).parents("tr").find("input[name='nm_breaktime']");

            // フォームをクリア
            $workTypeSelectForm.empty();
            $timeFromForm.val("");
            $timeToForm.val("");
            $breaktimeForm.val("");

            $.ajax({
                url: "/report/list/worktype",
                type: "POST",
                global: false,
                data: {
                    employee_id: employeeId,
                    site_id: siteId,
                },
                beforeSend: $(document).triggerHandler('ajaxStart')
            })
            .then(function(data, textStatus, jqXHR) {

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
                if( !data["worktypes"] ){
                    throw new Error("システムエラーです");
                }

                $workTypeSelectForm.append(`<option value="">選択してください</option>`);
                if( data['worktypes'] ){
                    $.each(data['worktypes'], function(idx, w) {
                        $workTypeSelectForm
                            .append(`<option value="${w['worktype_id']}">${w['name']}</option>`)

                        $timeFromForm.val(w["time_from"]);
                        $timeToForm.val(w["time_to"]);
                        $breaktimeForm.val(w["breaktime"]);
                    });
                }

                $(document).triggerHandler('ajaxStop', [ true ]);
            })
            .catch(function(jqXHR, textStatus, errorThrown){
                console.log(jqXHR);
                $(document).triggerHandler('ajaxStop', [ false, jqXHR]);
            });
        });

        $(".btn-submit").on("click", function (event) {

            // ポストキャンセル
            event.preventDefault();

            const $row = $(this).parents("tr");
            const date = $row.find("input[name='nm_date']").val();
            const employee_id = $row.find("input[name='nm_employee_id']").val();
            const site_id = $row.find("select[name='nm_site_id']").val();
            const wtype_id = $row.find("select[name='nm_wtype_id']").val();
            const timefrom = $row.find("input[name='nm_timefrom']").val();
            const timeto = $row.find("input[name='nm_timeto']").val();
            const breaktime = $row.find("input[name='nm_breaktime']").val();
            const action = $(this).data('report-method');

            // 非同期ポスト実装
            $.ajax({
                url: `/report/${action}`,
                type: "POST",
                data: {
                    nm_date: date,
                    nm_employee_id: employee_id,
                    nm_site_id: site_id,
                    nm_wtype_id: wtype_id,
                    nm_timefrom: timefrom,
                    nm_timeto: timeto,
                    nm_breaktime: breaktime
                },
                global: false,
                timeout: 1000 * 10,
                beforeSend: function(xhr, settings){
                    $(document).triggerHandler('ajaxStart');
                },
            }).then(
                function(data, textStatus, jqXHR) {
                    console.log(data);
                    if( data['result'] ){
                        if( data['result'] == "success" ) {
                            location.reload();
                        }
                        if( data['result'] == "failure" ) {
                            $(document).triggerHandler('ajaxStop', [ false, data['message']]);
                        }
                    }else{
                        $(document).triggerHandler('ajaxStop', [ false, "システムエラーが発生しました。"]);
                    }
            }).catch(
                function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    $(document).triggerHandler('ajaxStop', [ false, "システムエラーが発生しました。"]);
            });
            
        })
    });

</script>

{% endblock %}