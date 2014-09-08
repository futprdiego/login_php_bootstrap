<?php
/**
 * @author Glaubert Dumpierre
 * @since 21/08/2014
 */
include 'classes/sessao.php';

include 'classes/conexao.php';

$conexao = new Db();

$acao = !empty($_GET['acao']) ? $_GET['acao'] : 'cadastrar';

$pessoa = !empty($_GET['pessoa']) ? $_GET['pessoa'] : null;

$nome = !empty($_POST['nome']) ? $_POST['nome'] : null;

$endereco = !empty($_POST['endereco']) ? $_POST['endereco'] : null;

$telefone = !empty($_POST['telefone']) ? $_POST['telefone'] : null;

$idPessoa = !empty($_POST['idPessoa']) ? $_POST['idPessoa'] : null;

$dados = new stdClass();

$dados->nome = '';

$dados->endereco = '';

$dados->telefone = '';

/*
 * OBTEM DADOS PARA EDIÇÃO
 */
if ( $acao == 'editar' && !is_null($pessoa) && !$_POST )
{
	$sql = "SELECT *
	          FROM pessoas
	         WHERE idPessoa = {$pessoa}";

	$resultado = $conexao->query($sql);
	
	if ( $resultado->num_rows > 0 )
	{
		$dados = $resultado->fetch_object();		
	}
	else
	{
		echo "Pessoa não cadastrada!";

		echo '<br /><input type="button" value="Início" onClick="javascript: window.location.href=\'index.php\'" >';

		exit;
	}
}

if ( $_POST )
{
	/*
	 * ATUALIZA CADASTRO DE PESSOA EXISTENTE
	 */
	if ( $acao == 'editar' && !is_null($nome) && !is_null($endereco) && !is_null($telefone) && !is_null($idPessoa) )
	{
		if ( $conexao->query("UPDATE pessoas SET nome = '{$nome}', endereco='{$endereco}', telefone = '{$telefone}' WHERE idPessoa = {$idPessoa};") )
		{
			$resultado = $conexao->query("SELECT * FROM pessoas WHERE idPessoa = {$idPessoa}");
	
			$dados = $resultado->fetch_object();
	
			echo "Pessoa atualizada com sucesso!";
		}
		else
		{
			echo "<b>Encontramos um erro : </b>" . $conexao->error;
		}
	
		echo '<br /><input type="button" value="Retornar" onClick="javascript: window.location.href=\'pessoa.php\'" >';
	
		exit;
	}
	/*
	 * GRAVA CADASTRO DE NOVA PESSOA
	 */
	else if ( $acao == 'cadastrar' && !is_null($nome) && !is_null($endereco) && !is_null($telefone) && is_null($idPessoa) )
	{
		if ( $conexao->query("INSERT INTO pessoas (nome, endereco, telefone ) VALUES ( '{$nome}', '{$endereco}', '{$telefone}' );") )
		{
			echo "Pessoa cadastrada com sucesso!";
		}
		else
		{
			echo "<b>Encontramos um erro : </b>" . $conexao->error;
		}
	
		echo '<br /><input type="button" value="Retornar" onClick="javascript: window.location.href=\'pessoa.php\'" >';
	
		exit;
	}
	else
	{
		echo "Todos os campos devem ser preenchidos!";
	}
}
?>
<html>
<head>
	<title>Estruturado - Inicial</title>
</head>
<body>
	<form name="frmCadastro" method="post">
		<fieldset>
			<legend><?php echo ucfirst($acao); ?></legend>
			<input type="hidden" name="idPessoa" value="<?php echo $pessoa; ?>">
			<b>Nome : </b><input type="text" name="nome" size="80" value="<?php echo $dados->nome; ?>"><br />
			<b>Endereço : </b><input type="text" name="endereco" size="100" value="<?php echo $dados->endereco; ?>"><br />
			<b>Telefone : </b><input type="text" name="telefone" size="20" value="<?php echo $dados->telefone; ?>"><br />
			<input type="submit" value="Salvar">
			<input type="button" value="Busca" onClick="javascript: window.location.href='pessoa.php'" >
			<input type="button" value="Início" onClick="javascript: window.location.href='index.php'" >
		</fieldset>
	</form> 
</body>
</html>