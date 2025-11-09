<script setup>
import { Mail, Lock } from 'lucide-vue-next';
import axios from 'axios';
import { useRouter } from 'vue-router'
import { ref } from 'vue';

const router = useRouter()

const email = ref('')
const password = ref('');
const errorMessage = ref('');


function fazerLogin() {

    axios.post('api/login', {
        email: email.value,
        password: password.value,
    })
        .then((resposta) => {
            const token = resposta.data.data.token;
            console.log('touken: ', token)

            // Salva o token e aplica no header do Axios
            localStorage.setItem('token', token);

            router.push('/arrecadacoes');
            console.log('touken: ', token)
        })
        .catch((error) => {
            if (error.response) {
                // erro do back-end
                errorMessage.value =
                    error.response.data.message || error.response.data.status || 'Erro desconhecido do servidor';
            } else if (error.request) {
                // erro de rede
                console.error('Erro de rede:', error.request);
                errorMessage.value = 'Não foi possível conectar ao servidor.';
            } else {
                // erro inesperado
                errorMessage.value = 'Ocorreu um erro inesperado.';
            }
        });
}
</script>

<template>
    <section class="min-h-screen flex items-center justify-center bg-blue-700">
        <div class="w-full max-w-md">
            <!-- Logo e título -->
            <div class="text-center mb-8">
                <router-link to="/" class="inline-flex items-center gap-3 mb-4"></router-link>
                <h1 class="text-3xl font-bold text-white mb-2">Sefaz é Massa</h1>
                <p class="text-white/80">Acesso a Recursos Administrativos</p>
            </div>

            <!-- Mensagem de erro -->
            <div v-if="errorMessage" class="m-4 p-2 border-l-4 border-red-600 bg-red-50 rounded-md shadow-sm">
                <h2 class="text-ls font-semibold text-red-800 mb-1">{{ errorMessage }}</h2>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl text-center font-semibold mb-1">Login</h2>

                <form @submit.prevent="fazerLogin" method="POST" class="space-y-4">
                    <!-- Campo Email -->
                    <div class="space-y-2">
                        <div class="flex gap-1 items-center">
                            <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                            <Mail class="w-4 h-4" />
                        </div>
                        <div class="relative">

                            <input id="email" name="email" type="email" v-model="email"
                                placeholder="admin@sefaz.al.gov.br"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <!-- Campo Senha -->
                    <div class="space-y-2">
                        <div class="flex gap-1 items-center">
                            <label for="password" class="text-sm font-medium text-gray-700">Senha</label>
                            <Lock class="w-4 h-4" />
                        </div>
                        <div class="relative">
                            <input id="password" name="password" type="password" v-model="password"
                                placeholder="********"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <!-- Botão Entrar -->
                    <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 rounded-md transition">
                        Entrar
                    </button>

                    <!-- Link Voltar -->
                    <div class="text-center">
                        <router-link to="/" class="text-sm text-gray-500 hover:text-blue-700 transition-colors">
                            Voltar para o site
                        </router-link>
                    </div>
                </form>

                <!-- Credenciais -->
                <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                    <p class="text-xs text-gray-700 mb-2 font-medium">Credenciais de teste:</p>
                    <p class="text-xs text-gray-600">
                        Email: <span class="font-mono">admin@sefaz.al.gov.br</span><br>
                        Senha: <span class="font-mono">admin123</span>
                    </p>
                </div>
            </div>
        </div>

    </section>
</template>