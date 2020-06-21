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

    .text-warning{
        color: firebrick;
    }

</style>

<div class="content_root" style="position: relative;">

<div style="width: 600px; padding: 40px 100px; position: absolute; top: 0%; left: 50%; transform: translate(-50%, 50%)" class="border border-secondary rounded btn-like">
    <h4 style="text-align: center; margin-bottom: 2.0rem; font-weight: bold;">管理者ログイン</h4>

    <?php if( empty($login_failure_message) === false ): ?>
        {% for message in login_failure_message %}
            <p class="text-warning">{{ message }}</p>
        {% endfor %}
    <?php endif; ?>

    <form  action="/login/check" method="post">
        <input type="hidden" name="<?php echo $this->security->getTokenKey() ?>"
               value="<?php echo $this->security->getToken() ?>"/>

        <div class="form-element-wrap">
                <label for="username">ログインID</label>
                <input name="username" type="text" class="form-control">
        </div>
        <div class="form-element-wrap">
                <label for="username">パスワード</label>
                <input name="password" type="password" class="form-control">
        </div>
        <div class="form-element-wrap" style="margin-top: 2.0rem;">
            <input style="width: 100%;" type="submit" value="ログイン" class="btn btn-primary" style="display: block;">
        </div>
    </form>
</div>
</div>

{% endblock %}