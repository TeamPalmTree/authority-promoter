<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Authority</title>
    <!-- css -->
    <?php echo Asset::css('reset.css'); ?>
    <?php echo Asset::css('promoter.css'); ?>
    <!-- js -->
    <?php echo Asset::js('jquery.min.js'); ?>
    <?php echo Asset::js('bootstrap.min.js'); ?>
</head>
<body>
<div class="promoter">
    <div class="promoter-login">
        <div class="promoter-login-logo">
            <img src="/assets/img/logo.png" />
        </div>
        <div class="promoter-login-container">
            <div class="promoter-login-button">
                <a href="<?php echo $provider_url; ?>?callback_url=<?php echo urlencode($callback_url); ?>&redirect_url=<?php echo urlencode($redirect_url); ?>">
                    <img src="/assets/shared/img/login.png" />
                </a>
            </div>
            <div class="promoter-login-form">
                <?php echo Form::open(array('id' => 'welcome-form', 'action' => 'promoter/login?&redirect_url=' . urlencode($redirect_url), 'class' => 'form-horizontal')); ?>
                    <div class="form-group">
                        <input name="username" type="text" class="form-control" placeholder="Username" />
                    </div>
                    <div class="form-group">
                        <input name="password" type="password" class="form-control" placeholder="Password" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default btn-primary" form="welcome-form">LOGIN</button>
                    </div>
                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() { $('input[name=username]').focus(); });
</script>
</body>
</html>