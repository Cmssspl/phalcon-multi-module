<?php echo Tag::form('session/start') ?>

    <label for="email">Username/Email</label>
    <?php echo Tag::textField(array("email", "size" => "30")) ?>

    <label for="password">Password</label>
    <?php echo Tag::passwordField(array("password", "size" => "30")) ?>

    <?php echo Tag::submitButton(array('Login')) ?>

</form>