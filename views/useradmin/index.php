<table>
<tr><th>User Name</th><th>Full Name</th><th>Actions</th></tr>
<?php
while( $this->edit_user->next() ) {
?>
<tr>
  <td><?php echo $this->edit_user->user_name; ?></td>
  <td><?php echo $this->edit_user->full_name; ?></td>
  <td>
  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $this->edit_user->id; ?>" />
    <input type="hidden" name="controller" value="useradmin" />
    <input type="submit" class="inline_button" name="action" value="Password" />
    <input type="submit" class="inline_button" name="action" value="Edit" />
    <input type="submit" class="inline_button" name="action" value="Delete" />
  </form>
  </td>
</tr>
<?php
}
?>
</table>
