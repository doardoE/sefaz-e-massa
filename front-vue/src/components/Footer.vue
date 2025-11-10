<script setup>
</script>

<template>
    <!-- Imagem decorativa no topo do rodapé -->
    <div class="overflow-hidden">
        <img src="/background-maceio.svg" alt="Fundo de Maceió" class="w-full translate-y-[5px]" />
    </div>

    <!-- Rodapé -->
    <footer class="border-t border-gray-200 bg-[#EDF1F6] mt-auto">
        <div class="container mx-auto px-16 py-8">

            <div class="grid gap-8 md:grid-cols-3">
                <!-- Coluna 1 -->
                <div>
                    <h3 class="font-bold text-blue-700">SEFAZ É MASSA</h3>
                    <p class="bg-blue-700 w-1/3 h-2 mb-3"></p>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Portal de transparência das arrecadações municipais de Maceió, Alagoas. 
                        Um espaço dedicado à prestação de contas e ao fortalecimento da participação 
                        cidadã por meio do acesso a dados públicos.
                    </p>
                </div>

                <!-- Coluna 2 -->
                <div>
                    <h3 class="font-bold text-orange-700">LINKS ÚTEIS</h3>
                    <p class="bg-orange-700 w-1/3 h-2 mb-3"></p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><router-link to="/" class="hover:text-blue-700 transition-colors">Início</router-link></li>
                        <li><router-link to="/arrecadacoes" class="hover:text-blue-700 transition-colors">Arrecadações</router-link></li>
                        <li><router-link to="/login" class="hover:text-blue-700 transition-colors">Área Administrativa</router-link></li>
                    </ul>
                </div>

                <!-- Coluna 3 -->
                <div>
                    <h3 class="font-bold text-green-700">CONTATO</h3>
                    <p class="bg-green-700 w-1/3 h-2 mb-3"></p>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Prefeitura de Maceió - Secretaria Municipal de Fazenda<br />

                        Rua Dr. Pedro Monteiro 47, Maceió, AL, 57020-380<br />
                        Telefone: (82) 94002-8922<br />
                        Email: admin@sefaz.al.gov.br
                    </p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-300 text-center text-sm text-gray-500">
                © {{ new Date().getFullYear() }} Sefaz Maceió. Todos os direitos reservados.
            </div>
        </div>
    </footer>
</template>