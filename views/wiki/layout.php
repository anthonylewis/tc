<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $this->title; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="styles/screen.css"/>
<link rel="stylesheet" type="text/css" media="print" href="styles/print.css"/>
</head>
<body>
<div class="wholepage">
<div class="header">
<h1><?php echo $this->title; ?></h1>
<?php echo $this->navbar(); ?>
</div>
<div class="container">

<?php echo $page_content ?>

</div>
<div class="footer">
<p>Powered by <a href="http://thoughtcollector.com">Thought Collector</a>.</p>
</div>
</div>
</body>
</html>
