<?php
class Acesso_userVO
{
	private $iduser = null;
	private $nom_user = null;
	private $pwd_user = null;
	private $sit_ativo = null;
	private $dat_inclusao = null;
	private $dat_update = null;
	public function Acesso_userVO( $iduser=null, $nom_user=null, $pwd_user=null, $sit_ativo=null, $dat_inclusao=null, $dat_update=null )
	{
		$this->setIduser( $iduser );
		$this->setNom_user( $nom_user );
		$this->setPwd_user( $pwd_user );
		$this->setSit_ativo( $sit_ativo );
		$this->setDat_inclusao( $dat_inclusao );
		$this->setDat_update( $dat_update );
	}
	//--------------------------------------------------------------------------------
	function setIduser( $strNewValue = null )
	{
		$this->iduser = $strNewValue;
	}
	function getIduser()
	{
		return $this->iduser;
	}
	//--------------------------------------------------------------------------------
	function setNom_user( $strNewValue = null )
	{
		$this->nom_user = $strNewValue;
	}
	function getNom_user()
	{
		return $this->nom_user;
	}
	//--------------------------------------------------------------------------------
	function setPwd_user( $strNewValue = null )
	{
		$this->pwd_user = $strNewValue;
	}
	function getPwd_user()
	{
		return $this->pwd_user;
	}
	//--------------------------------------------------------------------------------
	function setSit_ativo( $strNewValue = null )
	{
		$this->sit_ativo = $strNewValue;
	}
	function getSit_ativo()
	{
		return $this->sit_ativo;
	}
	//--------------------------------------------------------------------------------
	function setDat_inclusao( $strNewValue = null )
	{
		$this->dat_inclusao = $strNewValue;
	}
	function getDat_inclusao()
	{
		return is_null( $this->dat_inclusao ) ? date( 'Y-m-d h:i:s' ) : $this->dat_inclusao;
	}
	//--------------------------------------------------------------------------------
	function setDat_update( $strNewValue = null )
	{
		$this->dat_update = $strNewValue;
	}
	function getDat_update()
	{
		return is_null( $this->dat_update ) ? date( 'Y-m-d h:i:s' ) : $this->dat_update;
	}
	//--------------------------------------------------------------------------------
}
?>