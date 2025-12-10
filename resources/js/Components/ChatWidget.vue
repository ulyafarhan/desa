<script setup>
import { ref, nextTick, onUnmounted } from 'vue';
import axios from 'axios';

const isOpen = ref(false);
const message = ref('');
const messages = ref([]);
const chatContainer = ref(null);
const isSending = ref(false);
let pollingInterval = null;

const toggleChat = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        fetchMessages();
        startPolling();
        scrollToBottom();
    } else {
        stopPolling();
    }
};

const startPolling = () => {
    if (!pollingInterval) {
        pollingInterval = setInterval(() => {
            if (isOpen.value && !isSending.value) {
                fetchMessages();
            }
        }, 5000);
    }
};

const stopPolling = () => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
};

const fetchMessages = async () => {
    try {
        const response = await axios.get(route('chat.history'));
        messages.value = response.data.map(msg => ({
            ...msg,
            is_admin_reply: msg.role === 'model'
        }));
        
        // Auto scroll jika user berada di bawah
        if (chatContainer.value && chatContainer.value.scrollTop + chatContainer.value.clientHeight >= chatContainer.value.scrollHeight - 100) {
            scrollToBottom();
        }
    } catch (error) {
        console.error("Error fetching chat:", error);
    }
};

const sendMessage = async () => {
    if (!message.value.trim()) return;

    const userMsg = message.value;
    
    // Optimistic UI
    messages.value.push({
        message: userMsg,
        is_admin_reply: false,
        role: 'user',
        created_at: new Date().toISOString()
    });
    
    message.value = '';
    isSending.value = true;
    scrollToBottom();

    try {
        const response = await axios.post(route('chat.send'), {
            message: userMsg
        });

        if (response.data.reply) {
            messages.value.push({
                message: response.data.reply,
                is_admin_reply: true,
                role: 'model',
                created_at: new Date().toISOString()
            });
            scrollToBottom();
        }
    } catch (error) {
        console.error("Error sending message:", error);
        messages.value.push({
            message: "Gagal mengirim pesan. Coba lagi nanti.",
            is_admin_reply: true,
            role: 'model',
            is_error: true
        });
    } finally {
        isSending.value = false;
    }
};

const scrollToBottom = async () => {
    await nextTick();
    if (chatContainer.value) {
        chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
};

// Bersihkan interval saat komponen dihancurkan
onUnmounted(() => {
    stopPolling();
});
</script>

<template>
    <div class="fixed bottom-5 right-5 z-50 font-sans">
        <button 
            @click="toggleChat"
            class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 flex items-center justify-center transform hover:scale-105"
        >
            <span v-if="!isOpen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </span>
            <span v-else>
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </span>
        </button>

        <div v-show="isOpen" class="absolute bottom-20 right-0 w-80 md:w-96 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col h-[500px] transition-all duration-300">
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white shadow-md flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">SiDesa Assistant</h3>
                    <p class="text-xs text-blue-100">Siap membantu 24/7</p>
                </div>
                <span class="text-[10px] uppercase tracking-wider bg-green-400 text-white px-2 py-0.5 rounded-full font-bold shadow-sm">Online</span>
            </div>

            <div ref="chatContainer" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4">
                <div v-if="messages.length === 0" class="flex flex-col items-center justify-center h-full text-gray-400 space-y-2">
                    <p class="text-sm">Halo! Ada yang bisa saya bantu?</p>
                </div>

                <div 
                    v-for="(msg, index) in messages" 
                    :key="index" 
                    class="flex flex-col"
                    :class="msg.is_admin_reply ? 'items-start' : 'items-end'"
                >
                    <div 
                        class="max-w-[85%] rounded-2xl px-4 py-2 text-sm shadow-sm leading-relaxed"
                        :class="[
                            msg.is_admin_reply 
                                ? 'bg-white text-gray-800 border border-gray-200 rounded-tl-none' 
                                : 'bg-blue-600 text-white rounded-tr-none',
                            msg.is_error ? 'bg-red-50 text-red-600 border-red-200' : ''
                        ]"
                    >
                        <span v-html="msg.message.replace(/\n/g, '<br>')"></span>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 px-1">
                        {{ msg.is_admin_reply ? 'SiDesa' : 'Anda' }}
                    </span>
                </div>
                
                <div v-if="isSending" class="flex items-start">
                    <div class="bg-gray-200 rounded-full px-4 py-2 rounded-tl-none flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-75"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-150"></div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-white border-t border-gray-100">
                <form @submit.prevent="sendMessage" class="flex gap-2 items-center">
                    <input 
                        v-model="message" 
                        type="text" 
                        placeholder="Ketik pesan..." 
                        class="flex-1 bg-gray-50 border border-gray-200 rounded-full px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-200"
                        :disabled="isSending"
                    />
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white p-2.5 rounded-full hover:bg-blue-700 disabled:opacity-50 transition-colors shadow-md"
                        :disabled="!message || isSending"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>