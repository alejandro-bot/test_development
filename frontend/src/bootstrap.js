import axios from 'axios'

// Configuración de axios
axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'

// Interceptor para agregar el token de autenticación
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('auth-token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

// Interceptor para manejar errores de autenticación
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('auth-token')
            window.location.href = '/'
        }
        return Promise.reject(error)
    }
) 