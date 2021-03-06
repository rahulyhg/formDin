<?php
class EnderecoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idendereco
									 ,endereco
									 ,idpessoa
									 ,idtipo_endereco
									 ,cod_municipio
									 ,cep
									 ,numero
									 ,complemento
									 ,bairro
									 ,cidade
									 from form_exemplo.endereco ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDENDERECO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'ENDERECO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPESSOA', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDTIPO_ENDERECO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'COD_MUNICIPIO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'CEP', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NUMERO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'COMPLEMENTO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'BAIRRO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'CIDADE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ) {
		if( empty($id) || !is_numeric($id) ){
			throw new InvalidArgumentException();
		}
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idendereco = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idendereco) as qtd from form_exemplo.endereco';
		$sql = $sql.( ($where)? ' where '.$where:'');
		$result = self::executeSql($sql);
		return $result['QTD'][0];
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null, $where=null, $page=null,  $rowsPerPage= null ) {
		$rowStart = PaginationSQLHelper::getRowStart($page,$rowsPerPage);
		$where = self::processWhereGridParameters($where);

		$sql = self::$sqlBasicSelect
		.( ($where)? ' where '.$where:'')
		.( ($orderBy) ? ' order by '.$orderBy:'')
		.( ' LIMIT '.$rowStart.','.$rowsPerPage);

		$result = self::executeSql($sql);
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAll( $orderBy=null, $where=null ) {
		$where = self::processWhereGridParameters($where);
		$sql = self::$sqlBasicSelect
		.( ($where)? ' where '.$where:'')
		.( ($orderBy) ? ' order by '.$orderBy:'');

		$result = self::executeSql($sql);
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function insert( EnderecoVO $objVo ) {
		$values = array(  $objVo->getEndereco() 
						, $objVo->getIdpessoa() 
						, $objVo->getIdtipo_endereco() 
						, $objVo->getCod_municipio() 
						, $objVo->getCep() 
						, $objVo->getNumero() 
						, $objVo->getComplemento() 
						, $objVo->getBairro() 
						, $objVo->getCidade() 
						);
		return self::executeSql('insert into form_exemplo.endereco(
								 endereco
								,idpessoa
								,idtipo_endereco
								,cod_municipio
								,cep
								,numero
								,complemento
								,bairro
								,cidade
								) values (?,?,?,?,?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( EnderecoVO $objVo ) {
		$values = array( $objVo->getEndereco()
						,$objVo->getIdpessoa()
						,$objVo->getIdtipo_endereco()
						,$objVo->getCod_municipio()
						,$objVo->getCep()
						,$objVo->getNumero()
						,$objVo->getComplemento()
						,$objVo->getBairro()
						,$objVo->getCidade()
						,$objVo->getIdendereco() );
		return self::executeSql('update form_exemplo.endereco set 
								 endereco = ?
								,idpessoa = ?
								,idtipo_endereco = ?
								,cod_municipio = ?
								,cep = ?
								,numero = ?
								,complemento = ?
								,bairro = ?
								,cidade = ?
								where idendereco = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from form_exemplo.endereco where idendereco = ?',$values);
	}
}
?>