<script setup>
import { BarChart3, LogOut } from 'lucide-vue-next';
</script>

<template>
    <header class="border-b border-gray-200 bg-white">
        <div class="container mx-auto px-6 md:px-16 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <!-- Ícone e título -->
                <a href="/" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-700">
                        <BarChart3 class="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Sefaz é Massa</h1>
                        <p class="text-xs text-gray-500">Transparência Municipal</p>
                    </div>
                </a>

                <!-- Navegação -->
                <nav class="flex flex-wrap justify-center md:justify-end items-center gap-3 sm:gap-4 md:gap-6">
                    <a href="/" class="text-sm font-medium text-gray-700 hover:text-blue-700 transition-colors">
                        Início
                    </a>

                    <a href="/arrecadacoes"
                        class="text-sm font-medium text-gray-700 hover:text-blue-700 transition-colors">
                        Arrecadações
                    </a>

                    <button
                        class="flex items-center border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-1 text-sm transition-colors">
                        <LogOut class="h-4 w-4 mr-2" />
                        Sair
                    </button>

                    <a href="/login">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md px-3 py-1 transition-colors">
                            Sessão de Administrador
                        </button>
                    </a>
                </nav>

            </div>
        </div>
    </header>

</template>