{% extends "layout/template_in_service.volt" %}

{% block title %}従業員編集{% endblock %}
{% block css_include %}
<link rel="stylesheet" type="text/css" href="/css/base.css" />
{% endblock %}
{% block js_include %}{% endblock %}
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

    .errorMessage{
        color: lightcoral;
    }
</style>

<div class="content_root">
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

{% endblock %}