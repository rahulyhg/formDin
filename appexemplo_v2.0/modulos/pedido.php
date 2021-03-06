<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDPEDIDO';
$frm = new TForm('Pedido',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);
$frm->addCssFile('css/css_form02.css');


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
//$listPessoa = Pessoa::selectAll();
//$frm->addSelectField('IDPESSOA', 'IDPESSOA',TRUE,$listPessoa,null,null,null,null,null,null,' ',null);

$frm->addGroupField('gpx1','Solicitante do Pedido');
	$frm->addNumberField('IDPESSOA', 'Cod',4,true,0);
	$frm->addCpfCnpjField('CPFCNPJ','CPF/CNPJ',true);
    $frm->addTextField('NOM_PESSOA', 'Nome',150,true,70,null,false);
    //Deve sempre ficar depois da definição dos campos
    $frm->setAutoComplete('CPFCNPJ'
        ,'vw_pessoa'// tabela
        ,'CPFCNPJ'	 		// campo de pesquisa
        ,'IDPESSOA|IDPESSOA,NOME|NOM_PESSOA,CPFCNPJ|CPFCNPJ' // campo que será atualizado ao selecionar o nome do município <campo_tabela> | <campo_formulario>
        ,true
        ,null 		        // campo do formulário que será adicionado como filtro
        ,null				// função javascript
        ,3					// Default 3, numero de caracteres minimos para disparar a pesquisa
        ,500				// 9: Default 1000, tempo após a digitação para disparar a consulta
        ,50					//10: máximo de registros que deverá ser retornado
        , null, null, null, null, true, null, null, true );
$frm->closeGroup();

$frm->addGroupField('gpx2','Info Complementar');
	$listTipo = Tipo::selectAllAtivoByMeta(Meta_tipoDAO::PAGAMENTO);
	$frm->addRadioField('IDTIPO_PAGAMENTO', 'Forma Pagamento:', true, $listTipo,null,null,null,4);
	//$frm->addSelectField('IDTIPO_PAGAMENTO', 'IDTIPO_PAGAMENTO',TRUE,$listTipo,null,null,null,null,null,null,' ',null);
	$frm->addDateField('DAT_PEDIDO', 'Data do Pedido',TRUE);
$frm->closeGroup();

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, true, false)->setClass('btnOragen', false);
$frm->addButton('Salvar', null, 'Salvar', null, null, false, false)->setClass('btnOragen', false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		try{
			if ( $frm->validate() ) {
				$vo = new PedidoVO();
				$frm->setVo( $vo );
				$resultado = Pedido::save( $vo );
				if($resultado==1) {
					$frm->setMessage('Registro gravado com sucesso!!!');
					$frm->clearFields();
				}else{
					$frm->setMessage($resultado);
				}
			}
		}
		catch (DomainException $e) {
			$frm->setMessage( $e->getMessage() );
		}
		catch (Exception $e) {
			MessageHelper::logRecord($e);
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'gd_excluir':
		try{
			$id = $frm->get( $primaryKey ) ;
			$resultado = Pedido::delete( $id );;
			if($resultado==1) {
				$frm->setMessage('Registro excluido com sucesso!!!');
				$frm->clearFields();
			}else{
				$frm->clearFields();
				$frm->setMessage($resultado);
			}
		}
		catch (DomainException $e) {
			$frm->setMessage( $e->getMessage() );
		}
		catch (Exception $e) {
			MessageHelper::logRecord($e);
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'gd_imprimir':
	    try{
			$_SESSION[APLICATIVO]['RELATORIO']=null;
			$_SESSION[APLICATIVO]['RELATORIO']['IDPEDIDO'] = $frm->getFieldValue('IDPEDIDO');
			$_SESSION[APLICATIVO]['RELATORIO']['NOM_PESSOA'] = $frm->getFieldValue('NOM_PESSOA');
			$_SESSION[APLICATIVO]['RELATORIO']['DAT_PEDIDO'] = $frm->getFieldValue('DAT_PEDIDO');
	        $frm->redirect('relatorio.php');
	    }
	    catch (DomainException $e) {
	        $frm->setMessage( $e->getMessage() );
	    }
	    catch (Exception $e) {
	        MessageHelper::reportarLog($e);
	        $frm->setMessage( $e->getMessage() );
	    }
		break;
	//--------------------------------------------------------------------------------
	case 'gd_itens':
	    try{
	        $frm->redirect('pedido_item.php');
	    }
	    catch (DomainException $e) {
	        $frm->setMessage( $e->getMessage() );
	    }
	    catch (Exception $e) {
	        MessageHelper::reportarLog($e);
	        $frm->setMessage( $e->getMessage() );
	    }
	    break;		
}


function getWhereGridParametersPedido(&$frm){
	$retorno = null;
	if($frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'IDPEDIDO'=>$frm->get('IDPEDIDO')
				,'IDPESSOA'=>$frm->get('IDPESSOA')
				,'IDTIPO_PAGAMENTO'=>$frm->get('IDTIPO_PAGAMENTO')
				,'DAT_PEDIDO'=>$frm->get('DAT_PEDIDO')
		);
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParametersPedido($frm);
	$page = PostHelper::get('page');
	$dados = Pedido::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Pedido::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',IDPESSOA|IDPESSOA'
					.',IDTIPO_PAGAMENTO|IDTIPO_PAGAMENTO'
					.',DAT_PEDIDO|DAT_PEDIDO'
					.',NOM_PESSOA|NOM_PESSOA'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Lista de Pedidos' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'pedido.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('IDPESSOA','id Pessoa');
	$gride->addColumn('NOM_PESSOA','Pessoa');
	$gride->addColumn('IDTIPO_PAGAMENTO','id Tipo Pagamento');
	$gride->addColumn('DES_TIPO_PAGAMENTO','Tipo Pagamento');
	$gride->addColumn('DAT_PEDIDO','Data do Pedido');

	$gride->addButton('Incluir Itens','gd_itens','btnItens',null,null,'images/gtk_add_17px.png');
	$gride->addButton('Relatório','gd_imprimir','btnImprimir',null,null,'impressora.gif');
	$gride->addButton('Alterar','gd_alterar','btnAlterar',null,null,'alterar.gif');
	$gride->addButton('Excluir','gd_excluir','btnExcluir',null,'Deseja Exlcuir o registro?','lixeira.gif');

	$gride->show();
	die();
}

$frm->addHtmlField('gride');
$frm->addJavascript('init()');
$frm->show();
?>
<script>
function init() {
	var Parameters = {"BUSCAR":""
					,"IDPEDIDO":""
					,"IDPESSOA":""
					,"IDTIPO_PAGAMENTO":""
					,"DAT_PEDIDO":""
					};
	fwGetGrid('pedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>