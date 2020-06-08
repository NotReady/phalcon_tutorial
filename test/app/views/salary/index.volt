{% extends "layout/template_in_service.volt" %}

{% block title %}給与登録{% endblock %}
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
    {width: 30%;}
    .table_statement td:nth-of-type(2),
    .table_statement th:nth-of-type(2)
    {width: 30%;}
    .table_statement td:nth-of-type(3),
    .table_statement th:nth-of-type(3)
    {width: 20%; text-align: right}
    .table_statement td:nth-of-type(4),
    .table_statement th:nth-of-type(4)
    {width: 20%; text-align: left}

    .table_statement td{
        font-size: 1rem;
        vertical-align: middle;
    }

    .table_statement td input{
        display: inline-block !important;
    }

    .highlight{
        font-weight: normal;
        font-size: 1.0rem;
        margin: 0 1.0rem;
    }

    .highlight-text{
        font-weight: 700;
        font-size: 1.4rem;
    }


</style>

<div class="content_root">

    <h1 class="title">{{ "%s %sさん %d年 %d月の給与登録" |format(employee.first_name, employee.last_name ,thisyear, thismonth) }}</h1>

    <div class="row">

        <div class="col-12">
            <div class=" col-12">
                <p class="subtitle">
                    今月のサマリー
                    <span class="highlight">出勤日数　<span class="highlight-text">{{ days_worked }}</span> 日</span>
                    <span class="highlight">出勤時間　<span class="highlight-text">{{ summary['timeAll'] }}</span></span>
                </p>
            </div>
        </div>

        <div class="col-12">

            <div class=" col-12">
                <p class="subtitle">時間内訳</p>
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
                        <td>{{ summary['timeAll'] }}</td>
                        <td class="text-right">{% if employee.employee_type === 'pro' %}-{% else %}{{ summary['chargeAll'] | number_format }} 円{% endif %}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>

        <div class="col-12">
            <div class="col-12">
            <p class="subtitle">支給</p>
                <table class="table table_statement">
                    <thead>
                    <th>項目</th>
                    <th>金額</th>
                    <th></th>
                    <th></th>
                    </thead>
                    <tbody>
                        <tr>
                            {% if employee.employee_type === 'pro' %}
                                {# 社員 #}
                                <td>基本給</td>
                                <td>{{ form.render('base_charge') }} 円</td>
                                <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                                <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                            {% else %}
                                {# 社員以外は時間給を参照 #}
                                <td>基本給</td>
                                <td><input readonly type="number" class="form-control text-right" value="{{ summary['chargeAll'] }}"/> 円</td>
                                <td></td>
                                <td></td>
                            {% endif %}
                        </tr>
                        <tr>
                            <td>みなし残業額</td>
                            <td>{{ form.render('overtime_charge') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>役職手当</td>
                            <td>{{ form.render('skill_charge') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>課税交通費</td>
                            <td>{{ form.render('transportation_expenses') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>日割交通費</td>
                            <td>{{ form.render('transportation_expenses_by_day') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>非課税交通費</td>
                            <td>{{ form.render('transportation_expenses_without_tax') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>非課税通信費</td>
                            <td>{{ form.render('communication_charge_without_tax') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>住宅手当</td>
                            <td>{{ form.render('house_charge') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>送迎手当</td>
                            <td>{{ form.render('bus_charge') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>事務手当</td>
                            <td>{{ form.render('officework_charge') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>その他支給</td>
                            <td>{{ form.render('etc_charge') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>合計</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {% if employee.insurance_type === 'enable' %}
            <div class="col-12">
                <div class="col-12">
                    <p class="subtitle">社会保険</p>
                    <table class="table table_statement">
                        <thead>
                        <th>項目</th>
                        <th>金額</th>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>社会保険料</td>
                                <td>{{ form.render('insurance_bill') }} 円</td>
                                <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                                <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                            </tr>
                            <tr>
                                <td>厚生年金料</td>
                                <td>{{ form.render('pension_bill') }} 円</td>
                                <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                                <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                            </tr>
                            <tr>
                                <td>雇用保険料</td>
                                <td>{{ form.render('employment_insurance_bill') }} 円</td>
                                <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                                <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                            </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td>合計</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        {% endif %}

        <div class="col-12">
            <div class="col-12">
                <p class="subtitle">控除</p>
                <table class="table table_statement">
                    <thead>
                    <th>項目</th>
                    <th>金額</th>
                    <th></th>
                    <th></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>所得税</td>
                            <td>{{ form.render('income_tax') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>

                        <tr>
                            <td>家賃</td>
                            <td>{{ form.render('rent_bill') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>電気代</td>
                            <td>{{ form.render('electric_bill') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>ガス代</td>
                            <td>{{ form.render('gas_bill') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>水道代</td>
                            <td>{{ form.render('water_bill') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>弁当代</td>
                            <td>{{ form.render('food_bill') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                        <tr>
                            <td>その他控除</td>
                            <td>{{ form.render('etc_bill') }} 円</td>
                            <td><input type="button" class="btn btn-danger" value="元に戻す"> </td>
                            <td><input type="button" class="btn btn-primary" value="保存する"> </td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>合計</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
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