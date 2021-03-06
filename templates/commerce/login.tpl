{$this->registerMeta("ROBOTS", "NOINDEX, NOFOLLOW")}

<div id="titleExt"><span class="ext">{lang('lang_login_page')}</span></h5></div>

{if validation_errors() OR $info_message}
    <div class="errors">
        {validation_errors()}
        {$info_message}
    </div>
{/if}

<br/>

<form action="" method="post" class="form">

    <div class="fieldName">{lang('lang_email')}</div>
    <div class="field">
        <input type="text" id="username" size="30" name="email" value="Введите Ваш логин" onfocus="if (this.value == 'Введите Ваш логин')
                            this.value = '';" onblur="if (this.value == '')
                            this.value = 'Введите Ваш логин';" />
    </div>
    <div class="clear"></div>

    <div class="fieldName">{lang('lang_password')}</div>
    <div class="field">
        <input type="password" size="30" name="password" id="password" value="{lang('lang_password')}" onfocus="if (this.value == '{lang('lang_password')}')
                            this.value = '';" onblur="if (this.value == '')
                            this.value = '{lang('lang_password')}';"/>
    </div>
    <div class="clear"></div>

    {if $cap_image}
        <div class="fieldName">{$cap_image}</div>
        {if $captcha_type == 'captcha'}
            <div class="field">
                <input type="text" name="captcha" id="captcha" value="{lang('lang_captcha')}" onfocus="if (this.value == '{lang('lang_captcha')}')
                            this.value = '';" onblur="if (this.value == '')
                            this.value = '{lang('lang_captcha')}';"/>
            </div>
        {/if}
        <div class="clear"></div>
    {/if}

    <div class="fieldName"></div>
    <div class="field">
        <label><input type="checkbox" name="remember" value="1" id="remember" /> {lang('lang_remember_me')}</label>
    </div>
    <div class="clear"></div>

    <div class="fieldName"></div>
    <div class="field">
        <input type="submit" id="submit" class="submit" value="{lang('lang_submit')}" />
    </div>
    <div class="clear"></div>

    <div class="fieldName"></div>
    <div class="field">
        <a href="{site_url($modules.auth . '/forgot_password')}">{lang('lang_forgot_password')}</a>
        &nbsp;
        <a href="{site_url($modules.auth . '/register')}">{lang('lang_register')}</a>
    </div>
    <div class="clear"></div>

    {form_csrf()}
</form>
