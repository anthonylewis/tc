<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<label for="password">Password</label>
<input type="password" name="password" value="" />
<label for="full_name">Confirm Password</label>
<input type="password" name="confirm" value="" /><br />
<input type="hidden" name="id" value="<?php echo $this->edit_user->id; ?>" />
<input type="hidden" name="action" value="change_password" />
<input type="hidden" name="controller" value="useradmin" />
<input type="submit" class="button" value="Submit" />
</form>
