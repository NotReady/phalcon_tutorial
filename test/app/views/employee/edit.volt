<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>従業員編集</title>

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

        .form-element-wrap{
            margin: 0 auto 15px 0;
        }

        .form-element-wrap ul{
            padding: 0;
            text-align: right;
        }

        .form-element-wrap ul li{
            display: inline-block;
            width: 100px;
            margin-left: 10px;
        }

        .errorMessage{
            color: lightcoral;
        }

    </style>
</head>
<body>
<div class="kinmuhyo">
<h3>従業員編集</h3>

    {{ form('/employees/edit/check', 'method': 'post') }}

    {{ form.render('id') }}

    <div class="form-element-wrap">
        {{ form.label('first_name') }}
        {{ form.render('first_name') }}
        {{ form.messages('first_name') }}
    </div>

    <div class="form-element-wrap">
        {{ form.label('last_name') }}
        {{ form.render('last_name') }}
        {{ form.messages('last_name') }}
    </div>

    <div class="form-element-wrap">
        {{ form.label('Transportation_expenses') }}
        {{ form.render('Transportation_expenses') }}
        {{ form.messages('Transportation_expenses') }}
    </div>

    <div class="form-element-wrap">
        <ul>
            <li><input type="button" value="キャンセル" class="btn-outline-secondary form-control"></li>
            <li>{{ form.render('submit') }}</li>
        </ul>
    </div>

    {{ endform() }}

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script><!-- ローカルと異なるところ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script><!-- ローカルと異なるところ -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script><!-- ローカルと異なるところ -->

</body>
</html>
