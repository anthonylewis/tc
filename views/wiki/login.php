<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<label for="user_name">User Name</label>
<input type="text" name="user_name" value="<?php echo $this->user->user_name; ?>" />
<label for="password">Password</label>
<input type="password" name="password" value="" />
<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" /><br />
<input type="submit" class="action" name="action" value="login" />
<input type="submit" class="action" name="action" value="cancel" />
</form>
