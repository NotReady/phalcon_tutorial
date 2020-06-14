{% extends "layout/template_in_service.volt" %}

{% block title %}給与確認{% endblock %}
{% block css_include %}
    <link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>

    /* 就業明細テーブル */
    .table_timedetail td:nth-of-type(1),
    .table_timedetail th:nth-of-type(1)
    {width: 25%;}
    .table_timedetail td:nth-of-type(2),
    .table_timedetail th:nth-of-type(2)
    {width: 25%;}
    .table_timedetail td:nth-of-type(3),
    .table_timedetail th:nth-of-type(3)
    {width: 15%;}
    .table_timedetail td:nth-of-type(4),
    .table_timedetail th:nth-of-type(4)
    {width: 15%;}
    .table_timedetail td:nth-of-type(5),
    .table_timedetail th:nth-of-type(5)
    {width: 20%;}

    /* 支給控除明細テーブル */
    .table_statement td:nth-of-type(1),
    .table_statement th:nth-of-type(1)
    {width: 50%;}
    .table_statement td:nth-of-type(2),
    .table_statement th:nth-of-type(2)
    {width: 50%;}

    .table_statement td{
        font-size: 1rem;
        vertical-align: middle;
    }

    /* 支給控除明細テーブル */
    .table_statement td:nth-of-type(1){
        text-align: right;
    }

    /* 支給控除明細テーブル */
    .table_statement td:nth-of-type(2){
        text-align: left;
    }

</style>

<div class="content_root">

    <h1 class="title flex_box">
        <div class="col-8">
            <a href="/employee/edit/{{ employee.id }}">{{ "%s %s" | format(employee.first_name, employee.last_name) }}</a>さん
            {{ "%d年 %d月の給与" | format(thisyear, thismonth) }}
        </div>
        <div class="col-4 text-right">
            <a href="/salary/{{ employee.id }}/{{ thisyear }}/{{ thismonth }}/cancel" class="btn btn-danger btn-fix">確定取消</a>
        </div>
    </h1>

    <div class="row">

        <div class="col-12">
            <div class=" col-12">
                <h2 class="subtitle flex_box flex_left">
                    今月のサマリー
                    <span class="badge-info highlight">確定済</span>
                    <span class="highlight">総支給額　<span class="highlight-text">{{ total_salary | number_format }}</span> 円</span>
                    <span class="highlight">出勤日数　<span class="highlight-text">{{ days_worked }}</span> 日</span>
                    <span class="highlight">出勤時間　<span class="highlight-text">{{ summary['timeAll'] }}</span></span>
                </h2>
            </div>
        </div>

        <div class="col-12">
            <div class="col-12">
            <h2 class="subtitle">支給</h2>
                <table class="table table_statement">
                    <thead>
                    <th>項目</th>
                    <th>金額</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>基本給</td>
                            <td>{{ salary.base_charge | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>賞与</td>
                            <td>{{ salary.bonus_charge | number_format }} 円</td>
                        </tr>

                        <tr>
                            <td>みなし残業額</td>
                            <td>{{ salary.overtime_charge | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>役職手当</td>
                            <td>{{ salary.skill_charge | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>課税交通費</td>
                            <td>{{ salary.transportation_expenses | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>日割交通費</td>
                            <td>{{ salary.transportation_expenses_by_day | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>非課税交通費</td>
                            <td>{{ salary.transportation_expenses_without_tax | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>非課税通信費</td>
                            <td>{{ salary.communication_charge_without_tax | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>住宅手当</td>
                            <td>{{ salary.house_charge | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>送迎手当</td>
                            <td>{{ salary.bus_charge | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>事務手当</td>
                            <td>{{ salary.officework_charge | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>その他支給</td>
                            <td>{{ salary.etc_charge | number_format }} 円</td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>支給合計</td>
                        <td><span class="highlight-text">{{ salary.getChargiesSummary() | number_format }} 円</span></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-12">
            <div class="col-12">
                <h2 class="subtitle">社会保険</h2>
                <table class="table table_statement">
                    <thead>
                    <th>項目</th>
                    <th>金額</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>社会保険料</td>
                            <td>{{ salary.insurance_bill | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>厚生年金料</td>
                            <td>{{ salary.pension_bill | number_format  }} 円</td>
                        </tr>
                        <tr>
                            <td>雇用保険料</td>
                            <td>
                                <div>{{ salary.employment_insurance_bill }} 円</div>
                                <div><small>事業主負担額 {{ salary.employment_insurance_owner | number_format }} 円</small></div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>社会保険合計</td>
                        <td><span class="highlight-text">{{ salary.getInsuranciesSummary() | number_format }} 円</span></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-12">
            <div class="col-12">
                <h2 class="subtitle">税引</h2>
                <table class="table table_statement">
                    <thead>
                    <th>項目</th>
                    <th>金額</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>課税対象</td>
                            <td>{{ salary.getSubjectToTaxSummary() | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>所得税</td>
                            <td>{{ salary.income_tax | number_format }} 円</td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>税引合計</td>
                        <td><span class="highlight-text">{{ salary.getTaxSummary() | number_format }} 円</span></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-12">
            <div class="col-12">
                <h2 class="subtitle">控除</h2>
                <table class="table table_statement">
                    <thead>
                    <th>項目</th>
                    <th>金額</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>家賃</td>
                            <td>{{ salary.rent_bill | number_format }} 円</td>

                        </tr>
                        <tr>
                            <td>電気代</td>
                            <td>{{ salary.electric_bill | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>ガス代</td>
                            <td>{{ salary.gas_bill | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>水道代</td>
                            <td>{{ salary.water_bill | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>弁当代</td>
                            <td>{{ salary.food_bill | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>その他控除</td>
                            <td>{{ salary.etc_bill | number_format }} 円</td>
                        </tr>
                        <tr>
                            <td>貸付返済額</td>
                            <td>
                                <div>{{ salary.loan_bill | number_format }} 円</div>
                                <div><small>控除後の貸付残高 {{ activeLoan | number_format }} 円</small></div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>控除合計</td>
                        <td><span class="highlight-text">{{ salary.getBillsSummary() | number_format }} 円</span></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-12">
            <div class="col-12">
                <h2 class="subtitle flex_box flex_left">
                    <span>出勤統計</span>
                    <span class="highlight">出勤日数　<span class="highlight-text">{{ days_worked }}</span> 日</span>
                    (
                    {% for unitname, time in howDaysWorkedOfDay %}
                        <span class="highlight">{{ unitname }}　<span class="highlight-text">{{ time }}</span> 日</span>
                    {% endfor %}
                    )
                </h2>
                <table class="table table_statement">
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

        <div class=" col-12">
            <div class="col-12">
                <h2 class="subtitle flex_box flex_bottom">
                    <div class="col-6">現場別 出勤内訳</div>
                    <div class="col-6 text-right">
                        <a href="/report/{{ employee.id }}/{{ thisyear }}/{{ thismonth }}/edit" class="btn btn-primary btn-fix" target="_blank">勤務表を開く</a>
                    </div>
                </h2>
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
                            <td class="text-right">{% if employee.employee_type === 'pro' %}-{% else %}{{ row.sum_charge | number_format }} 円{% endif %}</td>
                        </tr>
                    {% endfor %}
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>合計</td>
                        <td></td>
                        <td></td>
                        <td><span class="highlight-text">{{ summary['timeAll'] }}</span></td>
                        <td class="text-right"><span class="highlight-text">{% if employee.employee_type === 'pro' %}-{% else %}{{ summary['chargeAll'] | number_format }} 円{% endif %}</span></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block js_include %}
{% endblock %}