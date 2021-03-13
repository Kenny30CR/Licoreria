<?php
namespace App\Controllers;
use Psr\Container\ContainerInterface;
use \PDO;

class BaseBD{

    protected $container;
    private $tabla;
    private $campoCodigo;


    public function __construct(ContainerInterface $c){
        $this->container= $c;
    } 
    private function cadenaFiltro($campos, $valores){
        $filtro = "";
        for($i=0; $i < sizeof($campos);$i++){
            $filtro .= $campos[$i] . " LIKE '%" . $valores[$i] . "%' and ";
        }

        $filtro = substr($filtro , 0, -4);
        return $filtro;
    }
    private function obtConst($valor){
        switch (gettype($valor)){
            case "integer": return PDO::PARAM_INT; break;
            case "string": return PDO::PARAM_STR; break;
            default: return PDO::PARAM_STR; break;
        }
    }
    private function generarConsulta($datos, $codigo=null){
        $cadena ="(";
        if($codigo != null){
            $cadena.= ':'.$this->campoCodigo.',';
        }
        foreach($datos as $campo => $valor){
            $cadena.= ':'.$campo.',';  
        }
        $cadena= trim($cadena,',');
        $cadena .= ')';
        return $cadena;
    }
    public function numRegs($datos=false){
        $sql = "select count(*) as regs from ". $this->tabla;
        if($datos){
            $sql .= " where ";
            foreach($datos as $clave => $valor)
             $sql .= "$clave LIKE '%$valor%' and ";
            $sql= substr($sql, 0, -5);
        } 

        $conexion = $this->container->get('bd');
        $consulta= $conexion->prepare($sql);
        $consulta->execute();
        $registro = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta=null;
        $conexion=null;
        return $registro["regs"];
    }
    public function iniciar($tb, $cC){
        $this->tabla=$tb;
        $this->campoCodigo=$cC;
    }
    public function todos($pag,$lim){
        $pag= ($pag-1)*$lim;
        $conexion = $this->container->get('bd');
        $sql= "call todos".$this->tabla."(:pag, :lim);";
        $consulta= $conexion->prepare($sql);
        $consulta->bindParam(':pag', $pag, PDO::PARAM_INT);
        $consulta->bindParam(':lim', $lim, PDO::PARAM_INT);
        $consulta->execute();
        $datos=[];
        if($consulta->rowCount()>0){
            $i=0;
            while($registro = $consulta->fetch(PDO::FETCH_ASSOC)){
                $i++;
                foreach($registro as $clave => $valor){
                    $datos[$i][$clave]= $valor;
                }
            }
        }
        $consulta=null;
        $conexion=null;
        return $datos;
    }
    public function buscar($codigo){
        $conexion = $this->container->get('bd');
        $sql= "call buscar".$this->tabla."(:codigo);";
        $consulta= $conexion->prepare($sql);
        $consulta->bindParam(':codigo', $codigo, $this->obtConst($codigo));
      
        $consulta->execute();
        $datos = $consulta->fetchAll();
        $consulta=null;
        $conexion=null;
        return $datos;
    }
    public function filtrar($pag,$lim, $datos=false){
        $pag =($pag-1) * $lim;
        $sql = "select * from ". $this->tabla;
        if($datos){
            $sql .= " where ";
            foreach($datos as $clave => $valor)
             $sql .= "$clave LIKE '%$valor%' and ";
            $sql= substr($sql, 0, -5);
        } 
        $sql.= " LIMIT :pag, :lim";
        $conexion = $this->container->get('bd');
        $consulta= $conexion->prepare($sql);
        $consulta->bindParam(':pag', $pag, PDO::PARAM_INT);
        $consulta->bindParam(':lim', $lim, PDO::PARAM_INT);
        $consulta->execute();
        $datos=[];
        if($consulta->rowCount()>0){
            $i=0;
            while($registro = $consulta->fetch(PDO::FETCH_ASSOC)){
                $i++;
                foreach($registro as $clave => $valor){
                    $datos[$i][$clave]= $valor;
                }
            }
        }
        $consulta=null;
        $conexion=null;
        return $datos;
    }
    /*public function filtrar($campos , $valores){
        $filtro = $this->cadenaFiltro($campos,$valores);
        $sql= "select * from ".$this->tabla." where $filtro";
        $conexion = $this->container->get('bd');
        $consulta= $conexion->prepare($sql);
        $consulta->execute();
        $datos=[];
        if($consulta->rowCount()>0){
            $i=0;
            while($registro = $consulta->fetch(PDO::FETCH_ASSOC)){
                $i++;
                foreach($registro as $clave => $valor){
                    $datos[$i][$clave]= $valor;
                }
            }
        }
        $consulta=null;
        $conexion=null;
        return $datos;
    }*/
    public function guardar($datos, $codigo=null){
        if($codigo != null){
        $sql= 'select editar'.$this->tabla.$this->generarConsulta($datos,$codigo); //<- editar nuevo curso
        }else{
            $sql= 'select nuevo'.$this->tabla.$this->generarConsulta($datos); //<- crear nuevo curso
        }
        $conexion = $this->container->get('bd');
        $consulta= $conexion->prepare($sql);
        foreach($datos as $campo=> $valor){
            //$valor = filter_var($valor , FILTER_SANITIZE_STRING);
            $consulta->bindValue(':'.$campo, $valor, $this->obtConst($valor));
        }
        if($codigo != null){
        $consulta->bindParam(':'.$this->campoCodigo,$codigo, $this->obtConst($codigo));
        }
        $consulta->execute();
        $datos = $consulta->fetch(PDO::FETCH_NUM);
        $consulta=null;
        $conexion=null;
        return $datos;
    }
    public function eliminarbd($codigo){
        $sql= 'select eliminar'.$this->tabla.'(:codigo);'; //<- eliminar nuevo curso
        $conexion = $this->container->get('bd');
        $consulta= $conexion->prepare($sql);
        $consulta->bindParam(':codigo', $codigo, $this->obtConst($codigo));
        $consulta->execute();
        $datos = $consulta->fetch(PDO::FETCH_NUM);
        $consulta=null;
        $conexion=null;
        return $datos;
    }
    public function sigCodigo($tabla){
        $sql= 'select siguienteCodigo(:tabla);'; //<- eliminar nuevo curso
        $conexion = $this->container->get('bd');
        $consulta= $conexion->prepare($sql);
        $consulta->bindParam(':tabla', $tabla, PDO::PARAM_STR);
        $consulta->execute();
        $datos = $consulta->fetch(PDO::FETCH_NUM);
        $consulta=null;
        $conexion=null;
        return $datos;
    }
    
    public function modificarToken($id, $token= ""){
        $sql= 'select modificarToken(:id, :token)';
        $conexion = $this->container->get('bd');
        $consulta= $conexion->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':token', $token, PDO::PARAM_STR);
        $consulta->execute();
        $datos = $consulta->fetch(PDO::FETCH_NUM);
        $consulta=null;
        $conexion=null;
        return $datos;
        
    }

    public function validarRefresco($id, $token){
        $sql= 'select * from usuario where id= :id and rft= :token';
        $conexion = $this->container->get('bd');
        $consulta= $conexion->prepare($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':token', $token, PDO::PARAM_STR);
        $consulta->execute();
        $datos = $consulta->fetchAll();
        $consulta=null;
        $conexion=null;
        return $datos;
    }
}