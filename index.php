<?php
/**
 * @author Glaubert Dumpierre
 * @since 15/08/2014
 */
include './classes/sessao.php';

/**
 * A sessão [login] está sendo referenciada ao objeto aqui. 
 */
$logado = (object)$_SESSION['login'];
?>
<html>
<head>
	<title>Estruturado - Inicial</title>
</head>
<body>
	<?php echo "Seja bem vindo(a) <b>{$logado->nome}</b><br />"; ?>
	<?php echo "Usuario: <b>{$logado->nome}</b> / Acesso: <b>{$logado->acesso}</b> / Endereço IP: <b>{$logado->ip}</b>"; ?>
	<form name="frmSair" action="sair.php">
		<input type="button" value="Pessoa" onClick="javascript: window.location.href='pessoa.php'" >
		<input type="button" value="Usuário" onClick="javascript: window.location.href='usuario-busca.php'" >
		<input type="submit" value="Sair" />
	</form> 
</body>
</html>
