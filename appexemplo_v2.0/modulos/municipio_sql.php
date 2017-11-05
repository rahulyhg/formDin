<?php
$primaryKey = 'COD_MUNICIPIO';
$frm = new TForm('Cadastro de Munic�pios',600);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( $primaryKey ); // coluna chave da tabela

$dadosUf = UfDAO::selectAll('NOM_UF');
$frm->addSelectField('COD_UF','Estado:',true,$dadosUf);
$frm->addTextField('NOM_MUNICIPIO', 'Nome munic�pio:', 50, true);
$frm->addSelectField('SIT_ATIVO', 'Ativo:', true, 'S=Sim,N=N�o', true);

$frm->addButton('Salvar', null, 'Salvar', null, null, true, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		if ( $frm->validate() ) {
			$vo = new MunicipioVO();
			$frm->setVo( $vo );
			$resultado = MunicipioDAO::insert( $vo );
			if($resultado==1) {
				$frm->setMessage('Registro gravado com sucesso!!!');
				$frm->clearFields();
			}else{
				$frm->setMessage($resultado);
			}
		}
	break;
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'gd_excluir':
		$id = $frm->get( $primaryKey ) ;
		$resultado = MunicipioDAO::delete( $id );;
		if($resultado==1) {
			$frm->setMessage('Registro excluido com sucesso!!!');
			$frm->clearFields();
		}else{
			$frm->clearFields();
			$frm->setMessage($resultado);
		}
	break;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = 17;
	$page = PostHelper::get('page');
	$dados = MunicipioDAO::selectAllSqlPagination( $primaryKey, null, $page,  $maxRows);
	$realTotalRowsWithoutPaginator = MunicipioDAO::selectCount();
	$mixUpdateFields = $primaryKey.'|'.$primaryKey.',COD_UF|COD_UF,NOM_MUNICIPIO|NOM_MUNICIPIO,SIT_ATIVO|SIT_ATIVO';
	$gride = new TGrid( 'gd'          // id do gride
					   ,'Gride com Paginador SQL'       // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'municipio_sql.php' );
	$gride->setOnDrawActionButton('onDraw');
	
	$gride->addColumn($primaryKey, 'id', 50, 'center');
	$gride->addColumn('COD_UF', 'COD_UF', 50, 'center');
	$gride->addColumn('SIG_UF', 'UF', 50, 'center');
	$gride->addColumn('NOM_MUNICIPIO', 'Nome munic�pio', 200);
	$gride->addColumn('SIT_ATIVO', 'Ativo', 50, 'center');
	$gride->show();
	die();
}

$frm->addHtmlField('gride');
$frm->addJavascript('init()');
$frm->show();


function onDraw( $rowNum,$button,$objColumn,$aData) {
	//$button->setEnabled( false );
	if( $button->getName() == 'btnAlterar') {
		if( $rowNum == 1 ) {
			$button->setEnabled( false );
		}
	}
}
?>
<script>
function init() {
	fwGetGrid("municipio_sql.php",'gride');
}
// recebe fields e values do grid
function alterar(f,v){
	var dados = fwFV2O(f,v);
	fwModalBox('Altera��o','index.php?modulo=municipio_sql.php',300,800,null,dados);
}
</script>