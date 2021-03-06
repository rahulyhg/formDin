<?php
/*
 * Formdin Framework
 * Copyright (C) 2012 Ministério do Planejamento
 * Criado por Luís Eugênio Barbosa
 * Essa versão é um Fork https://github.com/bjverde/formDin
 *
 * ----------------------------------------------------------------------------
 * This file is part of Formdin Framework.
 *
 * Formdin Framework is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License version 3
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License version 3
 * along with this program; if not,  see <http://www.gnu.org/licenses/>
 * or write to the Free Software Foundation, Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA  02110-1301, USA.
 * ----------------------------------------------------------------------------
 * Este arquivo é parte do Framework Formdin.
 *
 * O Framework Formdin é um software livre; você pode redistribuí-lo e/ou
 * modificá-lo dentro dos termos da GNU LGPL versão 3 como publicada pela Fundação
 * do Software Livre (FSF).
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA
 * GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou
 * APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/LGPL em português
 * para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da GNU LGPL versão 3, sob o título
 * "LICENCA.txt", junto com esse programa. Se não, acesse <http://www.gnu.org/licenses/>
 * ou escreva para a Fundação do Software Livre (FSF) Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02111-1301, USA.
 */
include_once( 'autoload_formdin.php');
final class TConnection
{
	// construtor
	private function __construct(){}
	private function __clone(){}
	//------------------------------------------------------------------------------------------
	public static function connect($dbType='postgres|mysql|sqlite|oracle|sqlserver',$username=null,$password=null,$database=null,$host=null,$port=null,$schema=null,$boolUtf8=null)
	{
		if( preg_match('/\|/',$dbType) || is_null($dbType) )
		{
		    $dbType='';
            $dbType='default';
		}
		$boolUtf8 = ( $boolUtf8 === false ) ? false : true;
        $dbType = strtolower($dbType);
        $configFile = "conn_$dbType.php";
		$configErrors=array();
		if( !$database && !$username )
		{
			if( !file_exists( $configFile ))
			{
				$configFile = "includes/conn_$dbType.php";
				if( !file_exists( $configFile ))
				{
					$root = self::getRoot();
					$configFile = $root.$configFile;
				}
			}
			//die( $configFile);
			if( ! file_exists( $configFile ) )
			{
				self::showExemple(array("Classe TConnection.class.php - Arquivo {$configFile} não encontrado!"));
				return false;
			}
			try
			{
				require_once($configFile);
				if( isset($utf8))
				{
					$boolUtf8 = $utf8;
				}
			}
			catch(Exception $e)
			{
				throw $e;
			}
			/*$db			= parse_ini_file($configFile);
			$dbType     = isset($db['dbtype']) 		? $db['dbtype'] 	: $dbType;
			$username	= isset($db['username']) 	? $db['username'] 	: null;
			$password 	= isset($db['password']) 	? $db['password'] 	: null;
			$database 	= isset($db['database']) 	? $db['database'] 	: null;
			$host 		= isset($db['host']) 		? $db['host'] 		: null;
			$port 		= isset($db['port']) 		? $db['port'] 		: null;
			$schema		= isset($db['schema']) 		? $db['schema'] 	: null;
			$boolUtf8	= isset($db['utf8']) 		? $db['utf8'] 		: $boolUtf8;
			*/

			$decimal_separator = isset($db['decimal_separator']) 		? $db['decimal_separator'] 		: null;
			if( preg_match('/false|0/i',$boolUtf8 ) == 1 || trim( $boolUtf8 ) == '' )
			{
				$boolUtf8 = 0;
			}
			else
			{
				$boolUtf8 = 1;
			}
		}


		switch( $dbType )
		{
			case 'mysql':
				if( ! $port )
				{
					$port = '3306';
				}
				$dsn='mysql:host='.$host.';dbname='.$database.';port='.$port;
			break;
			//-----------------------------------------------------------------------
			case 'postgre':
			case 'postgres':
			case 'pgsql':
                $dbType = 'postgres';
				if(! $port )
				{
					$port = '5432';
				}
				$dsn='pgsql:host='.$host.';dbname='.$database.';port='.$port;
			break;
			//-----------------------------------------------------------------------
			case 'sqllite':
			case 'sqlite':
				if( !file_exists( $database ) )
				{
					$configErrors[] = 'Arquivo '.$database.' não encontrado!';
				}
				$dsn='sqlite:'.$database;
			break;
			//-----------------------------------------------------------------------
			case 'oracle':
				if( ! $port )
				{
					$port = '1152';
				}
				$dsn="oci:dbname=(DESCRIPTION =(ADDRESS_LIST=(ADDRESS = (PROTOCOL = TCP)(HOST = ".$host.")(PORT = ".$port.")))(CONNECT_DATA =(SERVICE_NAME = ".$database.")))";
			break;
			//----------------------------------------------------------
			case 'mssql':
			if( ! $port ){
				$port = '1433';
			}
			$dsn='mssql:host='.$host.';dbname='.$database.';port='.$port;
			break;
			//----------------------------------------------------------
			case 'sqlserver':
				if( ! $port ){
					$port = '1433';
				}
				/**
				 * Dica de Reinaldo A. BarrÃªto Junior para utilizar o sql server no linux
				 * 
				 * No PHP 5.4 ou superior o drive mudou de MSSQL para SQLSRV
				 * */
					if (PHP_OS == "Linux") {
					$driver = 'dblib';
					$dsn = $driver.':host='.$host.';dbname='.$database.';port='.$port;
				} else {
					$driver = 'sqlsrv';
					$dsn = $driver.':Server='.$host.';Database='.$database;
				}
			break;
			case 'firebird':
				$dsn = 'firebird:dbname='.( ( is_null($host) ? '' : $host.':') ).$database;
			break;
			//----------------------------------------------------------
			default:
				$configErrors[] = 'Variavel $dbType não definida no arquivo de configuração!';
		}

		if( count( $configErrors ) > 0 )
		{
			self::showExemple( $configErrors );
		}
        if( !$dsn)
        {
            //die('Tipo do banco de dados '.$dbType.' não reconhecido. Ex: postgres, mysql, sqlite, oracle.');
			throw new Exception('Tipo do banco de dados '.$dbType.' não reconhecido. Ex: postgres, mysql, sqlite, oracle.');
        }
		//print 'dns:'.$dsn.'<br>User:'.$username.'<br>Senha:'.$password.'<br>';
        //die();
		try
		{
			if( $dbType!='oracle')
			{
				$conn = new PDO($dsn,$username,$password);
				$conn->isPDO = true;

//				$conn->dsn 		= $dsn;
//				$conn->utf8 	= $boolUtf8;
//				$conn->dbType	= $dbType;
//				$conn->schema	= $schema;
				$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				$conn->setAttribute(PDO::ATTR_CASE,PDO::CASE_UPPER );

	            if( $dbType == 'postgres' && $schema )
	            {
	                $stmt = $conn->prepare( 'set search_path='.$schema );
	                $stmt->execute();
	                $stmt=null;
	            }
			}
			else
			{
				$dsn=$database;
				$charSet = ( ( $boolUtf8===true) ? 'UTF8': null );
				$connection = @oci_connect($username, $password, $database, $charSet);
				if( ! $connection )
				{
					$e = oci_error();
					throw new Exception('Connection error'.$e['message']);
				}
				$conn = (object) array('connection'=>$connection,'isPDO'=>false);
				/*
				$stid = oci_parse($conn, 'SELECT * from tb_uf where cod_uf = 59');
				oci_execute($stid);
				$nrows = oci_fetch_all($stid, $res);
				print_r($res);
				*/
			}
			if( is_object($conn))
			{
				$conn->dsn 		= $dsn;
				$conn->utf8 	= $boolUtf8;
				$conn->dbType	= $dbType;
				$conn->schema	= $schema;
			}

		}
		catch( Exception $e )
		{
			// capturar error de sql
			throw new Exception("<br><b>Connection error using dsn ".$dsn."</b><br>Message:".$e->getMessage().'<br>');
		}
		return $conn;
	}
	private static function showExemple($arrErros=null)
	{
		$msgErro =  implode('<br>',$arrErros);
		$html='</pre><div style="padding:5px;border:1px solid red;background-color:lightyellow;width:400px;color:blue;overflow:auto;">';
		$html.='<div style="border-bottom:1px solid blue;color:red;text-align:center;"><blink>'.$msgErro.'</blink></div>';
		$html.='<center>Exemplo de configuração para conexão com banco ORACLE. Arquivo: conn_oracle.php</center><br>
                    $dbType = "oracle"<br>
					$host = "192.168.1.140";<br>
					$port = "1521";<br>
					$database = "xe";<br>
					$username = "root";<br>
					$password = "123456";<br>
					$utf8=0;<br><hr>
				<center>Exemplo de configuração para conexão com banco MYSQL. Arquivo: conn_mysql.php</center><br>
					$dbType = "mysql";<br>
					$host = "192.168.1.140";<br>
					$port = "3306";<br>
					$database = "dbteste";<br>
					$username = "root";<br>
					$password = "";<br>
					$utf8=1;<br><br><hr>
				<center>Exemplo de configuração para conexão com banco POSTGRES. Arquivo: conn_postgres.php</center><br>
                    $dbType = "postgres";<br>
					$host = "192.168.1.140";<br>
					$port = "5432";<br>
					$database = "dbteste";<br>
					$username = "postgres";<br>
					$password = "123456";<br>
					$utf8=1;<br>
                    schema   = sisteste<br<br><hr>
				<center>Exemplo de configuração para conexão com banco SQLITE. Arquivo: conn_sqlite.php</center><br>
                    dbtype="sqlite"<br>
					database = "includes/exemplo.s3db"<hr>
				<center>Exemplo de configuração para conexão com banco SQLSERVER. Arquivo: conn_sqlserver.php</center><br>
                    $dbType = "sqlserver";<br>
					$host = "192.168.1.140";<br>
					$port = "1433";<br>
					$database = "dbteste";<br>
					$username = "sa";<br>
					$password = "123456";<br>
					$utf8=0;<br><hr>
		</div>';
		die( $html);
		//throw new Exception( utf8_encode($html) );
	}
	/**
	* Localiza a pasta base da framework
	*
	*/
	private static function getRoot()
	{
		$base='';
		for($i=0;$i<10;$i++)
		{
			$base = str_repeat('../',$i).'base/';
			if( file_exists($base) )
			{
				$i=10;
				break;
			}
		}
		$base = str_replace('base/','',$base);
		$root = ($base == '/') ? './' : $base;
		return $root;
	}
}
?>