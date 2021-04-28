{% extends "layout/template_in_service.volt" %}

{% block title %}給与確認{% endblock %}
{% block css_include %}{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>

    .bold{
        font-weight: bold !important;
    }

    .printable{

        margin: 0 auto;
    }

    .table_salary_printable *{
        font-size: 14px !important;
    }

    .table_salary_printable tr.header > th{
        background-color: #f0f0f0;
        font-weight: normal;
    }

    .table_salary_printable tr.body > td{
        text-align: right;
    }

    .table_salary_printable td,
    .table_salary_printable th{
        border: 1px solid #aaa;
        padding: 2px 4px;
        width: 140px;
    }

    .suffix_days:after{
        content: " 日";
    }
    .suffix_money:after{
        content: " 円";
    }

</style>

<div class="content_root">

    <div class="printable">
        <div class="flex_box mb-4">
            <div class="col-6">
                <span style="border-bottom: 1px solid #aaa;">{{ "%s %s" | format(employee.first_name, employee.last_name) }} 殿</span>
            </div>
            <div class="col-6 text-right">
                {{ "%d年 %d月度の給与明細" | format(thisyear, thismonth) }}
            </div>
        </div>

        <p>支給項目</p>
        <table class="table_salary_printable">
            <tr class="header">
                <th>基本給</th>
                <th>時間外勤務</th>
                <th></th>
                <th>役職手当</th>
                <th>住宅手当</th>
                <th>送迎手当</th>
                <th>事務手当</th>
                <th>課税通勤費</th>
                <th>日割通勤費</th>
            </tr>
            <tr class="body">
                <td class="suffix_money">{{ salary.base_charge | number_format }}</td>
                <td class="suffix_money">{{ salary.overtime_charge | number_format }}</td>
                <td></td>
                <td class="suffix_money">{{ salary.skill_charge | number_format }}</td>
                <td class="suffix_money">{{ salary.house_charge | number_format }}</td>
                <td class="suffix_money">{{ salary.bus_charge | number_format }}</td>
                <td class="suffix_money">{{ salary.officework_charge | number_format }}</td>
                <td class="suffix_money">{{ salary.transportation_expenses | number_format }}</td>
                <td class="suffix_money">{{ salary.transportation_expenses_by_day | number_format }}</td>
            </tr>
            <tr class="header">
                <th></th>
                <th></th>
                <th></th>
                <th>賞与</th>
                <th></th>
                <th></th>
                <th></th>
                <th>非課税通勤費</th>
                <th>その他支給</th>
            </tr>
            <tr class="body">
                <td></td>
                <td></td>
                <td></td>
                <td class="suffix_money">{{ salary.bonus_charge | number_format }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="suffix_money">{{ salary.transportation_expenses_without_tax | number_format }}</td>
                <td class="suffix_money">{{ salary.etc_charge | number_format }}</td>
            </tr>
            <tr class="header">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>非課税通信費</th>
                <th class="bold">支給額合計</th>
            </tr>
            <tr class="body">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="suffix_money">{{ salary.communication_charge_without_tax | number_format }}</td>
                <td class="bold suffix_money">{{ salary.getChargiesSummary() | number_format }}</td>
            </tr>
        </table>

        <p>控除項目</p>
        <table class="table_salary_printable">
            <tr class="header">
                <th>健康保険料</th>
                <th>厚生年金料</th>
                <th>雇用保険料</th>
                <th class="bold">社保合計</th>
                <th>課税対象額</th>
                <th>所得税</th>
                <th>住民税</th>
                <th></th>
                <th></th>
            </tr>
            <tr class="body">
                <td class="suffix_money">{{ salary.insurance_bill | number_format }}</td>
                <td class="suffix_money">{{ salary.pension_bill | number_format  }}</td>
                <td class="suffix_money">{{ salary.employment_insurance_bill }}</td>
                <td class="bold suffix_money">{{ salary.getInsuranciesSummary() | number_format }}</td>
                <td class="bold suffix_money">{{ salary.getSubjectToTaxSummary() | number_format }}</td>
                <td class="suffix_money">{{ salary.income_tax | number_format }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="header">
                <th>家賃</th>
                <th>電気代</th>
                <th>ガス代</th>
                <th>水道代</th>
                <th>弁当代</th>
                <th>前払金</th>
                <th>その他控除</th>
                <th></th>
                <th></th>
            </tr>
            <tr class="body">
                <td class="suffix_money">{{ salary.rent_bill | number_format }}</td>
                <td class="suffix_money">{{ salary.electric_bill | number_format }}</td>
                <td class="suffix_money">{{ salary.gas_bill | number_format }}</td>
                <td class="suffix_money">{{ salary.water_bill | number_format }}</td>
                <td class="suffix_money">{{ salary.food_bill | number_format }}</td>
                <td class="suffix_money">{{ salary.loan_bill | number_format }}</td>
                <td class="suffix_money">{{ salary.etc_bill | number_format }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="header">
                <th>欠勤控除</th>
                <th>勤怠控除</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="bold">控除額合計</th>
            </tr>
            <tr class="body">
                <td class="suffix_money">{{ salary.attendance_deduction1 | number_format }}</td>
                <td class="suffix_money">{{ salary.attendance_deduction2 | number_format }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="bold suffix_money">{{ ( salary.getInsuranciesSummary() + salary.getTaxSummary() + salary.getBillsSummary() ) | number_format }}</td>
            </tr>
        </table>

        <p>勤怠</p>
        <table class="table_salary_printable">
            <tr class="header">
                <th>平日日数</th>
                <th>平日時間</th>
                <th>平日時間外</th>
                <th>土曜日数</th>
                <th>土曜時間</th>
                <th>日曜日数</th>
                <th>日曜時間</th>
                <th>祝祭日数</th>
                <th>祝祭時間</th>
            </tr>
            <tr class="body">
                <td class="suffix_days">{{ howDaysWorkedOfDay['平日'] | number_format }}</td>
                <td>{{ summary['timeunits']['平日時間内']['time'] }}</td>
                <td>{{ summary['timeunits']['平日時間外']['time'] }}</td>
                <td class="suffix_days">{{ howDaysWorkedOfDay['土曜日'] | number_format }}</td>
                <td>{{ summary['timeunits']['土曜日出勤']['time'] }}</td>
                <td class="suffix_days">{{ howDaysWorkedOfDay['日曜日'] | number_format }}</td>
                <td>{{ summary['timeunits']['日曜日出勤']['time'] }}</td>
                <td class="suffix_days">{{ howDaysWorkedOfDay['祝祭日'] | number_format }}</td>
                <td>{{ summary['timeunits']['祝祭日出勤']['time'] }}</td>
            </tr>
            <tr class="header">
                <th>欠勤日数</th>
                <th>遅刻日数</th>
                <th>遅刻時間</th>
                <th>早退日数</th>
                <th>早退時間</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr class="body">
                <td class="suffix_days">{{ days_Absenteeism | number_format }}</td>
                <td class="suffix_days">{{ missing_report['be_late']['days'] | number_format }}</td>
                <td>{{ missing_report['be_late']['time'] }}</td>
                <td class="suffix_days">{{ missing_report['Leave_early']['days'] | number_format }}</td>
                <td>{{ missing_report['Leave_early']['time'] }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="header">
                <th>今月有給消化日数</th>
                <th>残有給日数</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="bold">差引支給額</th>
            </tr>
            <tr class="body">
                <td class="suffix_days">{{ days_holiday | number_format }}</td>
                <td class="suffix_days">{{ days_remain_holidays }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="bold suffix_money">{{ total_salary | number_format }}</td>
            </tr>
        </table>
    </div>

</div>
{% endblock %}

{% block js_include %}
{% endblock %}