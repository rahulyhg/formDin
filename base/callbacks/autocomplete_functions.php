<?php
/*
 * Formdin Framework
 * Copyright (C) 2012 Minist�rio do Planejamento
 * Criado por Lu�s Eug�nio Barbosa
 * Essa vers�o � um Fork https://github.com/bjverde/formDin
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
 * Este arquivo � parte do Framework Formdin.
 *
 * O Framework Formdin � um software livre; voc� pode redistribu�-lo e/ou
 * modific�-lo dentro dos termos da GNU LGPL vers�o 3 como publicada pela Funda��o
 * do Software Livre (FSF).
 *
 * Este programa � distribu�do na esperan�a que possa ser �til, mas SEM NENHUMA
 * GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer MERCADO ou
 * APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/LGPL em portugu�s
 * para maiores detalhes.
 *
 * Voc� deve ter recebido uma c�pia da GNU LGPL vers�o 3, sob o t�tulo
 * "LICENCA.txt", junto com esse programa. Se n�o, acesse <http://www.gnu.org/licenses/>
 * ou escreva para a Funda��o do Software Livre (FSF) Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02111-1301, USA.
 */

//--------------------------------------------------------
/**
* Fun��o para ajudar na depura��o do retorno do autocomplete
* a variavel erro esta recebendo um numero de erro como retorno mesmo a query ser bem sucesdida
* $boolDebug=true;
* impAutocomplete($sql,$boolDebug);
* impAutocomplete('$bvars='.print_r($bvars,true),$boolDebug);
* impAutocomplete('nrows='.$nrows,$boolDebug);
* impAutocomplete('intCacheTime='.$intCacheTime,$boolDebug);
* impAutocomplete('Erro='.$erro,$boolDebug);
*
* @param string $strTexto
* @param string $boolDebug
*/
function impAutocomplete($strText, $boolDebug) {
	if($boolDebug) {
		print $strText."\n";
	}
}

/**
 * Recupera o pacote Oracle e retorna o resultado.
 * 
 * @param strSearchField
 * @param intCacheTime
 * @param strSearchField
 * @param strTablePackageFuncion
 */
function recuperaPacoteOracleAutoComplete($strSearchField, $intCacheTime, $strTablePackageFuncion) {
	if($strSearchField) {
		$bvars[$strSearchField]=$_REQUEST['q'];
	}
	// Por raz�es de seguran�a, o vari�vel num_pessoa tem que ser lido da sess�o
	if ( defined('TIPO_ACESSO') && TIPO_ACESSO=='I' ) {
		$bvars['NUM_PESSOA_CERTIFICADO'] = $_SESSION['num_pessoa'];
	} else {
		$bvars['NUM_PESSOA'] = $_SESSION['num_pessoa'];
	}
	if( $erro = recuperarPacote($strTablePackageFuncion,$bvars,$res,(int)$intCacheTime)) {
		echo utf8_encode("Erro na fun��o autocomplete(). Erro:".$erro[0])."\n";
		return;
	}
	
	return $res;
}


/**
 * Recupera o resultado da tabela 
 * @param bvars
 * @param boolSearchAnyPosition
 * @param arrUpdateFields
 * @param strSearchField
 * @param strTablePackageFuncion
 * @param erro
 */
function tableRecoverResult($bvars, $boolSearchAnyPosition, $arrUpdateFields, $strSearchField, $strTablePackageFuncion) {
	$sql = tableRecoverCreateSql ( $bvars, $boolSearchAnyPosition, $arrUpdateFields, $strSearchField, $strTablePackageFuncion);
	//impAutocomplete( $sql,true);return;

	$bvars	=null;
	$res	=null;
	$nrows	=null;
    if( !class_exists('TPDOConnection') || !TPDOConnection::getInstance() ) {
		if( $erro = $GLOBALS['conexao']->executar_recuperar($sql,$bvars,$res,$nrows,(int)$intCacheTime) ) {
			if( preg_match('/falha/i',$erro ) > 0 ) {
				echo utf8_encode("Erro na fun��o autocomplete(). Erro:".$erro)."\n".$sql;
				return;
			}
		}
	} else {
		$res = TPDOConnection::executeSql($sql);
		//echo utf8_encode($sql."\n");
		//return;
	}
	return $res;
}


/**
 * @param bvars
 * @param boolSearchAnyPosition
 * @param arrUpdateFields
 * @param strSearchField
 * @param strTablePackageFuncion
 * @param selectColumns
 * @param selectColumns
 */
function tableRecoverCreateSql($bvars, $boolSearchAnyPosition, $arrUpdateFields, $strSearchField, $strTablePackageFuncion) {
	$selectColumns=$strSearchField;
	
	if( is_array($arrUpdateFields)) {
		foreach($arrUpdateFields as $k=>$v) {
			if( strtoupper($k) != strtoupper( $strSearchField ) ) {
				$selectColumns.=','.$k;
			}
		}
	}
	//$where = "upper({$strSearchField}) like '".strtoupper($_REQUEST['q'])."%'";
	$where = "upper({$strSearchField}) like upper('".($boolSearchAnyPosition === true ? '%' : '' ). utf8_encode($_REQUEST['q'])."%')";
	if( is_array($bvars) ) {
		foreach($bvars as $k=>$v) {
			$where .=" and {$k} = '{$v}'";
		}
	}
	$sql 	= "select {$selectColumns} from {$strTablePackageFuncion} where {$where} order by {$strSearchField}";
	return $sql;
}

?>