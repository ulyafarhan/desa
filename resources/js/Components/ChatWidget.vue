<script setup>
import { ref, nextTick, onMounted, watch } from 'vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from '@/Components/ui/card';
import axios from 'axios';

const isOpen = ref(false);
const isLoading = ref(false);
const message = ref('');
const chatContainer = ref(null);
const messages = ref([]);

// Load history saat komponen dimuat
onMounted(async () => {
    try {
        const response = await axios.get(route('chat.history'));
        messages.value = response.data;
        scrollToBottom();
    } catch (error) {
        console.error("Gagal memuat riwayat chat");
    }
});

const toggleChat = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) scrollToBottom();
};

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        }
    });
};

const sendMessage = async () => {
    if (!message.value.trim() || isLoading.value) return;

    const userMsg = message.value;
    message.value = ''; // Kosongkan input
    
    // Optimistic UI: Tampilkan pesan user duluan
    messages.value.push({ role: 'user', message: userMsg });
    scrollToBottom();
    isLoading.value = true;

    try {
        const response = await axios.post(route('chat.send'), { message: userMsg });
        // Tampilkan balasan AI
        messages.value.push({ role: 'model', message: response.data.reply });
    } catch (error) {
        messages.value.push({ role: 'model', message: 'Maaf, terjadi kesalahan koneksi.' });
    } finally {
        isLoading.value = false;
        scrollToBottom();
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
            <Card v-show="isOpen" class="w-80 sm:w-96 shadow-2xl border-slate-200 mb-4 h-[500px] flex flex-col">
                <CardHeader class="bg-slate-900 text-white py-3 px-4 rounded-t-lg flex flex-row justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <CardTitle class="text-sm font-medium">SiDesa Assistant</CardTitle>
                    </div>
                    <button @click="toggleChat" class="text-slate-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </CardHeader>
                
                <div ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
                    <div v-if="messages.length === 0" class="text-center text-xs text-slate-400 mt-10">
                        <p>Halo! Saya SiDesa.</p>
                        <p>Ada yang bisa saya bantu?</p>
                    </div>

                    <div v-for="(msg, index) in messages" :key="index" :class="{'flex justify-end': msg.role === 'user', 'flex justify-start': msg.role === 'model'}">
                        <div 
                            :class="[
                                'max-w-[80%] rounded-lg px-3 py-2 text-sm shadow-sm',
                                msg.role === 'user' ? 'bg-slate-900 text-white rounded-tr-none' : 'bg-white border border-slate-200 text-slate-800 rounded-tl-none'
                            ]"
                        >
                            {{ msg.message }}
                        </div>
                    </div>
                    
                    <div v-if="isLoading" class="flex justify-start">
                        <div class="bg-white border border-slate-200 px-3 py-2 rounded-lg rounded-tl-none shadow-sm">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce"></div>
                                <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce delay-75"></div>
                                <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce delay-150"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <CardFooter class="p-3 border-t border-slate-100 bg-white">
                    <form @submit.prevent="sendMessage" class="flex w-full space-x-2">
                        <Input v-model="message" placeholder="Ketik pertanyaan..." class="flex-1 h-9 text-sm" :disabled="isLoading" />
                        <Button type="submit" size="sm" :disabled="isLoading || !message.trim()" class="h-9 w-9 p-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </Button>
                    </form>
                </CardFooter>
            </Card>
        </transition>

        <button 
            @click="toggleChat"
            class="group flex items-center justify-center w-14 h-14 bg-slate-900 hover:bg-slate-800 text-white rounded-full shadow-xl transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-4 focus:ring-slate-300"
        >
            <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>
</template>