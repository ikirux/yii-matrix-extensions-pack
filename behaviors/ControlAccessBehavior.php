<?php

class ControlAccessBehavior extends CBehavior
{
    /**
    * @var array | string
    * Lista de acciones publicas o sin control de permisos,
    * puede ser una lista de acciones, o "*" para indicar todas las acciones
    */
    public $grantedActions = '*';

    public function isAccessGranted($action)
    {
        if (is_array($this->grantedActions)) {
            return in_array($action, $this->grantedActions);    
        } else {
            return $this->grantedActions === '*';
        }        
    }
}