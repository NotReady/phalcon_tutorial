{% extends "layout/template_in_service.volt" %}

{% block title %}勤怠入力{% endblock %}
{% block css_include %}{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>

    /* 出勤テーブル */
    .table-main th:nth-of-type(1) {width: 10%;}
    .table-main th:nth-of-type(2) {width: 5%;}
    .table-main th:nth-of-type(3) {width: 10%;}
    .table-main th:nth-of-type(4) {width: 15%;}
    .table-main th:nth-of-type(5) {width: 15%;}
    .table-main th:nth-of-type(n+6):nth-of-type(-n+8){width: 10%;}
    .table-main th:nth-of-type(9) {width: 20%;}

    /* 時間内訳テーブル */
    .table_timeunit td:nth-of-type(1),
    .table_timeunit th:nth-of-type(1)
    {width: 40%;}
    .table_timeunit td:nth-of-type(2),
    .table_timeunit th:nth-of-type(2)
    {width: 30%;}
    .table_timeunit td:nth-of-type(3),
    .table_timeunit th:nth-of-type(3)
    {width: 30%;}

    /* 現場別 出勤内訳 */
    table_timedetail td:nth-of-type(1),
    table_timedetail th:nth-of-type(1)
    {width: 25%;}
    table_timedetail td:nth-of-type(2),
    table_timedetail th:nth-of-type(2)
    {width: 25%;}
    table_timedetail td:nth-of-type(3),
    table_timedetail th:nth-of-type(3)
    {width: 10%;}
    table_timedetail td:nth-of-type(4),
    table_timedetail th:nth-of-type(4)
    {width: 10%;}
    table_timedetail td:nth-of-type(5),
    table_timedetail th:nth-of-type(5)
    {width: 10%;}
    table_timedetail td:nth-of-type(6),
    table_timedetail th:nth-of-type(6)
    {width: 20%;}

    .right_align_margin{
        margin-right: 25%;
    }

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

    .holi-decoration{
        border-radius: 50%;
        padding: 8px 9px;
        background-color: lightpink;
        color: #ffffff;
    }

    .v-mid{
        vertical-align: middle;
    }

    .v-center{
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .flex_sequence_container{
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        height: 100%;
    }

    .flex_sequence_container > *{
        margin: 0.5rem;

    }

    .data-boxy{
        width: 110px;
        height: 110px;
        text-align: center;
        display: flex;
        align-items: center;
        display: inline-block;
        border: 1px solid #eee;
    }

    .data-boxy .header{
        height: 30px;
        background-color: #eee;
    }

    .data-boxy .body{
        height: 80px;
    }

</style>

<div class="content_root">

    <h1 class="title row">
        <div class="col-8 flex_box">

            {% if salary.fixed is 'fixed'%}
                <span class="badge-success v-mid">確定済</span>
            {% else %}
                <span class="badge-alert v-mid">未確定</span>
            {% endif %}
            <span class="v-mid">
                <a class="ml-2" href="/employees/edit/{{ employee.id }}">{{ "%s %s" | format(employee.first_name, employee.last_name) }}</a>さん
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
        <th>勤怠</th>
        <th>勤務先</th>
        <th>作業分類</th>
        <th>開始時間</th>
        <th>終了時間</th>
        <th>休憩時間</th>
        <th>保存</th>
        </thead>

        {% for day, report in reports %}
        <?php $windex = date('w',  strtotime("${day}")); ?>
        <tr>
            <input type="hidden" name="at_day" value="{{ report.getValue('at_day') }}" />
            <input type="hidden" name="employee_id" value="{{report.getValue('employee_id') }}" />
            <td class="cell"><?= date('m-d', strtotime($day)) ?></td>
            <td>
                {% set isHoliday = false %}
                <?php foreach( $holidays as $holiday => $caption ): ?>
                    <?php if( $holiday === $day ): ?>
                        <?php $isHoliday = true; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <span class="{% if isHoliday === true %}holi-decoration{% elseif windex is 6 %}sat-decoration{% elseif windex is 0 %}sun-decoration{% endif %}">
                    <?= $week[$windex] ?>
                </span>
            </td>
            <td>{{ report.render('attendance') }}</td>
            <td>{{ report.render('site_id') }}</td>
            <td>{{ report.render('worktype_id') }}</td>
            <td>{{ report.render('time_from') }}</td>
            <td>{{ report.render('time_to') }}</td>
            <td>{{ report.render('breaktime') }}</td>
            <td>
                <input class="btn btn-primary btn-submit" type="button" value="保存" data-report-method="update">
                <input class="btn btn-danger btn-submit" type="button" value="削除" data-report-method="delete">
            </td>
        </tr>
        {% endfor %}
        </tbody>
    </table>
    </div>

    <h2 class="title flex_box flex_left">
        <span>出勤統計</span>
    </h2>
    <div class="row">
        <div class="col-4 flex_sequence_container">

            <div class="data-boxy">
                <div class="header v-center">営業日数</div>
                <div class="body v-center"><span class="highlight-text">{{ days_business }}</div>
            </div>

            <div class="data-boxy">
                <div class="header v-center">出勤日数</div>
                <div class="body v-center"><span class="highlight-text text-success">{{ days_worked }}</span></div>
            </div>

            <div class="data-boxy">
                <div class="header v-center">欠勤日数</div>
                <div class="body v-center"><span class="highlight-text text-danger">{{ days_Absenteeism }}</span></div>
            </div>

            <div class="data-boxy">
                <div class="header v-center">時間内</div>
                <div class="body v-center"><span class="highlight-text">{{ summary['intimeAll']}}</span></div>
            </div>

            <div class="data-boxy">
                <div class="header v-center">時間外</div>
                <div class="body v-center"><span class="highlight-text">{{ summary['outtimeAll']}}</span></div>
            </div>

        </div>
        <div class="col-8">
            <table class="table table_timeunit">
                <thead>
                <th>項目</th>
                <th>時間</th>
                <th>出勤日数</th>
                </thead>
                <tbody>
                {% for categoryName, unit in summary['timeunits'] %}
                    <tr>
                        <td>{{ categoryName }}</td>
                        <td>{{ unit['time'] }}</td>
                        <td>{{ unit['days'] }} 日</td>
                    </tr>
                {% endfor %}
                </tbody>
                <tfoot>
                <tr>
                    <td>合計</td>
                    <td><span class="highlight-text">{{ summary['timeAll'] }}</span></td>
                    <td><span class="highlight-text">{{ days_worked }} 日</span></td>
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
                    <th>日数計</th>
                    <th class="text-right"><span class="right_align_margin">金額</span></th>
                </thead>
                <tbody>
                {% for row in summary['site'] %}
                    <tr>
                        <td>{{ row.sitename }}</td>
                        <td>{{ row.worktype_name }}</td>
                        <td class="{% if row.label == '時間外' %}text-danger{% endif %}" >{{ row.label }}</td>
                        <td>{{ row.sum_time }}</td>
                        <td>{{ row.days_worked }} 日</td>
                        <td class="text-right"><span class="right_align_margin">{{ row.sum_charge | number_format }} 円</span></td>
                    </tr>
                {% endfor %}
                </tbody>
                <tfoot>
                <tr>
                    <td>合計</td>
                    <td></td>
                    <td></td>
                    <td><span class="highlight-text">{{ summary['timeAll'] }}</span></td>
                    <td><span class="highlight-text">{{ days_worked }} 日</span></td>
                    <td class="text-right"><span class="highlight-text right_align_margin">{{ summary['chargeAll'] | number_format }} 円</span></td>
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

        {# 勤怠の選択イベント #}
        $(document).on('change', 'select[name="attendance"]', function () {
            {# フォームをクリアする #}
            $(this).parents("tr").find("select[name='site_id']").val(0);
            $(this).parents("tr").find("select[name='worktype_id']").val(0);
            $(this).parents("tr").find("input[name='time_from']").val("");
            $(this).parents("tr").find("input[name='time_to']").val("");
            $(this).parents("tr").find("input[name='breaktime']").val("");

            {# フォームのdisableを制御 #}
            const attendance = $(this).val();
            const disabled = attendance === "absenteeism";
            $(this).parents("tr").find("select[name='site_id']").prop("disabled", disabled);
            $(this).parents("tr").find("select[name='worktype_id']").prop("disabled", disabled);
            $(this).parents("tr").find("input[name='time_from']").prop("disabled", disabled);
            $(this).parents("tr").find("input[name='time_to']").prop("disabled", disabled);
            $(this).parents("tr").find("input[name='breaktime']").prop("disabled", disabled)
        });

        {# 現場の選択イベント 有効な作業分類を取得する #}
        $("select[name='site_id']").on("change", function (event) {

            const $tr = $(this).parents("tr");
            const site_id = $(this).val();
            const employee_id = $tr.find("input[name='employee_id']").val();

            // フォームをクリア
            $tr.find("select[name='worktype_id']").empty();
            $tr.find("input[name='time_from']").val("");
            $tr.find("input[name='time_to']").val("");
            $tr.find("input[name='breaktime']").val("");

            $.ajax({
                url: "/report/list/worktype",
                type: "POST",
                global: false,
                data: {
                    site_id: site_id,
                    employee_id: employee_id
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

                $tr.find("select[name='worktype_id']").append(`<option value="">選択してください</option>`);
                if( data['worktypes'] ){
                    {# フォームをセット #}
                    $.each(data['worktypes'], function(idx, w) {
                        $tr.find("select[name='worktype_id']")
                            .append(`<option value="${w['worktype_id']}">${w['name']}</option>`)

                        $tr.find("input[name='time_from']").val(w["time_from"]);
                        $tr.find("input[name='time_to']").val(w["time_to"]);
                        $tr.find("input[name='breaktime']").val(w["breaktime"]);
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

            const $row = $(this).parents("tr");
            const date = $row.find("input[name='at_day']").val();
            const employee_id = $row.find("input[name='employee_id']").val();
            const attendance = $row.find("select[name='attendance']").val();
            const site_id = $row.find("select[name='site_id']").val();
            const wtype_id = $row.find("select[name='worktype_id']").val();
            const timefrom = $row.find("input[name='time_from']").val();
            const timeto = $row.find("input[name='time_to']").val();
            const breaktime = $row.find("input[name='breaktime']").val();
            const action = $(this).data('report-method');

            $.ajax({
                url: `/report/${action}`,
                type: "POST",
                data: {
                    at_day: date,
                    employee_id: employee_id,
                    attendance: attendance,
                    site_id: site_id,
                    worktype_id: wtype_id,
                    time_from: timefrom,
                    time_to: timeto,
                    breaktime: breaktime
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
                            $(document).triggerHandler('ajaxStop', [ true , action=="update"?"登録しました":"削除しました", function () {
                                location.reload(); return;
                            }]);
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