<?php
/**
 * @author Glaubert Dumpierre
 * @since 15/08/2014
 */
class Db extends mysqli
{
	public $host = 'localhost';

	public $banco = 'estruturado';

	public $usuario = 'root';

	public $senha = '';

	public function __construct()
	{
		parent::mysqli($this->host, $this->usuario, $this->senha, $this->banco);		
	}
}