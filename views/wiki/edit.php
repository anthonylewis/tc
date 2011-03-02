<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<label for="title">Title:</label>
<input type="text" id="title" name="title" value="<?php echo $this->page->title; ?>" />
<label for="text">Text:</label>
<textarea id="text" name="text">
<?php echo $this->text; ?>
</textarea>
<label for="tags">Tags:</label>
<input type="text" id="tags" name="tags" value="<?php echo implode( ' ', $this->page->get_tags() ); ?>" />
<input type="submit" id="action" name="action" value="save" />
<input type="submit" id="action" name="action" value="delete" />
<input type="submit" id="action" name="action" value="cancel" />
<input type="hidden" id="id" name="id" value="<?php echo $this->page->id; ?>" />
<input type="hidden" id="page" name="page" value="<?php echo $this->page->title; ?>" />
</form>
