{% extends "layout/template_in_service.volt" %}

{% block title %}従業員編集{% endblock %}
{% block css_include %}
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
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
    {width: 20%;}
    table.loans tr th:nth-of-type(2),
    table.loans tr td:nth-of-type(2)
    {width: 15%;}
    table.loans tr th:nth-of-type(3)
    table.loans tr td:nth-of-type(3)
    {width: 15%;}
    table.loans tr th:nth-of-type(4),
    table.loans tr td:nth-of-type(4)
    {width: 40%;}
    table.loans tr th:nth-of-type(5),
    table.loans tr td:nth-of-type(5)
    {width: 10%;}

    /* cells text-align */

    table.loans tr td:nth-of-type(2),
    table.loans tr td:nth-of-type(3),
    table.loans tr td:nth-of-type(5)
    {text-align: center;}
    table.loans tr td:nth-of-type(4)
    {text-align: left; padding-left: 1rem;}

    /* table option */

    .sticky-table{
        height: 400px;
    }

    .errorMessage{
        color: red;
    }

    input[type="number"]{
        text-align: right;
    }

</style>

<div class="content_root">

    <h1 class="title"><i class="fas fa-user-circle mr-1"></i>登録情報</h1>

    {{ form('/employees/edit/check', 'method': 'post', 'class': 'row') }}

    {{ form.render('id') }}

    <div class="col-12 mb-4">
    <div style="border: 1px solid #ced4da; border-radius: 5px; padding: 1.5rem;">
    <div class="row">

        <span class="col-12">
            <h2 class="subtitle"><i class="fas fa-user-circle mr-1"></i>基本情報管理</h2>
        </span>

        <div class="form-element-wrap col-3">
            {{ form.label('first_name', ['class' : 'form-label']) }}
            {{ form.render('first_name') }}
            {{ form.messages('first_name') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('last_name', ['class' : 'form-label']) }}
            {{ form.render('last_name') }}
            {{ form.messages('last_name') }}
        </div>

        <div class="form-element-wrap col-6">
            {{ form.label('address', ['class' : 'form-label']) }}
            {{ form.render('address') }}
            {{ form.messages('address') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('employee_no', ['class' : 'form-label']) }}
            {{ form.render('employee_no') }}
            {{ form.messages('employee_no') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('employee_status', ['class' : 'form-label']) }}
            {{ form.render('employee_status') }}
            {{ form.messages('employee_status') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('hire_date', ['class' : 'form-label']) }}
            {{ form.render('hire_date') }}
            {{ form.messages('hire_date') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('leave_date', ['class' : 'form-label']) }}
            {{ form.render('leave_date') }}
            {{ form.messages('leave_date') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('employee_type', ['class' : 'form-label']) }}
            {{ form.render('employee_type') }}
            {{ form.messages('employee_type') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('skill_id', ['class' : 'form-label']) }}
            {{ form.render('skill_id') }}
            {{ form.messages('skill_id') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('insurance_type', ['class' : 'form-label']) }}
            {{ form.render('insurance_type') }}
            {{ form.messages('insurance_type') }}
        </div>

        <div class="form-element-wrap col-3">
        </div>
    </div>
    </div>
    </div>

    <div class="col-12 mb-4">
    <div style="border: 1px solid #ced4da; border-radius: 5px; padding: 1.5rem;">
    <div class="row">

        <span class="col-12">
            <h2 class="subtitle"><i class="fas fa-arrow-circle-up mr-1"></i>給与管理 支給マスタ</h2>
        </span>


        <div class="form-element-wrap col-3">
            {{ form.label('monthly_charge', ['class' : 'form-label positive']) }}
            {{ form.render('monthly_charge') }}
            {{ form.messages('monthly_charge') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('overtime_charge', ['class' : 'form-label positive']) }}
            {{ form.render('overtime_charge') }}
            {{ form.messages('overtime_charge') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('skill_charge', ['class' : 'form-label positive']) }}
            {{ form.render('skill_charge') }}
            {{ form.messages('skill_charge') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('transportation_expenses', ['class' : 'form-label positive']) }}
            {{ form.render('transportation_expenses') }}
            {{ form.messages('transportation_expenses') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('transportation_expenses_by_day', ['class' : 'form-label positive']) }}
            {{ form.render('transportation_expenses_by_day') }}
            {{ form.messages('transportation_expenses_by_day') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('transportation_expenses_without_tax', ['class' : 'form-label positive']) }}
            {{ form.render('transportation_expenses_without_tax') }}
            {{ form.messages('transportation_expenses_without_tax') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('communication_charge_without_tax', ['class' : 'form-label positive']) }}
            {{ form.render('communication_charge_without_tax') }}
            {{ form.messages('communication_charge_without_tax') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('house_charge', ['class' : 'form-label positive']) }}
            {{ form.render('house_charge') }}
            {{ form.messages('house_charge') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('bus_charge', ['class' : 'form-label positive']) }}
            {{ form.render('bus_charge') }}
            {{ form.messages('bus_charge') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('officework_charge', ['class' : 'form-label positive']) }}
            {{ form.render('officework_charge') }}
            {{ form.messages('officework_charge') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('etc_charge', ['class' : 'form-label positive']) }}
            {{ form.render('etc_charge') }}
            {{ form.messages('etc_charge') }}
        </div>

        <div class="form-element-wrap col-3">
        </div>
    </div>
    </div>
    </div>

    <div class="col-12 mb-4">
    <div style="border: 1px solid #ced4da; border-radius: 5px; padding: 1.5rem;">
    <div class="row">

        <span class="col-12">
            <h2 class="subtitle"><i class="fas fa-arrow-circle-down mr-1"></i>給与管理 控除マスタ</h2>
        </span>

        <div class="form-element-wrap col-3">
            {{ form.label('rent_bill', ['class' : 'form-label negative']) }}
            {{ form.render('rent_bill') }}
            {{ form.messages('rent_bill') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('electric_bill', ['class' : 'form-label negative']) }}
            {{ form.render('electric_bill') }}
            {{ form.messages('electric_bill') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('gas_bill', ['class' : 'form-label negative']) }}
            {{ form.render('gas_bill') }}
            {{ form.messages('gas_bill') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('water_bill', ['class' : 'form-label negative']) }}
            {{ form.render('water_bill') }}
            {{ form.messages('water_bill') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('food_bill', ['class' : 'form-label negative']) }}
            {{ form.render('food_bill') }}
            {{ form.messages('food_bill') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('etc_bill', ['class' : 'form-label negative']) }}
            {{ form.render('etc_bill') }}
            {{ form.messages('etc_bill') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('insurance_bill', ['class' : 'form-label negative']) }}
            {{ form.render('insurance_bill') }}
            {{ form.messages('insurance_bill') }}
        </div>

        <div class="form-element-wrap col-3">
            {{ form.label('pension_bill', ['class' : 'form-label negative']) }}
            {{ form.render('pension_bill') }}
            {{ form.messages('pension_bill') }}
        </div>

    </div>
    </div>
    </div>

    <div class="form-element-wrap col-12 text-right mt-3">
        <ul>
            <li><input type="button" value="キャンセル" class="btn-outline-secondary form-control"></li>
            <li>{{ form.render('submit') }}</li>
        </ul>
    </div>

    {{ endform() }}

    {# 貸付Pane #}
    <h1 class="title"><i class="fas fa-hand-holding-usd mr-1"></i>貸付明細</h1>

    <p class="caption-large">貸付残高　{{ loansAmount | number_format }} 円</p>

    <div class="sticky-table mb-3">
    <table class="table-hover table table-main loans">
        <thead>
            <th>日付</th>
            <th>貸付金額</th>
            <th>返済金額</th>
            <th>コメント</th>
            <th>変更</th>
        </thead>
        <tbody id="id-loans-body">{# load ajax response #}</tbody>
    </table>
    </div>

    <div class="float-container clearfix">
        <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#idModalLoans">新規登録する</button>
        {# pager #}
        <ul id="id_loan_pager" class="float-right m0 mr-3 mb-0"></ul>
    </div>

    {# 有給Pane #}
    <h1 class="title"><i class="fas fa-hand-holding-usd mr-1"></i>有給管理</h1>

    <p class="caption-large">残り有給　{{ holidaysAmount | number_format }} 日</p>

    <div class="sticky-table mb-3">
        <table class="table-hover table table-main loans">
            <thead>
            <th>日付</th>
            <th>付与日数</th>
            <th>消化日数</th>
            <th>コメント</th>
            <th>変更</th>
            </thead>
            <tbody id="id-holiday-body">{# load ajax response #}</tbody>
        </table>
    </div>

    <div class="float-container clearfix">
        <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#idModalHolidays">新規登録する</button>
        {# pager #}
        <ul id="id_holiday_pager" class="float-right m0 mr-3 mb-0"></ul>
    </div>

    {# 貸付編集モーダルウインドウ #}
    <div class="modal fade" id="idModalLoans" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="idModalLoansLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="idModalLoansLabel">明細を新規追加します</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="nm-employee-id" value="{{ employee_id }}">
                        <input type="hidden" name="loan-id">
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
                                <label for="loan-amount">金額</label>
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
                    <span class="loan-modify-method">
                        <button type="button" class="btn btn-danger" id="id-delete-loan" data-method="delete">削除する</button>
                        <button type="button" class="btn btn-primary" id="id-update-loan" data-method="update">変更する</button>
                    </span>
                    <span class="loan-add-method">
                        <button type="button" class="btn btn-primary" id="id-create-loan" data-method="create">登録する</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {# 有給編集モーダルウインドウ #}
    <div class="modal fade" id="idModalHolidays" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="idModalHolidaysLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="idModalHolidaysLabel">有給を新規追加します</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="nm-employee-id" value="{{ employee_id }}">
                        <input type="hidden" name="holiday-id">
                        <ul>
                            <li class="form-element-wrap">
                                <label for="holiday-date">登録日付</label>
                                <input class="form-control" name="holiday-date" type="date"/>
                                <span class="text-danger d-none" id="id-warn-holiday-date">登録日付を入力してください</span>
                            </li>
                            <li class="form-element-wrap">
                                <label>種別</label><br />

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="holiday-type" value="1" checked>
                                    <label class="form-check-label">付与</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="holiday-type" value="2">
                                    <label class="form-check-label">消化</label>
                                </div>

                            </li>
                            <li class="form-element-wrap">
                                <label for="holiday-amount">日数</label>
                                <input class="form-control" name="holiday-amount" type="number" placeholder="日数を入力してください"/>
                                <span class="text-danger d-none" id="id-warn-holiday-amount">日数を入力してください</span>
                            </li>
                            <li class="form-element-wrap">
                                <label for="holiday-comment">コメント</label>
                                <input class="form-control" name="holiday-comment" type="text" placeholder="コメントを入力してください"/>
                                <span class="text-danger d-none" id="id-warn-holiday-comment">コメントを入力してください</span>
                            </li>

                        </ul>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <span class="holiday-modify-method">
                        <button type="button" class="btn btn-danger" id="id-delete-holiday" data-method="delete">削除する</button>
                        <button type="button" class="btn btn-primary" id="id-update-holiday" data-method="update">変更する</button>
                    </span>
                    <span class="holiday-add-method">
                        <button type="button" class="btn btn-primary" id="id-create-holiday" data-method="create">登録する</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>
{% include "includes/spinner.volt" %}
{% endblock %}

{% block js_include %}
<script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script>
$(function() {

    {# ページャ #}
    <?php if( count($loans) > 0 ): ?>
        $('#id_loan_pager').twbsPagination({
            startPage : 1,
            totalPages: <?= ceil( count($loans) / 10); ?>,
            first: "最初",
            prev : "前",
            next : "次",
            last : "最後",
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

                url: "/employees/loan/get/member",
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

                        $.each(JSON.parse(data['loans']), function(idx, loan) {

                        $("<tr />").appendTo($("#id-loans-body"))
                                .append($("<td />").text(function(){
                                    const d = new Date(loan.regist_date);
                                    return `${d.getFullYear()}年${("00"+(d.getMonth()+1)).slice(-2)}月${("00" + d.getDate()).slice(-2)}日` }))
                                .append($("<td />").text(function(){if( loan.io_type == 1 ) return loan.amount + " 円";}()))
                                .append($("<td />").text(function(){if( loan.io_type == 2 ) return loan.amount + " 円";}()))
                                .append($("<td />").text(loan.comment))
                                .append(
                                    $("<td />").append(
                                        $(`<button class="btn btn-primary" data-toggle="modal" data-target="#idModalLoans" data-loan-id="${loan.loan_id}"
                                        data-loan-date="${function(){const d = new Date(loan.regist_date);return `${d.getFullYear()}-${("00"+(d.getMonth()+1)).slice(-2)}-${("00" + d.getDate()).slice(-2)}`}()}"
                                        data-loan-type="${loan.io_type}" data-loan-amount="${loan.amount}" data-loan-comment="${loan.comment}">変更</button>`)
                                    )
                                );
                            console.log(loan);
                        });
                    },
                    function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                    }
                );
        }
    <?php endif; ?>

    {# 有給明細を描画します #}
    <?php if( count($paid_holidays) > 0 ): ?>

    $('#id_holiday_pager').twbsPagination({
        startPage : 1,
        totalPages: <?= ceil( count($paid_holidays) / 10); ?>,
        first: "最初",
        prev : "前",
        next : "次",
        last : "最後",
        // これつけないと onPageClick の関数が初期表示時に実行されるアホ不具合がある。
        initiateStartPageClick: false,
        onPageClick: function (event, pageNum) {
        renderStatementInHoliday(pageNum);
        console.log(pageNum);
        },
    });

    renderStatementInHoliday(1);
    function renderStatementInHoliday(page){
        $.post({
            url: "/employees/holiday/get/member",
            dataType: 'json',
            data: {
                "employee_id" : {{ employee_id }},
                "page" : page,
            },
            timeout: 1000 * 30})
        .then(
            function(data, textStatus, jqXHR) {

                $("#id-holiday-body").empty();

                $.each(JSON.parse(data['holidays']), function(idx, holiday) {

                    $("<tr />").appendTo($("#id-holiday-body"))
                        .append($("<td />").text(function(){
                            const d = new Date(holiday.regist_date);
                            return `${d.getFullYear()}年${("00"+(d.getMonth()+1)).slice(-2)}月${("00" + d.getDate()).slice(-2)}日` }))
                        .append($("<td />").text(function(){if( holiday.io_type == 1 ) return holiday.amount + " 日";}()))
                        .append($("<td />").text(function(){if( holiday.io_type == 2 ) return holiday.amount + " 日";}()))
                        .append($("<td />").text(holiday.comment))
                        .append(
                            $("<td />").append(
                                $(`<button class="btn btn-primary" data-toggle="modal" data-target="#idModalHolidays" data-holiday-id="${holiday.paid_holiday_id}"
                                    data-holiday-date="${function(){const d = new Date(holiday.regist_date);return `${d.getFullYear()}-${("00"+(d.getMonth()+1)).slice(-2)}-${("00" + d.getDate()).slice(-2)}`}()}"
                                    data-holiday-type="${holiday.io_type}" data-holiday-amount="${holiday.amount}" data-holiday-comment="${holiday.comment}">変更</button>`)
                            )
                        );
                    console.log(holiday);
                });
            },
            function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            }
        );
    }
    <?php endif; ?>

    {# 貸付モーダルの表示イベント #}
    $("#idModalLoans").on("show.bs.modal", function (e) {
        const loan_id = $(e.relatedTarget).data("loan-id");
        const loan_date = $(e.relatedTarget).data("loan-date");
        const loan_type = $(e.relatedTarget).data("loan-type")
        const loan_amount = $(e.relatedTarget).data("loan-amount");
        const loan_comment = $(e.relatedTarget).data("loan-comment");
        const radio = loan_type ? loan_type-1 : 0;
        $("input[name='loan-id']").val(loan_id);
        $("input[name='loan-date']").val(loan_date);
        $(`input[name='loan-type']:eq(${radio})`).prop("checked", true);
        $("input[name='loan-amount']").val(loan_amount);
        $("input[name='loan-comment']").val(loan_comment);

        if( loan_id ){
            $(".modal-title").text("貸付を編集します");
            $(".loan-add-method").hide();
            $(".loan-modify-method").show();
        }
        else{
            $(".modal-title").text("貸付を登録します");
            $(".loan-add-method").show();
            $(".loan-modify-method").hide();
        }

    })

    {# 有給モーダルの表示イベント #}
    $("#idModalHolidays").on("show.bs.modal", function (e) {
        const holiday_id = $(e.relatedTarget).data("holiday-id");
        const holiday_date = $(e.relatedTarget).data("holiday-date");
        const holiday_type = $(e.relatedTarget).data("holiday-type")
        const holiday_amount = $(e.relatedTarget).data("holiday-amount");
        const holiday_comment = $(e.relatedTarget).data("holiday-comment");
        const radio = holiday_type ? holiday_type-1 : 0;
        $("input[name='holiday-id']").val(holiday_id);
        $("input[name='holiday-date']").val(holiday_date);
        $(`input[name='holiday-type']:eq(${radio})`).prop("checked", true);
        $("input[name='holiday-amount']").val(holiday_amount);
        $("input[name='holiday-comment']").val(holiday_comment);

        if( holiday_id ){
            $(".modal-title").text("有給を編集します");
            $(".holiday-add-method").hide();
            $(".holiday-modify-method").show();
        }
        else{
            $(".modal-title").text("有給を登録します");
            $(".holiday-add-method").show();
            $(".holiday-modify-method").hide();
        }

    })

    function getLoan(loanId) {
        $.post({
            url: "/employees/loan/get/id",
            dataType: 'json',
            // フォーム要素の内容をハッシュ形式に変換
            data: {
                "loan_id" : loanId
            },
            timeout: 1000 * 30,
        })
        .then(
            function(data, textStatus, jqXHR) {
                console.log(data);
                $loan = JSON.parse(data);
            },
            function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            }
        );
    }

    {# 貸付編集 #}
    $("#id-create-loan, #id-update-loan, #id-delete-loan").on("click", function(){

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
        const employee_id = $("input[name='nm-employee-id']").val();
        const loan_id = $("input[name='loan-id']").val();
        const method = $(this).data("method");

        if( !( date && amount && comment && type && employee_id) ){return;}

        $.post({
            url: `/employees/loan/${method}`,
            dataType: 'json',
            global: false,
            // フォーム要素の内容をハッシュ形式に変換
            data: {
                "date" : date,
                "type" : type,
                "amount" : amount,
                "comment" : comment,
                "employee_id" : employee_id,
                "loan_id" : loan_id,
                "method" : method
            },
            timeout: 1000 * 30,
            beforeSend: function(xhr, settings){
                $(document).triggerHandler('ajaxStart');
            },

        })
        .then(
            function(data, textStatus, jqXHR) {
                console.log(data);
                if( data['result'] ){
                    if( data['result'] == "success" ) {
                        $(document).triggerHandler('ajaxStop', [true,
                            method=="create" ? "登録しました": method=="update" ? "更新しました" : "削除しました", ()=>{location.reload();}]);
                    }
                    if( data['result'] == "failure" ) {
                        $(document).triggerHandler('ajaxStop', [ false, data['message']]);
                    }
                }else{
                    $(document).triggerHandler('ajaxStop', [ false, "システムエラーが発生しました。"]);
                }
            },
            function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                $(document).triggerHandler('ajaxStop', [ false, "システムエラーが発生しました。"]);
            }
        );

    });

    {# 有給編集 #}
    $("#id-create-holiday, #id-update-holiday, #id-delete-holiday").on("click", function(){

        if( date = $("input[name='holiday-date']").val() ){
            $("#id-warn-holiday-date").addClass("d-none");
        }else{
            $("#id-warn-holiday-date").removeClass("d-none");
        }

        if( amount = $("input[name='holiday-amount']").val() ){
            $("#id-warn-holiday-amount").addClass("d-none");
        }else{
            $("#id-warn-holiday-amount").removeClass("d-none");
        }

        if( comment = $("input[name='holiday-comment']").val() ){
            $("#id-warn-holiday-comment").addClass("d-none");
        }else{
            $("#id-warn-holiday-comment").removeClass("d-none");
        }

        const type = $("input[name='holiday-type']:checked").val();
        const employee_id = $("input[name='nm-employee-id']").val();
        const holiday_id = $("input[name='holiday-id']").val();
        const method = $(this).data("method");

        if( !( date && amount && comment && type && employee_id) ){return;}

        $.post({
            url: `/employees/holiday/${method}`,
            dataType: 'json',
            global: false,
            // フォーム要素の内容をハッシュ形式に変換
            data: {
                "date" : date,
                "type" : type,
                "amount" : amount,
                "comment" : comment,
                "employee_id" : employee_id,
                "holiday_id" : holiday_id,
                "method" : method
            },
            timeout: 1000 * 30,
            beforeSend: function(xhr, settings){
                $(document).triggerHandler('ajaxStart');
            },

        })
        .then(
            function(data, textStatus, jqXHR) {
                console.log(data);
                if( data['result'] ){
                    if( data['result'] == "success" ) {
                        $(document).triggerHandler('ajaxStop', [true,
                            method=="create" ? "登録しました": method=="update" ? "更新しました" : "削除しました", ()=>{location.reload();}]);
                    }
                    if( data['result'] == "failure" ) {
                        $(document).triggerHandler('ajaxStop', [ false, data['message']]);
                    }
                }else{
                    $(document).triggerHandler('ajaxStop', [ false, "システムエラーが発生しました。"]);
                }
            },
            function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                $(document).triggerHandler('ajaxStop', [ false, "システムエラーが発生しました。"]);
            }
        );
    });


    {# 保険加入選択 保険料額フォームのenable/disableを制御 #}
    {# disable時、postは飛ばないので自動的にnullとなる #}
    $(document).on("change", "#insurance_type", function () {
        if( $(this).val() === "enable" ){
            $("input[name='insurance_bill']").prop("disabled", false);
            $("input[name='pension_bill']").prop("disabled", false);
        }else{
            $("input[name='insurance_bill']").prop("disabled", true);
            $("input[name='pension_bill']").prop("disabled", true);
        }
    })

});
</script>

{% endblock %}