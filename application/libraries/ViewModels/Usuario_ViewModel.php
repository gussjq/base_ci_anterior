<?php
require_once 'Reportes_ViewModel.php';
class Usuario_ViewModel extends Reportes_ViewModel {
    
    public $idUsuario;
    public $idRol;
    public $cNombre;
    public $cApellidoPaterno;
    public $cApellidoMaterno;
    public $cCorreo;
    public $cContrasena;
    public $dtFechaAcceso;
    public $iIntentosAcceso;
    public $bHabilitado;
    public $bBorradoLogico;
    public $dtFechaIntentosAcceso;
    public $bBloqueado;
    public $cImagen;
    public $cRecuperar;
    
    public $cNombreRol;
    public $cAliasRol;
    
    public $bNuevo;
    public $idIdioma;
    
    public $bAdministrador;
    
    
}
