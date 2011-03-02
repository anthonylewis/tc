<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<label for="user_name">User Name</label>
<input type="text" name="user_name" value="<?php echo $this->edit_user->user_name; ?>" />
<label for="full_name">Full Name</label>
<input type="text" name="full_name" value="<?php echo $this->edit_user->full_name; ?>" /><br />
<label for="email">E-Mail</label>
<input type="text" name="email" value="<?php echo $this->edit_user->email; ?>" /><br />
<label for="url">Web Site</label>
<input type="text" name="url" value="<?php echo $this->edit_user->url; ?>" /><br />
<input type="hidden" name="id" value="<?php echo $this->edit_user->id; ?>" />
<input type="hidden" name="action" value="save" />
<input type="hidden" name="controller" value="useradmin" />
<input type="submit" class="button" value="Submit" />
</form>
