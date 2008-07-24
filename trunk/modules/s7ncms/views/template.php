<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <?php echo $head ?>
</head>

<body>
    <div id="header">
        <h1>My Website</h1>
    </div>

    <div id="content">
        <div id="left">
            <div id="menu">
                <?php echo new Menu; ?>
            </div>

            <div class="left_column">
            	<?php echo Sidebar::instance() ?>
            </div>
        </div>

        <div id="right">
            <?php echo $content; ?>
        </div>

        <div style="clear: both;">&nbsp;</div>
    </div>

    <div id="footer">
        <p>powered by <a href="http://www.s7n.de">S7Ncms</a></p>
    </div>
</body>
</html>
