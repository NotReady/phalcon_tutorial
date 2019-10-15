<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>勤務表</title>

    <style>

        body {
            font-size: 12px;
            font-family: sans-serif;
        }

        h1 {
            font-size: 16px;
        }

        .kinmuhyo {
            width: 90%;
            margin: 10px auto;
        }

        .kinmuhyo table {
            /*border: solid #eee 1px;*/
            text-align: center;
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        .kinmuhyo table tr td, .kinmuhyo table tr th {
            /*border: solid #eee 1px;*/
            /white-space: nowrap;
            position:relative;
        }

        .kinmuhyo table tr td:nth-of-type(n+4):nth-of-type(-n+7){
            width: 75px;
        }

        .kinmuhyo table tr td:nth-of-type(1){
            width: 55px;
        }

        .kinmuhyo table tr td:nth-of-type(2){
            width: 40px;
        }

        .kinmuhyo table tr td:nth-of-type(3){
            width: 150px;
        }

        .kinmuhyo table tr td:nth-of-type(4){
            width: 130px;
        }

        .kinmuhyo table tr td:nth-of-type(8){
            width: 50px;
        }

        .timeinput {
            width: 100%;
            box-sizing: border-box;
        }

        .ctrlPane {
            text-align: right;
            margin: 10px 0 0 0;
        }

        td.sat {
            background: cornflowerblue;
        }

        td.sun {
            background: lightcoral;
        }

        select {
            width: 100%;
        }

        .kinmuhyo td{
            font-size: 1rem;
            vertical-align: middle;
        }

    </style>
</head>
<body>
<div class="kinmuhyo">
<h3>{{ thismonth }}月度 {{ employee.first_name }} {{ employee.last_name }}の勤務表</h3>
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

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script><!-- ローカルと異なるところ -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script><!-- ローカルと異なるところ -->

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
</body>
</html>
