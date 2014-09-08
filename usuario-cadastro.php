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
	if ( $_SESSION['login']['administrador'] == "false" )
	{
		throw new Exception('Somente administradores podem cadastrar usuários!', 1);
	}

	/*
	 * OBTEM INFORMAÇÕES DAS PESSOAS CADASTRADAS
	 * QUE NÃO ESTÃO VINCULADAS A NENHUM USUÁRIO
	 * PARA COMPOR O SELECT
	 */
	$sqlPessoas = 'SELECT idPessoa,
			              nome
			         FROM pessoas
			        WHERE idPessoa NOT IN ( SELECT idPessoa
			                                  FROM usuarios );';
	
	$resultadoPessoas = $conexao->query($sqlPessoas);
	
	$opcoes = "<option value=''>Selecione a pessoa</option>";
	
	while ( $i = $resultadoPessoas->fetch_object() )
	{
		$opcoes .= "<option value='{$i->idPessoa}'>{$i->nome}</option>";
	}

	/*
	 * SE A REQUISIÇÃO ESTIVER VINDO VIA POST
	 */
	if ( $_POST )
	{
		$usuario = strlen($_POST['usuario']) > 0 ? $_POST['usuario'] : null;
		
		$senha = strlen($_POST['senha']) > 0 ? $_POST['senha'] : null;
		
		$pessoa = strlen($_POST['pessoa']) > 0 ? $_POST['pessoa'] : null;
		
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
		if ( is_null($pessoa) )
		{
			throw new Exception('O campo pessoa é obrigatório!');
		}
		
		/*
		 * VERIFICA SE O USUÁRIO INFORMADO JÁ NÃO ESTA
		 * CADASTRADO
		 */
		$sqlUsuario = "SELECT * FROM usuarios WHERE usuario = '{$usuario}';";
		
		$resultadoUsuario = $conexao->query($sqlUsuario);
		
		if ( $resultadoUsuario->num_rows > 0 )
		{
			throw new Exception("O usuário <b>{$usuario}</b> já é existe!");
		}
		
		if ( $conexao->query("INSERT INTO usuarios (usuario, senha, idPessoa, situacao, administrador ) VALUES ( '{$usuario}', md5('{$senha}'), {$pessoa}, '{$situacao}', '{$administrador}' );") )
		{
			throw new Exception("Usuário <b>{$usuario}</b> cadastrado com sucesso!");
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

			<b>Usuário : </b>
				<input type="text" name="usuario" size="45">
				<br />

			<b>Senha : </b>
				<input type="password" name="senha" size="45">
				<br />

			<b>Pessoa : </b>
				<select name="pessoa">
					<?php echo $opcoes; ?>
				</select>
				<br />

			<b>Situação : </b>
				<input type="radio" name="situacao" value="true">Ativo
				<input type="radio" name="situacao" value="false" checked>Inativo
				<br />
				
			<b>Administrador : </b>
				<input type="radio" name="administrador" value="true">Sim
				<input type="radio" name="administrador" value="false" checked>Não
				<br />

			<input type="submit" value="Salvar">

			<input type="button" value="Busca" onClick="javascript: window.location.href='usuario-busca.php'" >

			<input type="button" value="Início" onClick="javascript: window.location.href='index.php'" >
		</fieldset>
	</form> 
</body>
</html>