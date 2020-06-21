{% extends "layout/template_in_service.volt" %}

{% block title %}トップ{% endblock %}
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

    .text-warning{
        color: firebrick;
    }

</style>

<div class="content_root" style="position: relative;">
    <h4 style="text-align: center; margin-bottom: 2.0rem; font-weight: bold;">ようこそ</h4>
</div>

{% endblock %}