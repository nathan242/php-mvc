<?= self::render('header.php', ['topbar' => true, 'pagepath' => [['MAIN', $_SERVER['REQUEST_URI']]], 'loginuser' => $loginuser]) ?>
<p>Test Page</p>
<?= self::render('footer.php') ?>
