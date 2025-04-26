import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/auth/Login.vue'
import Register from '../components/auth/Register.vue'
import Cocktail from '../cocktail/index.vue'
import Swal from 'sweetalert2'

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { requiresGuest: true }
  },
  {
    path: '/cocktail',
    name: 'Cocktail',
    component: Cocktail,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Guard de navegación
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth-token')
  const isAuthenticated = !!token

  // Si la ruta requiere autenticación y el usuario no está autenticado
  if (to.meta.requiresAuth && !isAuthenticated) {
    Swal.fire({
      title: 'Acceso denegado',
      text: 'Debes iniciar sesión para acceder a esta página',
      icon: 'warning',
      confirmButtonText: 'Ir a login',
      confirmButtonColor: '#42b983',
      showCancelButton: false
    }).then(() => {
      next('/')
    })
    return
  }

  // Si la ruta es para invitados y el usuario está autenticado
  if (to.meta.requiresGuest && isAuthenticated) {
    next('/cocktail')
    return
  }

  next()
})

export default router 