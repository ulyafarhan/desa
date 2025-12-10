<script setup>
import { ref, nextTick, onMounted, watch } from 'vue';
import axios from 'axios';
import { PaperAirplaneIcon, TrashIcon, XMarkIcon, ChatBubbleLeftRightIcon } from '@heroicons/vue/24/solid';

const isOpen = ref(false);
const messages = ref([]);
const userInput = ref('');
const isLoading = ref(false);
const messagesContainer = ref(null);

const toggleChat = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value && messages.value.length === 0) {
        fetchMessages();
    }
};

const scrollToBottom = async () => {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

const fetchMessages = async () => {
    try {
        const response = await axios.get('/api/ai-chat');
        messages.value = response.data;
        scrollToBottom();
    } catch (error) {
        console.error('Error fetching chats:', error);
    }
};

const sendMessage = async () => {
    if (!userInput.value.trim() || isLoading.value) return;

    const message = userInput.value;
    userInput.value = '';
    
    // Optimistic update
    messages.value.push({ role: 'user', content: message });
    scrollToBottom();
    isLoading.value = true;

    try {
        const response = await axios.post('/api/ai-chat', { message: message });
        messages.value.push(response.data);
    } catch (error) {
        messages.value.push({ role: 'model', content: 'Maaf, terjadi kesalahan jaringan.' });
    } finally {
        isLoading.value = false;
        scrollToBottom();
    }
};

const clearChat = async () => {
    if (!confirm('Hapus semua riwayat chat?')) return;
    try {
        await axios.delete('/api/ai-chat');
        messages.value = [];
    } catch (error) {
        console.error('Error clearing chat:', error);
    }
};
</script>

<template>
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-4 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-4 scale-95"
        >
            <div v-if="isOpen" class="mb-4 w-[350px] sm:w-[400px] h-[500px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
                <div class="bg-blue-600 p-4 flex justify-between items-center text-white">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-white/20 rounded-lg">
                            <ChatBubbleLeftRightIcon class="w-5 h-5" />
                        </div>
                        <h3 class="font-semibold">Asisten Desa AI</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="clearChat" class="p-1 hover:bg-white/20 rounded transition" title="Hapus Chat">
                            <TrashIcon class="w-4 h-4" />
                        </button>
                        <button @click="toggleChat" class="p-1 hover:bg-white/20 rounded transition">
                            <XMarkIcon class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900">
                    <div v-if="messages.length === 0" class="text-center text-gray-500 mt-10 text-sm">
                        <p>Halo! Ada yang bisa saya bantu terkait layanan desa?</p>
                    </div>

                    <div v-for="(msg, index) in messages" :key="index" :class="['flex', msg.role === 'user' ? 'justify-end' : 'justify-start']">
                        <div :class="[
                            'max-w-[80%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed shadow-sm',
                            msg.role === 'user' 
                                ? 'bg-blue-600 text-white rounded-br-none' 
                                : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-bl-none border border-gray-200 dark:border-gray-600'
                        ]">
                            <p class="whitespace-pre-wrap">{{ msg.content }}</p>
                        </div>
                    </div>

                    <div v-if="isLoading" class="flex justify-start">
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-2xl rounded-bl-none px-4 py-3 flex gap-1.5">
                            <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></span>
                            <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce delay-75"></span>
                            <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce delay-150"></span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <form @submit.prevent="sendMessage" class="flex gap-2">
                        <input 
                            v-model="userInput" 
                            type="text" 
                            placeholder="Ketik pertanyaan..." 
                            class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-full focus:ring-blue-500 focus:border-blue-500 text-sm py-2.5 px-4 shadow-sm"
                            :disabled="isLoading"
                        >
                        <button 
                            type="submit" 
                            :disabled="isLoading || !userInput.trim()"
                            class="p-2.5 bg-blue-600 text-white rounded-full hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition shadow-sm flex-shrink-0"
                        >
                            <PaperAirplaneIcon class="w-5 h-5" />
                        </button>
                    </form>
                </div>
            </div>
        </transition>

        <button 
            @click="toggleChat"
            class="group flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg transition-all z-50"
        >
            <ChatBubbleLeftRightIcon v-if="!isOpen" class="w-7 h-7" />
            <XMarkIcon v-else class="w-7 h-7" />
        </button>
    </div>
</template>