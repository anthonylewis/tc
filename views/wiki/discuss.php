<div class="sidebar">
<?php echo $this->sidebar(); ?>
</div>

<div class="content">
<?php
if ( !empty( $this->message ) ) {
?>
<p><?php echo $this->message; ?></p>
<?php
}
?>
<?php
while ( $this->discussion->next() ) {
?>
<div>
<p><a href="<?php echo $this->discussion->url; ?>"><?php echo $this->discussion->author; ?></a> said:</p>
<?php echo wiki_format( $this->discussion->comment ) . "\n"; ?>
</div>
<?php
}
?>

<hr noshade="noshade" size="1" />

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label for="author">Name:</label>
<input type="text" name="author" value="<?php echo $this->user->full_name; ?>" />
<label for="email">E-Mail:</label>
<input type="text" name="email" value="<?php echo $this->user->email; ?>" />
<label for="url">Website:</label>
<input type="text" name="url" value="<?php echo $this->user->url; ?>" />
<label for="comment">Comment:</label>
<textarea id="comment" name="comment"></textarea>
<input type="hidden" name="page" value="<?php echo $this->page->title; ?>" />
<input type="submit" name="action" value="comment" />
</div>
