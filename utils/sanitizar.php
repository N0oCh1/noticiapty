<?php
class SanitizarEntrada
{
  public static function limpiarCadena($cadena)
  {
    return htmlspecialchars(trim(strip_tags($cadena)), ENT_QUOTES, 'UTF-8');
  }
}
