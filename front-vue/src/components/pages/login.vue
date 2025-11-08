<script setup>
import { Mail, Lock } from 'lucide-vue-next';
</script>

<template>
    <section class="min-h-screen flex items-center justify-center bg-blue-700">
        <div class="w-full max-w-md">
            <!-- Logo e título -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center gap-3 mb-4"></a>
                <h1 class="text-3xl font-bold text-white mb-2">Sefaz é Massa</h1>
                <p class="text-white/80">Área Administrativa</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl text-center font-semibold mb-1">Login</h2>

                <form action="#" method="POST" class="space-y-4">
                    <!-- Campo Email -->
                    <div class="space-y-2">
                        <div class="flex gap-1 items-center">
                            <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                            <Mail class="w-4 h-4" />
                        </div>
                        <div class="relative">

                            <input id="email" name="email" type="email" placeholder="admin@sefaz.al.gov.br"
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
                            <input id="password" name="password" type="password" placeholder="********"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <!-- Botão Entrar -->
                    <button
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 rounded-md transition">
                        Entrar
                    </button>

                    <!-- Link Voltar -->
                    <div class="text-center">
                        <a href="/" class="text-sm text-gray-500 hover:text-blue-700 transition-colors">
                            Voltar para o site
                        </a>
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