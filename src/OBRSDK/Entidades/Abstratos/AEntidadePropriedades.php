<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OBRSDK\Entidades\Abstratos;

/**
 * Description of ABancos
 *
 * @author Antonio
 */
abstract class AEntidadePropriedades extends AEntidadePreenchimento {

    public function getAtributes($entidade = null) {
        $atributos = get_object_vars($entidade == null ? $this : $entidade);
        $atributosPreenchidos = [];
        foreach ($atributos as $atributoNome => $valor) {
            $atributoValor = null;
            if ($valor != null) {
                $atributoValor = $this->getAtributoValor($valor);
            }
            
            if (count($atributoValor) > 0) {
                $atributosPreenchidos[$atributoNome] = $atributoValor;
            }
        }
        return $atributosPreenchidos;
    }

    private function percorrerArrayAtributo($valor) {
        $atributoValor = [];

        foreach ($valor as $v) {
            if (is_object($v)) {
                $atributoValor[] = $this->getAtributes($v);
            } else {
                $atributoValor[] = $v;
            }
        }

        return $atributoValor;
    }

    private function getAtributoValor($atributoValor) {
        if (is_array($atributoValor)) {
            return $this->percorrerArrayAtributo($atributoValor);
        } else if (is_object($atributoValor)) {
            return $this->getAtributes($atributoValor);
        } else {
            return $atributoValor;
        }
    }

    /// ====
    /// MAGIC METHODS
    /// ====

    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    public function __call($metodo, $param) {
        if (strtolower(substr($metodo, 0, 3)) == "get") {
            $get = substr($metodo, 3);
            $property = $this->pascalCaseParaUnderscore($get);

            return $this->$property;
        }
    }

}
