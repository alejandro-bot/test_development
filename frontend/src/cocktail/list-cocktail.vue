<template>
    <div class="background">
        <div class="cocktail-app">
            <header class="app-header">
                <div class="header-content">
                    <h1>Explorador de Cócteles</h1>
                    <button @click="handleLogout" class="logout-btn">
                        <span>Cerrar Sesión</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </div>
                <div class="search-container">
                    <input v-model="searchQuery" @input="searchCocktails" placeholder="Buscar cócteles..."
                        class="search-input" />
                </div>
            </header>

            <div class="cocktail-grid">
                <div v-for="cocktail in cocktails" :key="cocktail.idDrink" class="cocktail-card">
                    <div class="card-content">
                        <div class="cocktail-image-container">
                            <img :src="cocktail.strDrinkThumb" :alt="cocktail.strDrink" class="cocktail-image" />
                            <div class="image-overlay"></div>
                        </div>

                        <div class="cocktail-info">
                            <div class="info-header">
                                <h2>{{ cocktail.strDrink }}</h2>
                            </div>

                            <div class="info-details">
                                <div class="detail-item">
                                    <span class="detail-label">Categoría:</span>
                                    <span class="detail-value">{{ cocktail.strCategory }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Vaso:</span>
                                    <span class="detail-value">{{ cocktail.strGlass }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Tipo:</span>
                                    <span class="detail-value">{{ cocktail.strAlcoholic }}</span>
                                </div>
                            </div>

                            <div class="ingredients-list">
                                <h3>Ingredientes:</h3>
                                <ul>
                                    <li v-for="(ingredient, index) in getIngredients(cocktail)" :key="index">
                                        {{ ingredient }}
                                    </li>
                                </ul>
                            </div>

                            <div class="card-actions">
                                <template v-if="isCocktailSaved(cocktail)">
                                    <!-- Acciones para cócteles guardados -->
                                    <button @click="editCocktail(cocktail)" class="edit-btn action-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <span>Editar</span>
                                    </button>
                                    <button @click="showDetails(cocktail)" class="details-btn action-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12" y2="8"></line>
                                        </svg>
                                        <span>Ver Detalles</span>
                                    </button>
                                    <button @click.stop="confirmDeleteCocktail(cocktail)" class="delete-btn action-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                        </svg>
                                        <span>Eliminar</span>
                                    </button>
                                </template>
                                <template v-else>
                                    <!-- Acciones para cócteles no guardados -->
                                    <button @click="showDetails(cocktail)" class="details-btn action-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12" y2="8"></line>
                                        </svg>
                                        <span>Ver Detalles</span>
                                    </button>
                                    <button @click.stop="toggleSaveCocktail(cocktail)" class="save-btn action-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        <span>Guardar</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="loading">
                <div class="spinner"></div>
            </div>

            <!-- Modal de detalles -->
            <div v-if="selectedCocktail" class="modal-overlay" @click="closeModal">
                <div class="modal-content" @click.stop>
                    <button class="modal-close" @click="closeModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <div class="modal-header">
                        <img :src="selectedCocktail.strDrinkThumb" :alt="selectedCocktail.strDrink" class="modal-image">
                        <div class="modal-title-section">
                            <h2>{{ selectedCocktail.strDrink }}</h2>
                            <div class="modal-badges">
                                <span class="badge">{{ selectedCocktail.strCategory }}</span>
                                <span class="badge">{{ selectedCocktail.strAlcoholic }}</span>
                                <span class="badge">{{ selectedCocktail.strGlass }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="modal-section">
                            <h3>Ingredientes</h3>
                            <ul class="ingredients-grid">
                                <li v-for="(ingredient, index) in getIngredients(selectedCocktail)" :key="index"
                                    class="ingredient-item">
                                    <img :src="'https://www.thecocktaildb.com/images/ingredients/' + ingredient.split(' ').pop() + '-Small.png'"
                                        :alt="ingredient" class="ingredient-image">
                                    <span>{{ ingredient }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="modal-section">
                            <h3>Instrucciones</h3>
                            <p class="instructions-text">{{ selectedCocktail.strInstructions }}</p>
                        </div>
                        <div class="modal-footer">
                            <button @click="toggleSaveCocktail(selectedCocktail)" class="save-cocktail-btn"
                                :class="{ 'saved': isCocktailSaved(selectedCocktail) }">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                </svg>
                                {{ isCocktailSaved(selectedCocktail) ? 'Guardado' : 'Guardar Cóctel' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import axios from 'axios'


const router = useRouter()
const cocktails = ref([])
const searchQuery = ref('')
const loading = ref(false)
const savedCocktails = ref([])
const selectedCocktail = ref(null)

const getIngredients = (cocktail) => {
    const ingredients = []
    for (let i = 1; i <= 15; i++) {
        const ingredient = cocktail[`strIngredient${i}`]
        const measure = cocktail[`strMeasure${i}`]
        if (ingredient) {
            ingredients.push(`${measure ? measure + ' ' : ''}${ingredient}`)
        }
    }
    return ingredients
}

const isCocktailSaved = (cocktail) => {
    return savedCocktails.value.some(saved => saved.name === cocktail.strDrink)
}

// Función para obtener el token de autenticación
const getAuthToken = () => {
    return localStorage.getItem('auth-token')
}

const loadSavedCocktails = async () => {
    try {
        const token = getAuthToken()
        if (!token) {
            console.log('No hay token disponible')
            return
        }

        console.log('Token usado:', token) // Para debugging

        const response = await axios.get('http://localhost:8000/api/cocktails/saved', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })

        console.log('Respuesta de cócteles guardados:', response.data)
        savedCocktails.value = response.data.cocktails || []
        updateCocktailsWithSavedInfo()
    } catch (error) {
        console.error('Error completo:', error)
        console.error('Error al cargar cócteles guardados:', error.response || error)
        if (error.response?.status === 401) {
            Swal.fire({
                icon: 'error',
                title: 'Error de autenticación',
                text: 'Por favor, verifica tu sesión'
            })
        }
    }
}

const updateCocktailsWithSavedInfo = () => {
    cocktails.value = cocktails.value.map(cocktail => {
        const savedCocktail = savedCocktails.value.find(saved => saved.name === cocktail.strDrink)
        if (savedCocktail) {
            return {
                ...cocktail,
                id: savedCocktail.id,
                saved: true,
                savedData: savedCocktail
            }
        }
        return {
            ...cocktail,
            saved: false
        }
    })
}

const toggleSaveCocktail = async (cocktail) => {
    try {
        const token = getAuthToken()
        if (!token) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se encontró la información de autenticación'
            })
            return
        }

        const ingredients = []
        for (let i = 1; i <= 15; i++) {
            const ingredientName = cocktail[`strIngredient${i}`]
            const measure = cocktail[`strMeasure${i}`]
            if (ingredientName) {
                ingredients.push({
                    name: ingredientName.trim(),
                    measure: measure ? measure.trim() : null
                })
            }
        }

        const cocktailData = {
            name: cocktail.strDrink,
            image: cocktail.strDrinkThumb,
            category: cocktail.strCategory,
            glass: cocktail.strGlass,
            type: cocktail.strAlcoholic,
            instructions: cocktail.strInstructions,
            ingredients: ingredients
        }

        const response = await axios.post(
            'http://localhost:8000/api/cocktails/save',
            cocktailData,
            {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }
        )

        if (response.data.cocktail) {
            await loadSavedCocktails()

            Swal.fire({
                icon: 'success',
                title: '¡Guardado!',
                text: 'El cóctel se ha guardado correctamente',
                timer: 1500,
                showConfirmButton: false
            })
        }
    } catch (error) {
        console.error('Error completo:', error)
        console.error('Error al guardar el cóctel:', error.response || error)
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response?.data?.message || 'Error al procesar la solicitud'
        })
    }
}

const fetchCocktails = async (url) => {
    loading.value = true
    try {
        const response = await fetch(url)
        const data = await response.json()
        cocktails.value = data.drinks || []
        await loadSavedCocktails() // Cargar información de guardado después de obtener cócteles
    } catch (error) {
        console.error('Error al cargar los cócteles:', error)
    } finally {
        loading.value = false
    }
}

const searchCocktails = async () => {
    if (searchQuery.value.length < 2) {
        // Si la búsqueda es muy corta, mostrar cócteles aleatorios
        await fetchCocktails('https://www.thecocktaildb.com/api/json/v1/1/search.php?f=a')
        return
    }
    await fetchCocktails(`https://www.thecocktaildb.com/api/json/v1/1/search.php?s=${searchQuery.value}`)
}

const showDetails = (cocktail) => {
    selectedCocktail.value = cocktail
    document.body.style.overflow = 'hidden'
}

const closeModal = () => {
    selectedCocktail.value = null
    document.body.style.overflow = 'auto'
}

const handleLogout = async () => {
    try {
        await Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Deseas cerrar tu sesión?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#42b983',
            cancelButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('auth-token')
                router.push('/')
            }
        })
    } catch (error) {
        console.error('Error al cerrar sesión:', error)
    }
}

const editCocktail = (cocktail) => {
    // Por ahora solo mostraremos un mensaje
    Swal.fire({
        title: 'Próximamente',
        text: 'La función de edición estará disponible pronto',
        icon: 'info'
    });
}

const confirmDeleteCocktail = async (cocktail) => {
    // Primero verificamos si tenemos el ID correcto
    const savedCocktail = savedCocktails.value.find(saved => saved.name === cocktail.strDrink)
    if (!savedCocktail) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el cóctel en la base de datos'
        })
        return
    }

    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar el cóctel "${cocktail.strDrink}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    })

    if (result.isConfirmed) {
        try {
            const token = getAuthToken()
            if (!token) {
                throw new Error('No se encontró la información de autenticación')
            }

            // Usamos el ID del cóctel guardado
            await axios.delete(`http://localhost:8000/api/cocktails/delete/${savedCocktail.id}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })

            // Actualizamos la lista de cócteles guardados
            savedCocktails.value = savedCocktails.value.filter(c => c.id !== savedCocktail.id)
            // Actualizamos la UI
            updateCocktailsWithSavedInfo()

            Swal.fire({
                icon: 'success',
                title: '¡Eliminado!',
                text: 'El cóctel ha sido eliminado correctamente',
                timer: 1500,
                showConfirmButton: false
            })
        } catch (error) {
            console.error('Error completo:', error)
            console.error('Error al eliminar el cóctel:', error.response || error)
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.response?.data?.message || 'No se pudo eliminar el cóctel'
            })
        }
    }
}

onMounted(async () => {
    const token = getAuthToken()
    console.log('Token al montar:', token) // Para debugging
    await fetchCocktails('https://www.thecocktaildb.com/api/json/v1/1/search.php?f=a')
})

// Observador para la búsqueda
watch(searchQuery, async () => {
    if (searchQuery.value.length < 2) {
        await fetchCocktails('https://www.thecocktaildb.com/api/json/v1/1/search.php?f=a')
        return
    }
    await fetchCocktails(`https://www.thecocktaildb.com/api/json/v1/1/search.php?s=${searchQuery.value}`)
})
</script>

<style scoped>
.background {
    min-height: 100vh;
    background-color: #ffffff;
    padding: 20px;
}

.cocktail-app {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.app-header {
    margin-bottom: 40px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 500;
}

.logout-btn:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.app-header h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin: 0;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.search-container {
    max-width: 500px;
    margin: 0 auto;
}

.search-input {
    width: 100%;
    padding: 12px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 1rem;
    outline: none;
    transition: all 0.3s;
    background-color: rgba(255, 255, 255, 0.9);
}

.search-input:focus {
    border-color: #42b983;
    box-shadow: 0 0 0 3px rgba(66, 185, 131, 0.2);
}

.cocktail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 20px;
}

.cocktail-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
    border: 1px solid rgba(0, 0, 0, 0.05);
    backdrop-filter: blur(10px);
}

.cocktail-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.card-content {
    position: relative;
}

.cocktail-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.cocktail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.3));
}

.cocktail-info {
    padding: 25px;
    background: white;
}

.info-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.info-header h2 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: 600;
}

.info-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: rgba(0, 0, 0, 0.02);
    border-radius: 12px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-label {
    font-size: 0.8rem;
    color: #666;
    font-weight: 500;
}

.detail-value {
    font-size: 0.9rem;
    color: #2c3e50;
    font-weight: 600;
}

.ingredients-list {
    margin-bottom: 20px;
    height: 130px;
}

.ingredients-list h3 {
    font-size: 1rem;
    color: #2c3e50;
    margin-bottom: 10px;
}

.ingredients-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.ingredients-list li {
    background: rgba(66, 185, 131, 0.1);
    color: #42b983;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.card-actions {
    display: flex;
    gap: 8px;
    margin-top: 20px;
}

.action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
}

.details-btn {
    background: #42b983;
    color: white;
}

.details-btn:hover {
    background: #3aa876;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(66, 185, 131, 0.2);
}

.save-btn {
    background: #f8f9fa;
    color: #2c3e50;
    border: 2px solid #e9ecef;
}

.save-btn:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.edit-btn {
    background: #ffc107;
    color: #2c3e50;
}

.edit-btn:hover {
    background: #e0a800;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
}

.delete-btn {
    background: #dc3545;
    color: white;
}

.delete-btn:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
}

.loading {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 200px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #42b983;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 20px;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    animation: modalFadeIn 0.3s ease-out;
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #2c3e50;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
    z-index: 2;
}

.modal-close:hover {
    transform: rotate(90deg);
    background: white;
}

.modal-header {
    position: relative;
    height: 300px;
}

.modal-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 20px 20px 0 0;
}

.modal-title-section {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 30px;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    border-radius: 20px 20px 0 0;
}

.modal-title-section h2 {
    color: white;
    margin: 0;
    font-size: 2rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.modal-badges {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.9rem;
    backdrop-filter: blur(5px);
}

.modal-body {
    padding: 30px;
}

.modal-section {
    margin-bottom: 30px;
}

.modal-section h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 1.4rem;
}

.ingredients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    padding: 0;
    list-style: none;
}

.ingredient-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    background: rgba(66, 185, 131, 0.1);
    padding: 15px;
    border-radius: 15px;
    transition: all 0.3s;
}

.ingredient-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.ingredient-image {
    width: 60px;
    height: 60px;
    object-fit: contain;
    margin-bottom: 10px;
}

.instructions-text {
    line-height: 1.6;
    color: #2c3e50;
    background: rgba(0, 0, 0, 0.02);
    padding: 20px;
    border-radius: 15px;
}

.modal-footer {
    padding: 20px 30px;
    display: flex;
    justify-content: flex-end;
    border-top: 1px solid #e9ecef;
}

.save-cocktail-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 15px;
    background: #42b983;
    color: white;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    font-size: 1.1rem;
    font-weight: 500;
    transition: all 0.3s;
}

.save-cocktail-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(66, 185, 131, 0.3);
}

.save-cocktail-btn.saved {
    background: #dc3545;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
