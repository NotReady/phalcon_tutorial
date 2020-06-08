{% extends "layout/template_in_service.volt" %}

{% block title %}従業員編集{% endblock %}
{% block css_include %}
<link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}
<script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
{% endblock %}
{% block content_body %}

<style>
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

    /* cols width */

    table.loans tr th:nth-of-type(1),
    table.loans tr td:nth-of-type(1)
    {width: 25%;}
    table.loans tr th:nth-of-type(2),
    table.loans tr td:nth-of-type(2)
    {width: 15%;}
    table.loans tr th:nth-of-type(3)
    table.loans tr td:nth-of-type(3)
    {width: 15%;}
    table.loans tr th:nth-of-type(4),
    table.loans tr td:nth-of-type(4)
    {width: 45%;}

    /* cells text-align */

    table.loans tr td:nth-of-type(2),
    table.loans tr td:nth-of-type(3)
    {text-align: center;}
    table.loans tr td:nth-of-type(4)
    {text-align: left;}

    /* table option */

    .sticky-table{
        height: 400px;
    }

</style>

<div class="content_root">

    <h1 class="title">登録情報</h1>

    {{ form('/employees/edit/check', 'method': 'post', 'class': 'row') }}

    {{ form.render('id') }}

    <span class="col-12">
        <h2 class="subtitle">基本情報管理</h2>
    </span>

    <div class="form-element-wrap col-3">
        {{ form.label('first_name') }}
        {{ form.render('first_name') }}
        {{ form.messages('first_name') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('last_name') }}
        {{ form.render('last_name') }}
        {{ form.messages('last_name') }}
    </div>

    <div class="form-element-wrap col-6">
        {{ form.label('address') }}
        {{ form.render('address') }}
        {{ form.messages('address') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('employee_type') }}
        {{ form.render('employee_type') }}
        {{ form.messages('employee_type') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('skill_id') }}
        {{ form.render('skill_id') }}
        {{ form.messages('skill_id') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('insurance_type') }}
        {{ form.render('insurance_type') }}
        {{ form.messages('insurance_type') }}
    </div>

    <div class="form-element-wrap col-3">
    </div>

    <span class="col-12">
        <h2 class="subtitle">給与管理 支給マスタ</h2>
    </span>


    <div class="form-element-wrap col-3">
        {{ form.label('monthly_charge') }}
        {{ form.render('monthly_charge') }}
        {{ form.messages('monthly_charge') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('overtime_charge') }}
        {{ form.render('overtime_charge') }}
        {{ form.messages('overtime_charge') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('skill_charge') }}
        {{ form.render('skill_charge') }}
        {{ form.messages('skill_charge') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('transportation_expenses') }}
        {{ form.render('transportation_expenses') }}
        {{ form.messages('transportation_expenses') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('transportation_expenses_by_day') }}
        {{ form.render('transportation_expenses_by_day') }}
        {{ form.messages('transportation_expenses_by_day') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('transportation_expenses_without_tax') }}
        {{ form.render('transportation_expenses_without_tax') }}
        {{ form.messages('transportation_expenses_without_tax') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('communication_charge_without_tax') }}
        {{ form.render('communication_charge_without_tax') }}
        {{ form.messages('communication_charge_without_tax') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('house_charge') }}
        {{ form.render('house_charge') }}
        {{ form.messages('house_charge') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('bus_charge') }}
        {{ form.render('bus_charge') }}
        {{ form.messages('bus_charge') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('officework_charge') }}
        {{ form.render('officework_charge') }}
        {{ form.messages('officework_charge') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('etc_charge') }}
        {{ form.render('etc_charge') }}
        {{ form.messages('etc_charge') }}
    </div>

    <div class="form-element-wrap col-3">
    </div>


    <span class="col-12">
        <h2 class="subtitle">給与管理 控除マスタ</h2>
    </span>

    <div class="form-element-wrap col-3">
        {{ form.label('rent_bill') }}
        {{ form.render('rent_bill') }}
        {{ form.messages('rent_bill') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('electric_bill') }}
        {{ form.render('electric_bill') }}
        {{ form.messages('electric_bill') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('gas_bill') }}
        {{ form.render('gas_bill') }}
        {{ form.messages('gas_bill') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('water_bill') }}
        {{ form.render('water_bill') }}
        {{ form.messages('water_bill') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('food_bill') }}
        {{ form.render('food_bill') }}
        {{ form.messages('food_bill') }}
    </div>

    <div class="form-element-wrap col-3">
        {{ form.label('etc_bill') }}
        {{ form.render('etc_bill') }}
        {{ form.messages('etc_bill') }}
    </div>

    <div class="form-element-wrap col-3">
    </div>

    <div class="form-element-wrap col-3">
    </div>


    <div class="form-element-wrap col-12 text-right mt-3">
        <ul>
            <li><input type="button" value="キャンセル" class="btn-outline-secondary form-control"></li>
            <li>{{ form.render('submit') }}</li>
        </ul>
    </div>

    {{ endform() }}

    <h1 class="title">貸付明細</h1>

    <p class="caption-large">貸付残高　{{ loansAmmount.ammount | number_format }} 円</p>

    <div class="sticky-table mb-3">
    <table class="table-hover table table-main loans">
        <thead>
            <th>日付</th>
            <th>貸付金額</th>
            <th>返済金額</th>
            <th>コメント</th>
        </thead>
        <tbody id="id-loans-body"></tbody>
    </table>
    </div>

    {# pager #}
    <div class="float-container clearfix">
        <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">新規登録する</button>
        <ul id="id_pager" class="float-right m0 mr-3 mb-0"></ul>
    </div>

    <!-- モーダルの設定 -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">明細を新規追加します</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="nm-employee-id" value="{{ employee_id }}">
                        <ul>
                            <li class="form-element-wrap">
                                <label for="loan-date">登録日付</label>
                                <input class="form-control" name="loan-date" type="date"/>
                                <span class="text-danger d-none" id="id-warn-loan-date">登録日付を入力してください</span>
                            </li>
                            <li class="form-element-wrap">
                                <label>種別</label><br />

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="loan-type" value="1" checked>
                                    <label class="form-check-label">貸付</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="loan-type" value="2">
                                    <label class="form-check-label">返済</label>
                                </div>

                            </li>
                            <li class="form-element-wrap">
                                <label for="loan-comment">金額</label>
                                <input class="form-control" name="loan-amount" type="number" placeholder="金額を入力してください"/>
                                <span class="text-danger d-none" id="id-warn-loan-amount">金額を入力してください</span>
                            </li>
                            <li class="form-element-wrap">
                                <label for="loan-comment">コメント</label>
                                <input class="form-control" name="loan-comment" type="text" placeholder="コメントを入力してください"/>
                                <span class="text-danger d-none" id="id-warn-loan-comment">コメントを入力してください</span>
                            </li>

                        </ul>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <button type="button" class="btn btn-primary" id="id-regist-loan">登録</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script>
$(function() {

    {# ページャ #}
    $('#id_pager').twbsPagination({
        startPage : 1,
        totalPages: <?= ceil( count($loans) / 10); ?>,
        first: '最初',
        prev : '前',
        next : '次',
        last : '最後',
        // これつけないと onPageClick の関数が初期表示時に実行されるアホ不具合がある。
        initiateStartPageClick: false,
        onPageClick: function (event, pageNum) {
            refreshLoansByPage(pageNum);
            console.log(pageNum);
        },
    });

    refreshLoansByPage(1);
    {# 貸付明細取得 #}
    function refreshLoansByPage(page){
        $.post({

            url: "/employees/loan/get",
            dataType: 'json',
            // フォーム要素の内容をハッシュ形式に変換
            data: {
                "employee_id" : {{ employee_id }},
                "page" : page,
            },
            timeout: 1000 * 30})
            .then(
                function(data, textStatus, jqXHR) {

                    $("#id-loans-body").empty();

                    $.each(JSON.parse(data['loans']), function(idx, l) {
                        $("<tr />").appendTo($("#id-loans-body"))
                            .append($("<td />").text(function(){
                                d = new Date(l.regist_date);
                                return `${d.getFullYear()}/${("00"+(d.getMonth()+1)).slice(-2)}/${("00" + d.getDate()).slice(-2)}` }))
                            .append($("<td />").text(function(){if( l.io_type == 1 ) return "+" + l.ammount;}()))
                            .append($("<td />").text(function(){if( l.io_type == 2 ) return "-" + l.ammount;}()))
                            .append($("<td />").text(l.comment));
                        console.log(l);
                    });
                },
                function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                }
            );
    }

    {# モーダルの表示イベント #}
    $("#staticBackdrop").on("show.bs.modal", function (e) {
        $("input[name='loan-date']").val("");
        $("input[name='loan-amount']").val("");
        $("input[name='loan-comment']").val("");
        $("input[name='loan-type']:eq(0)").prop("checked", true);
    })

    {# 貸付登録 #}
    $("#id-regist-loan").on("click", function(){

        if( date = $("input[name='loan-date']").val() ){
            $("#id-warn-loan-date").addClass("d-none");
        }else{
            $("#id-warn-loan-date").removeClass("d-none");
        }

        if( amount = $("input[name='loan-amount']").val() ){
            $("#id-warn-loan-amount").addClass("d-none");
        }else{
            $("#id-warn-loan-amount").removeClass("d-none");
        }

        if( comment = $("input[name='loan-comment']").val() ){
            $("#id-warn-loan-comment").addClass("d-none");
        }else{
            $("#id-warn-loan-comment").removeClass("d-none");
        }

        const type = $("input[name='loan-type']:checked").val();
        employee_id = $("input[name='nm-employee-id']").val();

        if( !( date && amount && comment && type && employee_id) ){return;}

        $.post({
            url: '/employees/loan/add',
            dataType: 'json',
            // フォーム要素の内容をハッシュ形式に変換
            data: {
                "date" : date,
                "type" : type,
                "amount" : amount,
                "comment" : comment,
                "employee_id" : employee_id
            },
            timeout: 1000 * 30,
        })
        .then(
            function(data, textStatus, jqXHR) {
                console.log(data);
                if( data['result'] ){
                    if( data['result'] == "success" ) {
                        location.reload();
                    }
                }
            },
            function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            }
        );

    });
});
</script>

{% endblock %}