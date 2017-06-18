{include file="admin/inc/header.tpl"}
    <body>
        <div class="admin_logo">
            <img src="/img/admin/logo.png" />
        </div>
        <div class="container">
            <form name="form_act" method="post" onSubmit="return formValidate('form_act', 0)" class="form-signin" role="form">
                <h3 class="form-signin-heading">{$smarty.const.PAGE_TITLE_ADMIN} Login</strong></h3>


                <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
                      <input class="form-control" name="user" type="text" placeholder="{#txtUser#}" required autofocus>
                    </div>
                </div>
            
                <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
                      <input class="form-control" type="password" placeholder="{#txtPass#}" name="pass" required style="margin: 0;">
                    </div>
                </div>
            
                <label class="checkbox">
                  <input type="checkbox" value="remember-me"> {#txtSaveAutoLogin#}
                </label>
                <button class="btn btn-lg btn-primary btn-block enter_btn" type="submit">{#txtEnter#}</button>
            </form>
            <div class="admin_logo">

            </div>
            {if $invalidLogin==1}{#txtInvalidLogin#}{/if}				
        </div>
    </body>
</html>
