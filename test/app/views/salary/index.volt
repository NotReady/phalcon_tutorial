{% extends "layout/template_in_service.volt" %}

{% block title %}勤怠入力{% endblock %}
{% block css_include %}
    <link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>

    .content_root .table-main tr td:nth-of-type(n+4):nth-of-type(-n+7){width: 75px;}
    .content_root .table-main tr td:nth-of-type(1){width: 55px;}
    .content_root .table-main tr td:nth-of-type(2){width: 40px;}
    .content_root .table-main tr td:nth-of-type(3){width: 150px;}
    .content_root .table-main tr td:nth-of-type(4){width: 130px;}
    .content_root .table-main tr td:nth-of-type(8){width: 50px;}

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

</style>

<div class="content_root">

    <h1 class="title">{{ "%s %sさん %d年 %d月の給与レポート" |format(employee.first_name, employee.last_name ,thisyear, thismonth) }}</h1>

    <div class="row">

        <style>
            table.salary td:nth-of-type(1),
            table.salary th:nth-of-type(1)
            {width: 25%;}
            table.salary td:nth-of-type(2),
            table.salary th:nth-of-type(2)
            {width: 25%;}
            table.salary td:nth-of-type(3),
            table.salary th:nth-of-type(3)
            {width: 15%;}
            table.salary td:nth-of-type(4),
            table.salary th:nth-of-type(4)
            {width: 15%;}
            table.salary td:nth-of-type(5),
            table.salary th:nth-of-type(5)
            {width: 20%;}

        </style>

        <div class=" col-12">
            <p class="subtitle">時間給</p>
            <table class="table salary">
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
                    <td>{{ summary['timeAll'] }}</td>
                    <td class="text-right">{{ summary['chargeAll'] | number_format }} 円</td>
                </tr>
                </tfoot>
            </table>
        </div>


        <div class="col-6">
            <p class="subtitle">福利厚生</p>
            <table class="table">
                <thead>
                <th>項目</th>
                <th>数量</th>
                <th>金額</th>
                </thead>
                <tbody>
                    <tr>
                        <td>交通費</td>
                        <td></td>
                        <td>¥ 10,000</td>
                    </tr>
                    <tr>
                        <td>役職手当</td>
                        <td></td>
                        <td>¥ 10,000</td>
                    </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>合計</td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="col-6">
            <p class="subtitle">控除</p>
            <table class="table">
                <thead>
                <th>項目</th>
                <th>数量</th>
                <th>金額</th>
                </thead>
                <tbody>
                    <tr>
                        <td>寮費</td>
                        <td></td>
                        <td>¥ 20,000</td>
                    </tr>
                    <tr>
                        <td>貸付金返済</td>
                        <td></td>
                        <td>¥ 30,000</td>
                    </tr>
                    <tr>
                        <td>社会保険</td>
                        <td></td>
                        <td>¥ 30,000</td>
                    </tr>
                    <tr>
                        <td>雇用保険</td>
                        <td></td>
                        <td>¥ 10,000</td>
                    </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>合計</td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>

    </div>


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