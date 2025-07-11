<?php
  class db {
    private $conexion;
  
    public function __construct() {
      $sql_host="localhost";
      $sql_db = "noticiapty";
      $sql_user="N0oCh1";
      $sql_pass="N0oCh1Feng";

      $url_conexion = "mysql:host=$sql_host;dbname=$sql_db;charset=utf8mb4";
      try{
        $this->conexion = new PDO($url_conexion, $sql_user, $sql_pass);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if($this->conexion){
          echo "conexion exitosa";
        }
      }
      catch(PDOException $e){
        echo "error de conexion: ".$e->getMessage();
      }
    }
    public function getConexion (){
		  return $this->conexion;
	  }
    public function disconnect()
	  {
		  $this->conexion = null; 
	  }

    public function insert($tb_name, $cols, $val)
	  {
    	$cols = $cols ? "($cols)" : "";
   		$sql = "INSERT INTO $tb_name $cols VALUES ($val)";
    	try {
      	  if($this->conexion->exec($sql)){
            return true;
          };
  	  	} catch (PDOException $e) {
          echo "Error al insertar: " . $e->getMessage();
          return false;
    	}
	  }
    public function select($tb_name, $cols, $astriction=null)
	  {
      $cols = $cols ? "($cols)" : "";
      $sql = $astriction === null ? "SELECT $cols FROM $tb_name": "SELECT $cols FROM $tb_name where $astriction";
      try{
        $stml = $this->conexion->prepare($sql);
        $stml->execute();
        return $stml->fetchAll();
      }
      catch(PDOException $e){
        echo "Error al seleccionar: " . $e->getMessage();
        return false;
      }
    }
    public function update($tb_name, $string, $astriction)
	  {
      $sql = "UPDATE $tb_name SET $string where $astriction";
        try {
            if($this->conexion->exec($sql)){
              return true;
          }
        } catch (PDOException $e) {
            echo "Error al Modificar: " . $e->getMessage();
            return false;
        }
    }
  }
?>