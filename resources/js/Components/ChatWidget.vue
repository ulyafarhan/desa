<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';

const isOpen = ref(false);
const message = ref('');
const messages = ref([]);
const chatContainer = ref(null);
const isSending = ref(false);

const toggleChat = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        fetchMessages();
        scrollToBottom();
    }
};

const fetchMessages = async () => {
    try {
        const response = await axios.get(route('chat.history'));
        messages.value = response.data;
        scrollToBottom();
    } catch (error) {
        console.error("Gagal memuat chat:", error);
    }
};

const sendMessage = async () => {
    if (!message.value.trim()) return;

    const tempMessage = {
        message: message.value,
        is_admin_reply: false, // Pesan user sendiri
        created_at: new Date().toISOString() // Placeholder waktu
    };
    
    // Optimistic UI update (tambah pesan langsung ke layar sebelum request selesai)
    messages.value.push(tempMessage);
    const msgToSend = message.value;
    message.value = '';
    scrollToBottom();
    isSending.value = true;

    try {
        await axios.post(route('chat.send'), {
            message: msgToSend
        });
        // Refresh untuk memastikan sinkronisasi data (opsional, bisa di-skip jika yakin)
        // await fetchMessages(); 
    } catch (error) {
        console.error("Gagal mengirim pesan:", error);
        // Hapus pesan jika gagal (opsional)
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

// Polling pesan baru setiap 5 detik jika chat terbuka
setInterval(() => {
    if (isOpen.value) {
        fetchMessages();
    }
}, 5000);
</script>

<template>
    <div class="fixed bottom-5 right-5 z-50">
        <button 
            @click="toggleChat"
            class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 flex items-center justify-center"
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

        <div v-if="isOpen" class="absolute bottom-20 right-0 w-80 md:w-96 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden flex flex-col h-[500px]">
            <div class="bg-blue-600 p-4 text-white font-bold flex justify-between items-center">
                <span>Layanan Warga</span>
                <span class="text-xs bg-blue-500 px-2 py-1 rounded">Online</span>
            </div>

            <div ref="chatContainer" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4">
                <div v-if="messages.length === 0" class="text-center text-gray-400 mt-10 text-sm">
                    <p>Halo! Ada yang bisa kami bantu?</p>
                    <p>Silakan tinggalkan pesan.</p>
                </div>

                <div 
                    v-for="(msg, index) in messages" 
                    :key="index" 
                    class="flex flex-col"
                    :class="msg.is_admin_reply ? 'items-start' : 'items-end'"
                >
                    <div 
                        class="max-w-[80%] rounded-lg p-3 text-sm shadow-sm"
                        :class="msg.is_admin_reply 
                            ? 'bg-white text-gray-800 border border-gray-200 rounded-tl-none' 
                            : 'bg-blue-600 text-white rounded-tr-none'"
                    >
                        {{ msg.message }}
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1">
                        {{ msg.is_admin_reply ? 'Admin' : 'Anda' }}
                    </span>
                </div>
            </div>

            <div class="p-3 bg-white border-t border-gray-200">
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <input 
                        v-model="message" 
                        type="text" 
                        placeholder="Tulis pesan..." 
                        class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    />
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 disabled:opacity-50"
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