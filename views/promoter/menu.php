<?php if (Auth::check()): ?>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Auth::get_screen_name(); ?> <b class="caret"></b></a>
    <ul class="dropdown-menu">
        <?php if (isset($admin_menu)): ?>
            <?php echo $admin_menu; ?>
            <li class="divider"></li>
        <?php endif; ?>
        <li><a href="/promoter/logout"><span class="glyphicon glyphicon-off"></span> LOG OUT</a></li>
    </ul>
</li>
<?php else: ?>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">LOGIN <b class="caret"></b></a>
    <ul id="login-menu" class="dropdown-menu">
        <li>
            <a href="<?php echo $provider_url; ?>?callback_url=<?php echo urlencode($callback_url); ?>&redirect_url=<?php echo urlencode($redirect_url); ?>">
                <img src="/assets/shared/img/login/button-menu.png" />
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a>
                <?php echo Form::open(array('id' => 'promoter-form', 'action' => 'promoter/login?&redirect_url=' . urlencode($redirect_url), 'class' => 'form-horizontal')); ?>
                <input name="username" type="text" class="form-control" placeholder="USERNAME" />
                <input name="password" type="password" class="form-control" placeholder="PASSWORD" />
                <?php echo Form::close(); ?>
            </a>
        </li>
        <li><a><button type="submit" class="btn btn-default btn-primary" form="promoter-form">LOGIN</button></a></li>
    </ul>
</li>
<?php endif; ?>