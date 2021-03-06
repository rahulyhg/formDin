<?php
class TipoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  t.idtipo
									 ,t.descricao
									 ,t.idmeta_tipo
									 ,(select descricao from form_exemplo.meta_tipo as mt where mt.idmetatipo = t.idmeta_tipo) as nom_meta_tipo  
									 ,t.sit_ativo
									 from form_exemplo.tipo as t';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDTIPO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DESCRICAO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDMETA_TIPO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'SIT_ATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
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
		$sql = self::$sqlBasicSelect.' where idtipo = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idtipo) as qtd from form_exemplo.tipo';
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
	public static function insert( TipoVO $objVo ) {
		$values = array(  $objVo->getDescricao() 
						, $objVo->getIdmeta_tipo() 
						, $objVo->getSit_ativo() 
						);
		return self::executeSql('insert into form_exemplo.tipo(
								 descricao
								,idmeta_tipo
								,sit_ativo
								) values (?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( TipoVO $objVo ) {
		$values = array( $objVo->getDescricao()
						,$objVo->getIdmeta_tipo()
						,$objVo->getSit_ativo()
						,$objVo->getIdtipo() );
		return self::executeSql('update form_exemplo.tipo set 
								 descricao = ?
								,idmeta_tipo = ?
								,sit_ativo = ?
								where idtipo = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from form_exemplo.tipo where idtipo = ?',$values);
	}
}
?>