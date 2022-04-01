<?php

use Rxak\Framework\Session\MessageBag;
use Rxak\Framework\Templating\Components\Csrf;
?>
<form method="post" action="/user/register">
    <?= new Csrf() ?>
    <?= MessageBag::getInstance()->hasValidationError('email') ? 'Failed' : '' ?><input type="text" name="email" /><br>
    <?= MessageBag::getInstance()->hasValidationError('username') ? 'Failed' : '' ?><input type="text" name="username" /><br>
    <input type="text" name="password" /><br>
    <input type="submit">
</form>