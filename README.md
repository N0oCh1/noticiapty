# NoticiaPTY 📰

**Sistema de Noticias Web para Panamá**

NoticiaPTY es una plataforma web desarrollada en PHP para la gestión y visualización de noticias con enfoque en contenido panameño. El sistema permite a usuarios registrados crear, editar y gestionar noticias organizadas por categorías.

## ✨ Características Principales

- **Sistema de Usuarios Multi-rol**: Admin, Supervisor, Editor y Usuario
- **Gestión de Noticias**: Crear, editar, aprobar y publicar noticias
- **Categorías**: Deportes, Política, Tecnología, Entretenimiento
- **Sistema de Comentarios**: Interacción entre usuarios en las noticias
- **Sistema de Likes**: Valoración de noticias por parte de los usuarios
- **Gestión de Imágenes**: Subida y procesamiento de imágenes con miniaturas
- **Contador de Visitas**: Estadísticas de tráfico del sitio
- **Panel de Administración**: Herramientas de gestión para administradores
- **Responsive Design**: Interfaz adaptable a dispositivos móviles

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP (Programación Orientada a Objetos)
- **Base de Datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Estilos**: Font Awesome Icons
- **Librerías**: SweetAlert2 para notificaciones

## 📋 Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensiones PHP: mysqli, gd (para procesamiento de imágenes)

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/N0oCh1/noticiapty.git
   cd noticiapty
   ```

2. **Configurar la base de datos**
   - Importar el archivo `noticiapty(actualizada) (2).sql` en MySQL
   - Configurar las credenciales de la base de datos en `class/C_conexion.php`

3. **Configurar el servidor web**
   - Apuntar el documento root a la carpeta del proyecto
   - Asegurar que PHP tiene permisos de escritura en la carpeta `imagenDB/`

4. **Acceder al sistema**
   - Abrir `http://localhost/noticiapty` en el navegador
   - El sistema redirigirá automáticamente a `/app/index`

## 👥 Roles de Usuario

### **Usuario Regular**
- Ver noticias públicas
- Comentar en noticias
- Dar like a noticias
- Editar su perfil

### **Editor**
- Todas las funciones de usuario regular
- Crear noticias (pendientes de aprobación)
- Editar sus propias noticias

### **Supervisor**
- Todas las funciones de editor
- Aprobar/rechazar noticias de editores
- Acceso al panel de gestión de noticias

### **Administrador**
- Acceso completo al sistema
- Gestión de usuarios
- Gestión completa de noticias
- Configuración del sistema

## 📁 Estructura del Proyecto

```
noticiapty/
├── api/                          # Controladores API
│   ├── controllerNoticia.php     # Gestión de noticias
│   ├── controllerUsuarios.php    # Gestión de usuarios
│   ├── controllerLogin.php       # Autenticación
│   └── ...
├── app/                          # Aplicación principal
│   ├── index.php                 # Página principal
│   ├── auth/                     # Sistema de autenticación
│   ├── crear-noticia/            # Formulario de creación
│   ├── detalle-noticia/          # Vista detallada de noticias
│   ├── administrar-noticia/      # Panel de administración
│   └── ...
├── class/                        # Clases PHP (Modelo)
│   ├── C_conexion.php           # Conexión a base de datos
│   ├── C_noticia.php            # Lógica de noticias
│   ├── C_usuario.php            # Lógica de usuarios
│   └── ...
├── imagenDB/                     # Almacenamiento de imágenes
├── utils/                        # Utilidades y helpers
└── noticiapty(actualizada) (2).sql  # Script de base de datos
```

## 🔒 Seguridad

El sistema implementa varias medidas de seguridad:

- **Sanitización de datos**: Prevención de inyección SQL y XSS
- **Validación de sesiones**: Control de acceso por roles
- **Validación de archivos**: Restricciones en la subida de imágenes
- **Escape de salida**: Prevención de ejecución de código malicioso

## 📊 Base de Datos

### Tablas principales:
- `usuarios`: Información de usuarios y roles
- `noticias`: Contenido de noticias
- `categorias`: Categorías de noticias
- `comentarios`: Comentarios en noticias
- `likes`: Sistema de valoración
- `imagenes`: Gestión de archivos multimedia
- `visitas`: Estadísticas de tráfico

## 🤝 Contribuir

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear un Pull Request

## 👨‍💻 Desarrolladores

- **N0oCh1** - Desarrollador principal
- **Kelvin** - Colaborador (rama kelvin)

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📞 Contacto

- GitHub: [@N0oCh1](https://github.com/N0oCh1)
- Proyecto: [NoticiaPTY](https://github.com/N0oCh1/noticiapty)

---

**NoticiaPTY** - *Manteniendo informado a Panamá* 🇵🇦
