<?php
class ImagenUploader {
    private $carpeta;

    public function __construct($carpeta_destino = "../imagenDB/") {
        $this->carpeta = $carpeta_destino;
        if (!file_exists($this->carpeta)) {
            mkdir($this->carpeta, 0777, true);
        }
    }

    public function procesarImagen($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error al subir la imagen.");
        }

        $nombre_original = basename($file['name']);
        $tipo_imagen = $file['type'];
        $tamano = $file['size'];

        // Validaciones
        $permitidos = ['image/jpeg', 'image/png'];
        if (!in_array($tipo_imagen, $permitidos)) {
            throw new Exception("Tipo de imagen no permitido.");
        }

        if ($tamano > 5 * 1024 * 1024) {
            throw new Exception("Imagen demasiado grande (máx 5MB).");
        }

        $nombre_unico = uniqid() . "-" . $nombre_original;
        $ruta_original = $this->carpeta . $nombre_unico;

        // Mover imagen
        if (!move_uploaded_file($file['tmp_name'], $ruta_original)) {
            throw new Exception("No se pudo guardar la imagen.");
        }

        return [
            'ruta_original' => $ruta_original,
            'tipo' => $tipo_imagen
        ];
    }

    //genera una version mas liviana de la imagen original
    //para la miniatura
    public function generarMiniatura($ruta_original, $tipo_imagen) {
        list($ancho, $alto) = getimagesize($ruta_original);
        $nuevo_ancho = 100;
        $nuevo_alto = intval(($alto / $ancho) * $nuevo_ancho);

        $origen = ($tipo_imagen === 'image/jpeg') ? imagecreatefromjpeg($ruta_original) : imagecreatefrompng($ruta_original);
        $miniatura = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);

        imagecopyresampled($miniatura, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

        $ruta_miniatura = str_replace("imagenDB/", "imagenDB/thumb_", $ruta_original);

        if ($tipo_imagen === 'image/jpeg') {
            imagejpeg($miniatura, $ruta_miniatura);
        } else {
            imagepng($miniatura, $ruta_miniatura);
        }

        imagedestroy($origen);
        imagedestroy($miniatura);

        return $ruta_miniatura;
    }
}
