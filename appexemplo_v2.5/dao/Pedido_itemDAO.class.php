<?php
class Pedido_itemDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idpedido_item
									 ,idpedido
									 ,idproduto
									 ,qtd_unidade
									 ,preco
									 from form_exemplo.pedido_item ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO_ITEM', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPRODUTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'QTD_UNIDADE', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'PRECO', SqlHelper::SQL_TYPE_NUMERIC);
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
		$sql = self::$sqlBasicSelect.' where idpedido_item = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idpedido_item) as qtd from form_exemplo.pedido_item';
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
	public static function insert( Pedido_itemVO $objVo ) {
		$values = array(  $objVo->getIdpedido() 
						, $objVo->getIdproduto() 
						, $objVo->getQtd_unidade() 
						, $objVo->getPreco() 
						);
		return self::executeSql('insert into form_exemplo.pedido_item(
								 idpedido
								,idproduto
								,qtd_unidade
								,preco
								) values (?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( Pedido_itemVO $objVo ) {
		$values = array( $objVo->getIdpedido()
						,$objVo->getIdproduto()
						,$objVo->getQtd_unidade()
						,$objVo->getPreco()
						,$objVo->getIdpedido_item() );
		return self::executeSql('update form_exemplo.pedido_item set 
								 idpedido = ?
								,idproduto = ?
								,qtd_unidade = ?
								,preco = ?
								where idpedido_item = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from form_exemplo.pedido_item where idpedido_item = ?',$values);
	}
}
?>