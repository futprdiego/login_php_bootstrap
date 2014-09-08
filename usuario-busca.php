
<?php
/**
 * @author Glaubert Dumpierre
 * @since 03/09/2014
 */
include 'classes/sessao.php';

include 'classes/conexao.php';
?>
<html>
<head>
	<title>Estruturado - Usuários</title>
</head>
<body>
	<form name="frmBuscaUsuario" method="post">
		<fieldset>
			<p>
				<b>Nome da pessoa : </b>
					<input type="text" name="nome" size="100">
			</p>

			<b>Usuário : </b>
				<input type="text" name="usuario" size="70">

			<input type="submit" value="Buscar">

			<input type='button' onClick="javascript: window.location.href='usuario-cadastro.php';" value="Cadastrar">

			<input type="button" value="Início" onClick="javascript: window.location.href='index.php'" >
		</fieldset>
<?php 
try
{
	if ( $_POST )
	{
		/*
		 * OBTEM O NOME DA PESSOA E OU USUÁRIO PASSADO PELO FORMULÁRIO
		 */
		$nome = !empty($_POST['nome']) ? $_POST['nome'] : null;
		
		$usuario = !empty($_POST['usuario']) ? $_POST['usuario'] : null;

		/*
		 * INICIA SENTENÇA SQL PARA CONSULTA
		 */
		$sql = "SELECT A.usuario,
					   B.nome  
			      FROM usuarios AS A  
			INNER JOIN pessoas AS B 
					ON ( A.idPessoa = B.idPessoa ) ";

		$where = '';

		if ( !is_null($nome) )
		{
			$where .= " AND B.nome LIKE UPPER('%{$nome}%') ";
		}

		if ( !is_null($usuario) )
		{
			$where .= " AND A.usuario LIKE UPPER('%{$usuario}%') ";
		}

		if ( strlen($where) > 0 )
		{
			$sql .= " WHERE " . substr($where, 4);
		}

		$conexao = new Db();

		$resultado = $conexao->query($sql);

		echo "<fieldset><legend>Resultado</legend>";

		if ( $resultado->num_rows > 0 )
		{
			while ( $dados = $resultado->fetch_object() )
			{
				printf("<div><b>Nome :</b> %s </br> <b>Usuário:</b> %s </br> </div>", $dados->nome, $dados->usuario);
		
				echo "<input type='button' onClick=\"javascript: window.location.href='usuario-atualizacao.php?usuario={$dados->usuario}'\" value='Editar'>";
		
				echo "<hr />";
			}
		}
		else
		{
			throw new Exception('Nenhuma pessoa localizada');
		}

		echo "</fieldset>";
	}		
}
catch ( Exception $e )
{
	echo "<h4>{$e->getMessage()}</h4>";
}
?>
	</form> 
</body>
</html>