{% extends "layout/template_in_service.volt" %}

{% block title %}給与登録{% endblock %}
{% block css_include %}{% endblock %}
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
    {width: 40%;}
    .table_statement td:nth-of-type(3),
    .table_statement th:nth-of-type(3)
    {width: 30%;}

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
        text-align: right;
    }

    /* 支給控除明細テーブル */
    .table_statement td:nth-of-type(2) input{
        display: inline-block !important;
        width: 90% !important;
    }

    /* 支給控除明細テーブル */
    .table_statement td:nth-of-type(3) input{
        margin-right: 0.6rem;
    }

    /* 時間内訳テーブル */
    .table_timeunit td:nth-of-type(1),
    .table_timeunit th:nth-of-type(1)
    {width: 50%;}
    .table_timeunit td:nth-of-type(2),
    .table_timeunit th:nth-of-type(2)
    {width: 50%;}

    /* 支給控除明細テーブル */
    .table_timeunit td:nth-of-type(1){
        text-align: right;
    }

    /* 支給控除明細テーブル */
    .table_timeunit td:nth-of-type(2){
        text-align: left;
    }

</style>

<div class="content_root">

    <h1 class="title flex_box">
        <div class="col-8">
            <a href="/employee/edit/{{ employee.id }}">{{ "%s %s" | format(employee.first_name, employee.last_name) }}</a>さん
            {{ "%d年 %d月の給与編集" | format(thisyear, thismonth) }}
        </div>
        <div class="col-4 text-right">
            <a href="/salary/{{ employee.id }}/{{ thisyear }}/{{ thismonth }}/fix" class="btn btn-primary btn-fix">確定する</a>
        </div>
    </h1>

    <div class="row">

        <div class="col-12">
            <div class=" col-12">
                <h2 class="subtitle flex_box flex_left">
                    今月のサマリー
                    <span class="badge-alert highlight">未確定</span>
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
                    <th></th>
                    </thead>
                    <tbody>
                        <tr>
                            {% if employee.employee_type === 'pro' %}
                                {# 社員 #}
                                <td>基本給</td>
                                <td>{{ form.render('base_charge') }} 円</td>
                                <td>
                                    {% if salary.fixed is 'temporary' %}
                                        <div class="btn-wrap">
                                            <input type="button" class="btn btn-primary btn-update" value="保存する">
                                            {% if salary_origin.base_charge is defined %}
                                                <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                            {% endif %}
                                        </div>
                                    {% endif %}
                                </td>
                            {% else %}
                                <td>基本給</td>
                                <td><input readonly type="number" class="form-control text-right" value="{{ salary.base_charge }}"/> 円</td>
                                <td></td>
                            {% endif %}
                        </tr>
                        <tr>
                            <td>賞与</td>
                            <td>{{ form.render('bonus_charge') }} 円</td>
                            <td>
                                {% if salary.fixed is 'temporary' %}
                                    <div class="btn-wrap">
                                        <input type="button" class="btn btn-primary btn-update" value="保存する">
                                        {% if salary_origin.bonus_charge is defined %}
                                            <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                        {% endif %}
                                    </div>
                                {% endif %}
                            </td>
                        </tr>

                        <tr>
                            <td>みなし残業額</td>
                            <td>{{ form.render('overtime_charge') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.overtime_charge is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>役職手当</td>
                            <td>{{ form.render('skill_charge') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.skill_charge is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>課税交通費</td>
                            <td>{{ form.render('transportation_expenses') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.transportation_expenses is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>日割交通費</td>
                            <td>{{ form.render('transportation_expenses_by_day') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.transportation_expenses_by_day is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>非課税交通費</td>
                            <td>{{ form.render('transportation_expenses_without_tax') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.transportation_expenses_without_tax is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>非課税通信費</td>
                            <td>{{ form.render('communication_charge_without_tax') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.communication_charge_without_tax is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>住宅手当</td>
                            <td>{{ form.render('house_charge') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.house_charge is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>送迎手当</td>
                            <td>{{ form.render('bus_charge') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.bus_charge is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>事務手当</td>
                            <td>{{ form.render('officework_charge') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.officework_charge is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>その他支給</td>
                            <td>{{ form.render('etc_charge') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.officework_charge is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>支給合計</td>
                        <td class="text-right"><span class="highlight-text">{{ salary.getChargiesSummary() | number_format }} 円</span></td>
                        <td></td>
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
                    <th>操作</th>
                    </thead>
                    <tbody>

                        {% if employee.insurance_type === 'enable' %}
                            <tr>
                                <td>社会保険料</td>
                                <td>{{ form.render('insurance_bill') }} 円</td>
                                <td>
                                    <div class="btn-wrap">
                                        <input type="button" class="btn btn-primary btn-update" value="保存する">
                                        {% if salary_origin.insurance_bill is defined %}
                                            <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                        {% endif %}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>厚生年金料</td>
                                <td>{{ form.render('pension_bill') }} 円</td>
                                <td>
                                    <div class="btn-wrap">
                                        <input type="button" class="btn btn-primary btn-update" value="保存する">
                                        {% if salary_origin.pension_bill is defined %}
                                            <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                        {% endif %}
                                    </div>
                                </td>
                            </tr>
                        {% else %}
                            <tr>
                                <td>社会保険料</td>
                                <td class="text-right">未加入</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>厚生年金料</td>
                                <td class="text-right">未加入</td>
                                <td></td>
                            </tr>
                        {% endif %}
                        <tr>
                            <td>雇用保険料</td>
                            <td>
                                {{ form.render('employment_insurance_bill') }} 円
                                <div class="text-right"><small>事業主負担額 {{ salary.employment_insurance_owner | number_format }} 円</small></div>
                            </td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.employment_insurance_bill is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>社会保険合計</td>
                        <td class="text-right"><span class="highlight-text">{{ salary.getInsuranciesSummary() | number_format }} 円</span></td>
                        <td></td>
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
                    <th>操作</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>課税対象</td>
                            <td><input readonly type="number" class="form-control text-right" value="{{ salary.getSubjectToTaxSummary() }}"/> 円</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>所得税</td>
                            <td>{{ form.render('income_tax') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.income_tax is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>税引合計</td>
                        <td class="text-right"><span class="highlight-text">{{ salary.getTaxSummary() | number_format }} 円</span></td>
                        <td></td>
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
                    <th>操作</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>家賃</td>
                            <td>{{ form.render('rent_bill') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.rent_bill is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>電気代</td>
                            <td>{{ form.render('electric_bill') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.electric_bill is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>ガス代</td>
                            <td>{{ form.render('gas_bill') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.gas_bill is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>水道代</td>
                            <td>{{ form.render('water_bill') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.water_bill is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>弁当代</td>
                            <td>{{ form.render('food_bill') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.food_bill is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>その他控除</td>
                            <td>{{ form.render('etc_bill') }} 円</td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.etc_bill is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>貸付返済額</td>
                            <td>
                                {{ form.render('loan_bill') }} 円
                                <div class="text-right"><small>控除後の貸付残高 {{ activeLoan | number_format }} 円</small></div>
                            </td>
                            <td>
                                <div class="btn-wrap">
                                    <input type="button" class="btn btn-primary btn-update" value="保存する">
                                    {% if salary_origin.loan_bill is defined %}
                                        <input type="button" class="btn btn-danger btn-undo" value="元に戻す">
                                    {% endif %}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>控除合計</td>
                        <td class="text-right"><span class="highlight-text">{{ salary.getBillsSummary() | number_format }} 円</span></td>
                        <td></td>
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
                <table class="table table_timeunit">
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


</div>
{% endblock %}

{% block js_include %}
<script>

    const updateAction = "/salary/{{ employee.id }}/{{ thisyear }}/{{ thismonth }}/update";
    const undoAction = "/salary/{{ employee.id }}/{{ thisyear }}/{{ thismonth }}/undo";
    const method = "POST";

    $(function(){
        $(".btn-update").on("click",  function () {
            // ポストキャンセル
            event.preventDefault();

            const $input = $(this).parents("tr").find("input");
            const key = $input.attr('name');
            const value = $input.val();

            $.ajax({
                url: updateAction,
                type: method,
                dataType: "html",
                data: {
                    "name" : key,
                    "value" : value
                },
                timeout: 1000 * 10,
                beforeSend: function(xhr, settings){
                    $(this).attr("disable", true);
                },
                complete: function(xhr, textStatus){
                    $(this).attr("disable", false);
                },
                success: function (result, textStatus, xhr) {
                    alert('保存しました。');
                    location.reload();
                },
                error: function(xhr, textStatus, error){
                    alert('失敗しました。');
                }
            });
        })
    });

    $(".btn-undo").on("click",  function () {
        // ポストキャンセル
        event.preventDefault();

        const $input = $(this).parents("tr").find("input");
        const key = $input.attr('name');
        const value = $input.val();

        $.ajax({
            url: undoAction,
            type: method,
            dataType: "html",
            data: {
                "name" : key,
                "value" : value
            },
            timeout: 1000 * 10,
            beforeSend: function(xhr, settings){
                $(this).attr("disable", true);
            },
            complete: function(xhr, textStatus){
                $(this).attr("disable", false);
            },
            success: function (result, textStatus, xhr) {
                alert('保存しました。');
                location.reload();
            },
            error: function(xhr, textStatus, error){
                alert('失敗しました。');
            }
        });
    });

</script>

{% endblock %}