
<?php
/**
 * @author Glaubert Dumpierre
 * @since 21/08/2014
 */
include 'classes/sessao.php';

include 'classes/conexao.php';
?>
<html>
<head>
	<title>Estruturado - Inicial</title>
</head>
<body>
	<form name="frmBuscaPessoa" method="post">
		<fieldset>
			<b>Nome : </b><input type="text" name="nome" size="100">
			<input type="submit" value="Buscar">
			<input type='button' onClick="javascript: window.location.href='pessoa-cadastro.php';" value="Cadastrar">
			<input type="button" value="Início" onClick="javascript: window.location.href='index.php'" >
		</fieldset>
<?php 
$nome = !empty($_POST['nome']) ? $_POST['nome'] : null;

if ( !is_null($nome) )
{
	$sql = "SELECT * 
		      FROM pessoas 
		     WHERE nome LIKE UPPER('%{$nome}%')";
	
	$conexao = new Db();
	
	$resultado = $conexao->query($sql);
	
	echo "<fieldset><legend>Resultado</legend>";
	
	if ( $resultado->num_rows > 0 )
	{
		while ( $dados = $resultado->fetch_object() )
		{
			printf("<div><b>Nome :</b> %s </br> <b>Telefone:</b> %s </br> <b>Endereço :</b> %s </br></div>", $dados->nome, $dados->telefone, $dados->endereco);

			echo "<input type='button' onClick=\"javascript: window.location.href='pessoa-cadastro.php?acao=editar&pessoa={$dados->idPessoa}'\" value='Editar'>";

			echo "<hr />";
		}
	}
	else
	{
		echo "<h4>Nenhuma pessoa localizada</h4>";
	}
	
	echo "</fieldset>";
}
?>
	</form> 
</body>
</html>