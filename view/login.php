<?= self::render('header.php', ['pagetitle' => "{$app['name']} Login"]) ?>
<div class="panel panel-default control-box login-panel">
  <div class="panel-heading" style="text-align: center;"><?= $app['name'] ?> Login</div>
  <form action="" method="POST">
    <table border="1">
      <tr>
        <td>Username:</td>
        <td style="width: 100%;"><input type="text" name="username" autocomplete="off" style="width: 100%;"></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td style="width: 100%;"><input type="password" name="password" style="width: 100%;"></td>
      </tr>
      <tr>
        <td colspan="2"><input class="btn" type="submit" value="Login" style="width: 100%;"></td>
      </tr>
    </table>
  </form>
</div>
<?= self::render('footer.php') ?>
