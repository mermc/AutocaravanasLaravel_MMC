# AutocaravanasLaravel_MMC 

**AutocaravanasLaravel_MMC** es una plataforma web desarrollada con Laravel para la gestión de alquiler de autocaravanas. Permite a los usuarios registrarse, iniciar sesión, consultar vehículos disponibles, realizar reservas y realizar pagos de forma segura mediante Stripe. Los administradores pueden gestionar vehículos y reservas desde un panel de administración.

🔗 Sitio web en producción: [caravanas.milanmc.me](https://caravanas.milanmc.me/)

---

## Características principales

- Registro e inicio de sesión de usuarios
- Sistema de roles: Administrador / Usuario
- CRUD de reservas para usuarios
- CRUD de vehículos y reservas para administradores
- Integración con **Stripe** para pagos
- Confirmación de reservas por correo electrónico
- Listado dinámico de autocaravanas disponibles según fechas

---

##  Tecnologías utilizadas

- Laravel 12
- Base de datos **MySQL**
- Stripe para pagos
- Sistema de autenticación y roles (Laravel default)
- Bootstrap para el frontend (presumiblemente)

---

## Requisitos del sistema

- PHP >= 8.3
- Composer  
- MySQL >= 5.7  
- Node.js y NPM  
- Laravel (verifica con: `php artisan --version`)

---

## ⚙Instalación

1. **Clona el repositorio:**

   ```bash
   git clone https://github.com/mermc/AutocaravanasLaravel_MMC.git
   cd AutocaravanasLaravel_MMC

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
