<!--
  Componente de Login
  Este componente maneja la autenticación de usuarios mediante un formulario de inicio de sesión.

  Props:
  - No requiere props

  Events:
  - @submit: Emite el evento cuando el formulario es enviado con los datos del usuario

  Uso:
  <Login @submit="handleLogin" />
-->
<template>
    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <!-- Elemento decorativo: Rombos -->
                <div class="diamond diamond-top"></div>
                <div class="diamond diamond-bottom"></div>

                <div class="login-header">
                    <h2>Bienvenido</h2>
                    <p>Inicia sesión para continuar</p>
                </div>

                <form @submit.prevent="handleSubmit" class="login-form">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="email" id="email" v-model="form.email" required
                                placeholder="Ingresa tu correo electrónico" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-group">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" id="password" v-model="form.password" required
                                placeholder="Ingresa tu contraseña" />
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" v-model="rememberMe" />
                            <span>Recordar mi sesión</span>
                        </label>
                        <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="submit-button">
                        <span>Iniciar Sesión</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>

                    <div class="register-link">
                        ¿No tienes una cuenta? <router-link to="/register">Regístrate aquí</router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const form = ref({
    email: '',
    password: ''
})

const rememberMe = ref(false)
const router = useRouter()

const handleSubmit = async () => {
    try {
        const response = await axios.post('http://localhost:8000/api/login', form.value)
        localStorage.setItem('auth-token', response.data.token)

        // Mostrar alerta de éxito
        await Swal.fire({
            title: '¡Bienvenido!',
            text: 'Has iniciado sesión correctamente',
            icon: 'success',
            confirmButtonText: 'Continuar',
            confirmButtonColor: '#667eea',
            timer: 2000,
            timerProgressBar: true
        })

        // Redirigir al dashboard
        router.push('/cocktail')
    } catch (error) {
        console.error('Error al iniciar sesión:', error)

        // Mostrar alerta de error
        await Swal.fire({
            title: 'Error',
            text: error.response?.data?.message || 'Ha ocurrido un error al iniciar sesión',
            icon: 'error',
            confirmButtonText: 'Intentar de nuevo',
            confirmButtonColor: '#667eea'
        })
    }
}
</script>

<style scoped>
/* Fondo con efecto futurista y rombos sutiles */
.login-page {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    overflow: hidden;
    padding: 2rem;
}

/* Rombos de fondo a nivel de página */
.login-page::before,
.login-page::after {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.05);
    transform: rotate(45deg);
    z-index: 1;
}

.login-page::before {
    top: -100px;
    left: -100px;
    border: 2px dashed rgba(255, 255, 255, 0.15);
}

.login-page::after {
    bottom: -100px;
    right: -100px;
    border: 2px dashed rgba(255, 255, 255, 0.15);
}

.login-container {
    width: 100%;
    max-width: 450px;
    margin: 0 auto;
    z-index: 2;
    /* Por encima de los rombos del background */
}

/* Card con aspecto iOS: suave, transparente y con bordes redondeados */
.login-card {
    position: relative;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 30px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(15px);
    overflow: hidden;
}

/* Rombos decorativos dentro del card */
.diamond {
    position: absolute;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0.3;
    clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
}

.diamond-top {
    top: -40px;
    right: -40px;
}

.diamond-bottom {
    bottom: -40px;
    left: -40px;
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.login-header h2 {
    color: #0f172a;
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.login-header p {
    color: #334155;
    font-size: 1rem;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

label {
    font-weight: 500;
    color: #1e293b;
    font-size: 0.9rem;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 1rem;
    color: #64748b;
}

input {
    width: 100%;
    padding: 0.875rem 1rem 0.875rem 3rem;
    border: none;
    border-radius: 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-color: #f1f5f9;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
}

input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #475569;
}

.remember-me input {
    width: auto;
    padding: 0;
}

.forgot-password {
    color: #667eea;
    text-decoration: none;
    transition: color 0.3s;
}

.forgot-password:hover {
    color: #764ba2;
}

.submit-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 20px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.submit-button:active {
    transform: translateY(0);
}

.register-link {
    text-align: center;
    color: #475569;
    font-size: 0.9rem;
}

.register-link a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.register-link a:hover {
    color: #764ba2;
}

@media (max-width: 480px) {
    .login-card {
        padding: 1.5rem;
    }

    .login-header h2 {
        font-size: 1.7rem;
    }

    input {
        padding: 0.75rem 1rem 0.75rem 2.5rem;
    }
}
</style>