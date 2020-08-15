{% extends "layout/template_out_service.volt" %}

{% block title %}ログイン{% endblock %}
{% block css_include %}{% endblock %}
{% block js_include %}{% endblock %}
{% block content_body %}

<style>
    .form-element-wrap{
        margin: 0 auto 15px 0;
    }

    .errorMessage{
        color: lightcoral;
    }

</style>

<div class="content_root" style="position: relative;">

    <div style="width: 600px; padding: 40px 100px; position: absolute; top: 0%; left: 50%; transform: translate(-50%, 50%)" class="border border-secondary rounded btn-like">

        <h4 style="text-align: center; margin-bottom: 2.0rem; font-weight: bold;">ログイン</h4>

        {% if login_failure_message is not empty %}
            <p class="errorMessage">{{ login_failure_message }}</p>
        {% endif %}

        {{ form('/login/check', 'method': 'post') }}
        {{ hidden_field(security.getTokenKey(), "value": security.getToken()) }}

            <div class="mb-4">
                {{ form.label('username', ['class' : 'form-label']) }}
                {{ form.render('username') }}
                {{ form.messages('username') }}
            </div>

            <div class="mb-4">
                {{ form.label('password', ['class' : 'form-label']) }}
                {{ form.render('password') }}
                {{ form.messages('password') }}
            </div>

            {{ form.render('submit') }}

        {{ endform() }}

    </div>
</div>

{% endblock %}