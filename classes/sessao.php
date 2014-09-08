<?php
session_start();

$sessao = isset($_SESSION['login']) ? $_SESSION['login'] : NULL;

if ( is_null($sessao) )
{
	header('Location: login.php');
}
else if ( !is_null($sessao) )
{
	$tempo = (strtotime(date('H:i:s')) - strtotime($_SESSION['login']['tempo']));

	if ( $tempo > (10*60))
	{
		header('Location: sair.php');
	}
	else
	{
		$_SESSION['login']['tempo'] = date('H:i:s');
	}
}