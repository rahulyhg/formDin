<?php
class PedidoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idpedido
									 ,idpessoa
									 ,nom_pessoa
									 ,idtipo_pagamento
									 ,des_tipo_pagamento
									 ,dat_pedido
									 from ( 
										select
											pe.idpedido
									   		,pe.idpessoa
									   		,p.nome as nom_pessoa
									   		,pe.idtipo_pagamento
									   		,t.descricao as des_tipo_pagamento
									   		,DATE_FORMAT(pe.dat_pedido, \'%d/%m/%Y\') as dat_pedido
									   from form_exemplo.pedido as pe
									   		,form_exemplo.pessoa as p
									   		,form_exemplo.tipo as t
									   where p.idpessoa = pe.idpessoa
									   and t.idtipo = pe.idtipo_pagamento
									   ) as r';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPESSOA', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDTIPO_PAGAMENTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DAT_PEDIDO', SqlHelper::SQL_TYPE_TEXT_LIKE);
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
		$sql = self::$sqlBasicSelect.' where idpedido = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idpedido) as qtd from form_exemplo.pedido';
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
	public static function insert( PedidoVO $objVo ) {
		$values = array(  $objVo->getIdpessoa() 
						, $objVo->getIdtipo_pagamento() 
						, $objVo->getDat_pedido() 
						);
		return self::executeSql('insert into form_exemplo.pedido(
								 idpessoa
								,idtipo_pagamento
								,dat_pedido
								) values (?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( PedidoVO $objVo ) {
		$values = array( $objVo->getIdpessoa()
						,$objVo->getIdtipo_pagamento()
						,$objVo->getDat_pedido()
						,$objVo->getIdpedido() );
		return self::executeSql('update form_exemplo.pedido set 
								 idpessoa = ?
								,idtipo_pagamento = ?
								,dat_pedido = ?
								where idpedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from form_exemplo.pedido where idpedido = ?',$values);
	}
}
?>