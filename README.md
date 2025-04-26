# Sistema de Gestión de Usuarios

Este proyecto es una aplicación web moderna para la gestión de usuarios, construida con una arquitectura de cliente-servidor separada (frontend y backend).

## Arquitectura del Proyecto

El proyecto está dividido en dos partes principales:

### Backend (`/backend`)

- Servidor API RESTful
- Manejo de autenticación y autorización
- Gestión de base de datos
- Validación de datos
- Seguridad y middleware

### Frontend (`/frontend`)

- Interfaz de usuario moderna con Vue.js
- Gestión de estado con Pinia
- TypeScript para type safety
- Componentes reutilizables
- Enrutamiento con Vue Router

## ¿Por qué Vue.js en lugar de jQuery?

1. **Arquitectura Moderna**:

   - Vue.js sigue el patrón MVVM (Model-View-ViewModel)
   - Sistema de componentes reutilizables
   - Reactividad automática de datos

2. **Mantenibilidad**:

   - Código más organizado y estructurado
   - TypeScript para type safety
   - Mejor manejo de estado con Pinia

3. **Rendimiento**:

   - Virtual DOM para actualizaciones eficientes
   - Mejor manejo de eventos
   - Optimización automática de renderizado

4. **Ecosistema**:
   - Herramientas modernas de desarrollo
   - Comunidad activa
   - Amplia documentación

## Instalación

### Requisitos Previos

- Node.js (v16 o superior)
- npm o yarn
- Git

### Backend

```bash
cd backend
composer install
php artisan passport:client --personal
php artisan passport:keys --force
composer run dev o php artisan serve
```

### Frontend

```bash
cd frontend
npm install
npm run dev
```

## Autenticación

El sistema utiliza JWT (JSON Web Tokens) para la autenticación:

1. **Login**:

   - El usuario ingresa sus credenciales
   - El backend valida y genera un JWT
   - El token se almacena en el estado de Pinia

2. **Protección de Rutas**:

   - Middleware de autenticación en el backend
   - Guards de navegación en Vue Router
   - Validación de tokens en cada petición

3. **Gestión de Estado**:
   - Pinia store para manejar el estado del usuario
   - Persistencia del token
   - Manejo de sesiones

## Uso de la Aplicación

1. **Registro de Usuarios**:

   - Crear nuevas cuentas de usuario
   - Asignar roles (admin/user)
   - Validación de datos

2. **Gestión de Usuarios**:

   - Listar usuarios
   - Editar perfiles
   - Eliminar usuarios (solo admin)

3. **Autenticación**:
   - Login/Logout
   - Recuperación de contraseña
   - Verificación de email

## Estructura de Directorios

```
├── backend/
│   ├── src/
│   │   ├── controllers/
│   │   ├── models/
│   │   ├── routes/
│   │   └── middleware/
│   └── package.json
│
└── frontend/
    ├── src/
    │   ├── components/
    │   ├── stores/
    │   ├── types/
    │   ├── router/
    │   └── views/
    └── package.json
```

## Tecnologías Principales

### Backend

- Passport

### Frontend

- Vue.js 3
- TypeScript
- Pinia
- Vue Router
- Axios
- Sweet Alert

5. Abre un Pull Request
