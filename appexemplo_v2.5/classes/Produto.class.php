<?php
/**
 * System generated by SysGen (System Generator with Formdin Framework) 
 * Download SysGen: https://github.com/bjverde/sysgen
 * Download Formdin Framework: https://github.com/bjverde/formDin
 * 
 * SysGen  Version: 0.9.0
 * FormDin Version: 4.2.6-alpha
 * 
 * System ap2v created in: 2018-11-21 23:30:55
 */

class Produto {


	public function __construct(){
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$result = ProdutoDAO::selectById( $id );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$result = ProdutoDAO::selectCount( $where );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null, $where=null, $page=null,  $rowsPerPage= null){
		$result = ProdutoDAO::selectAllPagination( $orderBy, $where, $page,  $rowsPerPage );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAll( $orderBy=null, $where=null ){
		$result = ProdutoDAO::selectAll( $orderBy, $where );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function save( ProdutoVO $objVo ){
		$result = null;
		if( $objVo->getIdproduto() ) {
			$result = ProdutoDAO::update( $objVo );
		} else {
			$result = ProdutoDAO::insert( $objVo );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$result = ProdutoDAO::delete( $id );
		return $result;
	}

}
?>