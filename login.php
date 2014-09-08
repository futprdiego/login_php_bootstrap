<?php
/**
 * @author Glaubert Dumpierre
 * @since 15/08/2014
 */
session_start();

include './classes/conexao.php';

/**
 * OPERADOR TERNÁRIO
 * (<condição>) ? <instruções para verdadeiro> : <instruções para falso>;
 * (diferente de vazio) ? <grava na variável  o valor do método POST> ::(senão) <grava na variável o valor NULL>
 */
$usuario = !empty($_POST['usuario']) ? $_POST['usuario'] : null;
/**
 * OPERADOR TERNÁRIO
 * (<condição>) ? <instruções para verdadeiro> : <instruções para falso>;
 * (diferente de vazio) ? <grava na variável  o valor do método POST> :(senão) <grava na variável o valor NULL>
 */
$senha = !empty($_POST['senha']) ? $_POST['senha'] : null;
/**
 * VARIÁVEL ERRO - 
 * Criação da variável $erro para fácil manipulação no código HTML com o BOOTSTRAP para ficar centralizado. 
 */
$erro = null;

if (!is_null($usuario) && !is_null($senha)) {
    $conexao = new Db();

    $sql = "SELECT A.*,
	               B.* 
			  FROM usuarios AS A  
	    INNER JOIN pessoas AS B 
	            ON ( A.idPessoa = B.idPessoa )  
			 WHERE A.usuario = '{$usuario}' 
			   AND A.senha = md5('{$senha}') 
			   AND A.situacao = true 
	         LIMIT 1;";

    $resultado = $conexao->query($sql);

    if ($resultado) {
        if ($resultado->num_rows > 0) {
            echo 'Acesso autorizado!';

            $dados = $resultado->fetch_object();

            $_SESSION['login']['usuario'] = $dados->usuario;

            $_SESSION['login']['nome'] = $dados->nome;

            $_SESSION['login']['tempo'] = date('H:i:s');

            $_SESSION['login']['acesso'] = date('H:i:s');

            $_SESSION['login']['ip'] = $_SERVER['REMOTE_ADDR'];

            $_SESSION['login']['administrador'] = $dados->administrador;

            unset($_POST['usuario']);

            unset($_POST['senha']);

            header('Location: index.php');
        } else {
            
            $erro = "<script>$(document).ready(function() { $('.bs-example-modal-sm').modal(); })</script>";
        }
    }
}
?>

<!DOCTYPE html5>
<html lang="pt">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">
        <title>Login r-PHP</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

        <!-- Div barra navegação principal -->
        <div class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">r-PHP</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Início</a></li>
                    </ul>                  
                </div>
            </div>
        </div>

        <!-- Div formulário bem vindo -->
        <div class="container theme-showcase" role="main">
            <div class="jumbotron">
                <h1><strong>Seja bem vindo ao <font color="#4682B4">r-PHP</font></strong></h1>
                <p>Sistema de revisão do conteúdo apresentado na disciplina de <font color="#4682B4"> PHP.</font>
            </div>
        </div>


        <!-- Erro Login centralizado--> 
        <?php
        if (isset($erro))
            echo $erro;
        ?>

        <!-- Formulário de Login -->
        <div class="container col-sm-7">
            <div class="container col-md-5 col-md-offset-8">
                <form class="form-horizontal" role="form" method="POST">
                    <legend><h2><strong>Acesso a revisão</strong></h2></legend>
                    <div class="form-group">
                        <label for="input_usuario" class="col-sm-3 control-label">Usuário</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="usuario" placeholder="Usuário" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_senha" class="col-sm-3 control-label">Senha</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="senha" placeholder="Senha" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-10">
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </div>
                    </div>
            </div>
        </div>

        <!-- 
            * Modal para o erro do usuário
        -->
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <!--
                        * Alerta para usuário ou senha inválida
                    -->
                    <div id='myAlert' class='alert alert-danger col-sm-12'>
                        <a href='index.php' class='close'>&times;</a>
                        &nbsp; usuário ou senha incorretos!&nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
    <legend></legend>
        <div class="footer col-sm-12"> 
            <p> &copy; 2014 - Diego Reinoso Noronha</p>
        </div> 
</body>	
</html>

