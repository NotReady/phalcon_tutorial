<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>従業員一覧</title>

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

        .kinmuhyo table tr td:nth-of-type(1){
            width: 40px;
        }

        .kinmuhyo table tr td:nth-of-type(2){
            width: 40px;
        }

        .kinmuhyo table tr td:nth-of-type(3){
            width: 50%;
        }

        .kinmuhyo table tr td:nth-of-type(4){
            width: 18%;
        }

        .kinmuhyo table tr td:nth-of-type(5){
            width: 15%;
        }

        .kinmuhyo td{
            font-size: 1rem;
            vertical-align: middle;
        }

    </style>
</head>
<body>
<div class="kinmuhyo">
<h3>従業員一覧</h3>
<table class="table-hover table">
    <thead>
    <th>勤怠入力</th>
    <th>労務編集</th>
    <th>名前</th>
    <th>登録日</th>
    <th>最終入力日</th>
    </thead>
    <tbody>
        <?php foreach($employee_info as $employee): ?>
            <tr>
                <td class="cell"><a class="btn btn-primary btn-success" href="/report/{{ employee.employee_id }}/2019/05" role="button">勤怠入力</a></td>
                <td class="cell"><a class="btn btn-primary" href="#" role="button">労務編集</a></td>
                <td class="cell">{{ employee.first_name }} {{ employee.last_name }}</td>
                <td class="cell">{{ date('Y-m-d', employee.created | strtotime) }}</td>
                <td class="cell">{{ employee.last_input_day }}</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script><!-- ローカルと異なるところ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script><!-- ローカルと異なるところ -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script><!-- ローカルと異なるところ -->

</body>
</html>
