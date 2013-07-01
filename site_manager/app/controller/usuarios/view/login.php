<? $this -> setCss('login')
         -> setJs('login'); ?>
<h1>Login</h1>

<?=$form->initForm()?>

<?=$form->field('usuario',"Usuario")?>
<br>
<?=$form->field('senha','Senha')?>
<br>
<?=$form->field('salva_cookies','Cookies')?>
<br>
<?=$form->radio('salvar','sim','SIM')?>
<?=$form->radio('salvar','nao','NAO')?>
<br>
<?=$form->field('texto','Texto')?>
<br>
<?=$form->field('enviar')?>

<?=$form->endForm()?>
