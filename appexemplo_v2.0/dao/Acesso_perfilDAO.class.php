<?php
class Acesso_perfilDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idperfil
									 ,nom_perfil
									 ,sit_ativo
									 ,dat_inclusao
									 ,dat_update
									 from form_exemplo.acesso_perfil ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPERFIL', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NOM_PERFIL', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'SIT_ATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DAT_INCLUSAO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DAT_UPDATE', SqlHelper::SQL_TYPE_TEXT_LIKE);
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
		$sql = self::$sqlBasicSelect.' where idperfil = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idperfil) as qtd from form_exemplo.acesso_perfil';
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
	public static function insert( Acesso_perfilVO $objVo ) {
		$values = array(  $objVo->getNom_perfil() 
						, $objVo->getSit_ativo() 
						, $objVo->getDat_inclusao() 
						, $objVo->getDat_update() 
						);
		return self::executeSql('insert into form_exemplo.acesso_perfil(
								 nom_perfil
								,sit_ativo
								,dat_inclusao
								,dat_update
								) values (?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( Acesso_perfilVO $objVo ) {
		$values = array( $objVo->getNom_perfil()
						,$objVo->getSit_ativo()
						,$objVo->getDat_inclusao()
						,$objVo->getDat_update()
						,$objVo->getIdperfil() );
		return self::executeSql('update form_exemplo.acesso_perfil set 
								 nom_perfil = ?
								,sit_ativo = ?
								,dat_inclusao = ?
								,dat_update = ?
								where idperfil = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from form_exemplo.acesso_perfil where idperfil = ?',$values);
	}
}
?>