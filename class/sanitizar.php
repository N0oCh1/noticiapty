<?PHP
  class SanitizarEntrada {      
    public static function limpiarCadena($cadena) {
        return trim(strip_tags($cadena));
    }
  }
?>