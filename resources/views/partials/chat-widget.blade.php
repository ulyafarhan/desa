<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="chatWidget()" x-init="loadHistory()" x-cloak class="fixed bottom-5 right-5 z-50 font-sans">
    
    <button @click="toggleChat" x-show="!isOpen" 
        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all transform hover:scale-110 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
    </button>

    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="bg-white w-80 md:w-96 rounded-2xl shadow-2xl border border-gray-200 flex flex-col h-[500px] overflow-hidden">
        
        <div class="bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold">
                        S
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 border-2 border-blue-600 rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-bold text-base leading-tight">SiDesa Assistant</h3>
                    <p class="text-xs text-blue-100">Online & Siap Membantu</p>
                </div>
            </div>
            <button @click="toggleChat" class="text-white/80 hover:text-white hover:bg-blue-700 p-1 rounded-full transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="chat-box" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50 scroll-smooth">
            <div class="flex justify-start">
                <div class="bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-tl-none py-3 px-4 max-w-[85%] shadow-sm text-sm">
                    Halo! 👋 Ada yang bisa saya bantu terkait layanan desa hari ini?
                </div>
            </div>

            <template x-for="(chat, index) in messages" :key="index">
                <div :class="chat.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="chat.role === 'user' ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white border border-gray-200 text-gray-800 rounded-tl-none'"
                         class="rounded-2xl py-2 px-4 max-w-[85%] shadow-sm text-sm whitespace-pre-line leading-relaxed">
                        <span x-text="chat.message"></span>
                    </div>
                </div>
            </template>

            <div x-show="isTyping" class="flex justify-start">
                <div class="bg-gray-200 rounded-2xl rounded-tl-none py-2 px-4 flex items-center gap-1 w-16 h-9">
                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce delay-100"></span>
                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce delay-200"></span>
                </div>
            </div>
        </div>

        <div class="p-3 border-t bg-white">
            <form @submit.prevent="sendMessage" class="relative flex items-center gap-2">
                <input type="text" x-model="userInput" placeholder="Ketik pertanyaan Anda..." 
                    class="w-full bg-gray-100 text-gray-800 border-0 rounded-full px-4 py-3 pr-12 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white transition shadow-inner"
                    :disabled="isTyping">
                
                <button type="submit" 
                    class="absolute right-2 bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition shadow-md"
                    :disabled="isTyping || !userInput.trim()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
            <div class="text-center mt-2">
                 <span class="text-[10px] text-gray-400">Didukung oleh AI Desa Digital</span>
            </div>
        </div>
    </div>
</div>

<script>
    function chatWidget() {
        return {
            isOpen: false,
            messages: [],
            userInput: '',
            isTyping: false,

            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen && this.messages.length === 0) {
                    this.loadHistory();
                }
                this.$nextTick(() => this.scrollToBottom());
            },

            scrollToBottom() {
                const box = document.getElementById('chat-box');
                if(box) box.scrollTop = box.scrollHeight;
            },

            loadHistory() {
                axios.get('{{ route("chat.history") }}')
                    .then(res => {
                        this.messages = res.data;
                        this.$nextTick(() => this.scrollToBottom());
                    })
                    .catch(err => console.error(err));
            },

            sendMessage() {
                if (!this.userInput.trim()) return;

                const message = this.userInput;
                this.userInput = ''; 

                // Kita push tanpa ID, makanya HTML harus pakai :key="index"
                this.messages.push({ role: 'user', message: message });
                this.$nextTick(() => this.scrollToBottom());

                this.isTyping = true;

                axios.post('{{ route("chat.send") }}', { message: message })
                    .then(res => {
                        this.messages.push({ role: 'model', message: res.data.reply });
                    })
                    .catch(err => {
                        this.messages.push({ role: 'model', message: 'Maaf, gagal terhubung ke server.' });
                    })
                    .finally(() => {
                        this.isTyping = false;
                        this.$nextTick(() => this.scrollToBottom());
                    });
            }
        }
    }
</script>