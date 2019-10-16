<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>現場一覧</title>

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
            width: 25%;
        }

        .kinmuhyo table tr td:nth-of-type(2){
            width: 25%;
        }

        .kinmuhyo table tr td:nth-of-type(3){
            width: 40px;
        }

        .kinmuhyo table tr td:nth-of-type(4){
            width: 40px;
        }

        .kinmuhyo table tr td:nth-of-type(5){
            width: 40px;
        }

        .kinmuhyo table tr td:nth-of-type(6){
            width: 40px;
        }

        .kinmuhyo td{
            font-size: 1rem;
            vertical-align: middle;
        }

    </style>
</head>
<body>
<div class="kinmuhyo">
<h3>現場一覧</h3>
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
                <td class="cell">{{ date('H:i', site.breaksite.time_to | strtotime) }}</td>
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
