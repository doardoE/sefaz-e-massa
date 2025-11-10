<script setup>
import { BarChart3, LogOut } from 'lucide-vue-next';
import { ref, onMounted } from 'vue'
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const isLogado = ref(!!localStorage.getItem('token'))

// Atualiza também quando o componente monta
onMounted(() => {
    isLogado.value = !!localStorage.getItem('token')
})

function fazerLogout() {

    const storedToken = ref(localStorage.getItem("token"));

    if (!storedToken.value) {
        console.warn('Nenhum token encontrado.');
        return;
    }

    axios.post('api/logout', {}, {
        headers: {
            Authorization: `Bearer ${storedToken.value}`,
            Accept: 'application/json'
        }
    }).then(() => {
        // Remove token localmente
        delete axios.defaults.headers.common['Authorization'];
        localStorage.removeItem('token');
        router.push('/login')
    })
        .catch((error) => {
            if (error.response && error.response.data) {
                alert(error.response.data.message);
            } else {
                alert('Erro inesperado');
            }
        });
};


</script>

<template>
    <header class="border-b border-gray-200 bg-white">
        <div class="container mx-auto px-6 md:px-16 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <!-- Ícone e título -->
                <router-link to="/" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-700">
                        <BarChart3 class="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Sefaz é Massa</h1>
                        <p class="text-xs text-gray-500">Transparência Municipal</p>
                    </div>
                </router-link>

                <!-- Navegação -->
                <nav class="flex flex-wrap justify-center md:justify-end items-center gap-3 sm:gap-4 md:gap-6">
                    <router-link to="/" class="text-sm font-medium text-gray-700 hover:text-blue-700 transition-colors">
                        Início
                    </router-link>

                    <router-link to="/arrecadacoes"
                        class="text-sm font-medium text-gray-700 hover:text-blue-700 transition-colors">
                        Arrecadações
                    </router-link>

                    <button v-if="isLogado" @click="fazerLogout"
                        class="flex items-center border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-1 text-sm transition-colors">
                        <LogOut class="h-4 w-4 mr-2" />
                        Sair
                    </button>

                    <router-link to="/login">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 py-2 text-white text-sm rounded-md px-3 py-1 transition-colors">
                            Sessão de Administrador
                        </button>
                    </router-link>
                </nav>

            </div>
        </div>
    </header>

</template>