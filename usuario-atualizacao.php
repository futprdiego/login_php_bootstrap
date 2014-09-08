<?php
/**
 * @author Glaubert Dumpierre
 * @since 04/09/2014
 */
include 'classes/sessao.php';

include 'classes/conexao.php';

try
{
	$conexao = new Db();

	/*
	 * VERIFICA SE O USUÁRIO LOGADO É ADMINISTRADOR
	 */
	if ( $_SESSION['login']['administrador'] == false )
	{
		throw new Exception('Somente administradores podem cadastrar usuários!', 1);
	}

	$usuario = $_GET['usuario'] ? $_GET['usuario'] : null;

	if ( is_null($usuario) )
	{
		throw new Exception('O usuário deve ser informado!', 1);
	}

	$sqlUsuario = "SELECT * FROM usuarios INNER JOIN pessoas USING ( idPessoa ) WHERE usuario = '{$usuario}';";

	$resultadoUsuario = $conexao->query($sqlUsuario);

	if ( $resultadoUsuario->num_rows < 1 )
	{
		throw new Exception("Usuário não encontrado!", 1);
	}
 
	$dadosUsuario = $resultadoUsuario->fetch_object();

	if ( $_SESSION['login']['administrador'] == 'false' )
	{
		throw new Exception('Somente administradores podem atualizar usuários!', 1);
	}
	
	if ( $dadosUsuario->administrador == 'true' && $dadosUsuario->usuario != $_SESSION['login']['usuario'] )
	{
		throw new Exception("Um administrador não pode modificar os dados de outro administrador!");
	}

	/*
	 * SE A REQUISIÇÃO ESTIVER VINDO VIA POST
	 */
	if ( $_POST )
	{
		$usuario = strlen($_POST['usuario']) > 0 ? $_POST['usuario'] : null;
		
		$senha = strlen($_POST['senha']) > 0 ? $_POST['senha'] : null;

		$situacao = strlen($_POST['situacao']) > 0 ? $_POST['situacao'] : null;

		$administrador = strlen($_POST['administrador']) > 0 ? $_POST['administrador'] : null;

		if ( is_null($usuario) )
		{
			throw new Exception('O campo usuário é obrigatório!');
		}

		if ( is_null($senha) )
		{
			throw new Exception('O campo senha é obrigatório!');
		}

		if ( $conexao->query("UPDATE usuarios SET senha = md5('{$senha}'), situacao = '{$situacao}', administrador = '{$administrador}' WHERE usuario = '{$usuario}';") )
		{
			throw new Exception("Usuário <b>{$usuario}</b> atualizado com sucesso!", 1);
		}
		else
		{
			throw new Exception("<b>Falha ao inserir novo usuário: </b>" . $conexao->error);
		}
	}
}
catch ( Exception $e )
{
	echo $e->getMessage();

	if ( $e->getCode() == 1 )
	{
		header("Refresh:5; url=/", true, 303);

		exit;
	}
}
?>
<html>
<head>
	<title>Estruturado - Cadastro de usuário</title>
</head>
<body>
	<form name="frmCadastro" method="post">
		<fieldset>
			<legend>Cadastro</legend>

			<b>Pessoa : </b>
				<input type="text" name="pessoa" value="<?php echo $dadosUsuario->nome; ?>" size="45" disabled>
				<br />
			
			<b>Usuário : </b>
				<input type="hidden" name="usuario" value="<?php echo $dadosUsuario->usuario; ?>" >
				<input type="text" name="usuarioV" value="<?php echo $dadosUsuario->usuario; ?>" size="45" disabled>
				<br />

			<b>Senha : </b>
				<input type="password" name="senha" size="45">
				<br />

			<b>Situação : </b>
				<input type="radio" name="situacao" value="true" <?php if ( $dadosUsuario->situacao == 'true' ) echo 'checked'; ?> >Ativo
				<input type="radio" name="situacao" value="false" <?php if ( $dadosUsuario->situacao == 'false' ) echo 'checked'; ?> >Inativo
				<br />
				
			<b>Administrador : </b>
				<input type="radio" name="administrador" value="true" <?php if ( $dadosUsuario->administrador == 'true' ) echo 'checked'; ?> >Sim
				<input type="radio" name="administrador" value="false" <?php if ( $dadosUsuario->administrador == 'false' ) echo 'checked'; ?> >Não
				<br />

			<input type="submit" value="Salvar">

			<input type="button" value="Busca" onClick="javascript: window.location.href='usuario-busca.php'" >

			<input type="button" value="Início" onClick="javascript: window.location.href='index.php'" >
		</fieldset>
	</form> 
</body>
</html>