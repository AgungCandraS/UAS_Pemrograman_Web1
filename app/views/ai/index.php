<div class="fade-in">
    <div class="row g-4">
        <div class="col-12 col-lg-8 mx-auto">
            <!-- AI Chat Interface -->
            <div class="table-card overflow-hidden">
                <!-- Header -->
                <div class="p-4" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: white; border-radius: 50%;">
                            <i class="fas fa-robot" style="color: var(--primary); font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h2 class="fw-bold mb-0" style="color: white; font-family: 'Poppins', sans-serif; font-size: 1.5rem;">AI Assistant</h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: 0.9rem;">Asisten bisnis pintar Anda</p>
                        </div>
                    </div>
                </div>
                
                <!-- Chat Messages -->
                <div id="chatMessages" class="p-4 overflow-y-auto" style="height: 500px; background: var(--surface-1);">
                    <?php if (empty($conversations)): ?>
                        <!-- Welcome Message -->
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center">
                                <i class="fas fa-comments mb-4" style="font-size: 4rem; color: var(--text-muted); opacity: 0.3;"></i>
                                <h3 class="fw-semibold mb-2" style="color: var(--text-secondary);">Selamat datang!</h3>
                                <p class="mb-0" style="color: var(--text-tertiary);">Tanyakan apapun tentang bisnis Anda</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_reverse($conversations) as $conv): ?>
                            <!-- User Message -->
                            <div class="d-flex justify-content-end mb-3">
                                <div class="rounded px-3 py-2" style="max-width: 70%; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white;">
                                    <?= nl2br(htmlspecialchars($conv['message'])) ?>
                                </div>
                            </div>
                            
                            <!-- AI Response -->
                            <div class="d-flex justify-content-start mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: var(--surface-2); border-radius: 50%;">
                                        <i class="fas fa-robot" style="color: var(--primary);"></i>
                                    </div>
                                    <div class="rounded px-3 py-2" style="max-width: 70%; background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                                        <?= nl2br(htmlspecialchars($conv['response'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
        
        <!-- Input Form -->
        <div class="border-t border-gray-200 p-4 bg-white">
            <form id="chatForm" class="flex space-x-3">
                <input 
                    type="text" 
                    id="messageInput" 
                    placeholder="Ketik pesan Anda..." 
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    required
                >
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition btn-animate"
                >
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <button onclick="sendQuickMessage('Bagaimana penjualan bulan ini?')" class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition">
            <i class="fas fa-chart-line text-purple-600 text-2xl mb-2"></i>
            <p class="text-sm font-medium text-gray-800">Analisis Penjualan</p>
        </button>
        
        <button onclick="sendQuickMessage('Tampilkan produk dengan stok rendah')" class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition">
            <i class="fas fa-boxes text-blue-600 text-2xl mb-2"></i>
            <p class="text-sm font-medium text-gray-800">Cek Inventory</p>
        </button>
        
        <button onclick="sendQuickMessage('Berikan saya laporan keuangan')" class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition">
            <i class="fas fa-wallet text-green-600 text-2xl mb-2"></i>
            <p class="text-sm font-medium text-gray-800">Laporan Keuangan</p>
        </button>
    </div>
</div>

<?php
$additionalScripts = '
<script>
    const chatForm = document.getElementById("chatForm");
    const messageInput = document.getElementById("messageInput");
    const chatMessages = document.getElementById("chatMessages");
    
    chatForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Add user message to UI
        addMessage(message, "user");
        messageInput.value = "";
        
        // Show loading
        const loadingDiv = addMessage("Mengetik...", "ai", true);
        
        try {
            const response = await fetch("' . base_url('ai-assistant/chat') . '", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "message=" + encodeURIComponent(message)
            });
            
            const data = await response.json();
            
            // Remove loading
            loadingDiv.remove();
            
            // Add AI response
            addMessage(data.response, "ai");
            
        } catch (error) {
            loadingDiv.remove();
            showToast("Terjadi kesalahan", "error");
        }
    });
    
    function addMessage(text, sender, isLoading = false) {
        const messageDiv = document.createElement("div");
        messageDiv.className = "flex mb-4 fade-in " + (sender === "user" ? "justify-end" : "justify-start");
        
        if (sender === "user") {
            messageDiv.innerHTML = `
                <div class="bg-purple-600 text-white rounded-lg px-4 py-2 max-w-md">
                    ${text}
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-robot text-purple-600"></i>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg px-4 py-2 max-w-md ${isLoading ? "italic text-gray-500" : ""}">
                        ${text}
                    </div>
                </div>
            `;
        }
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        return messageDiv;
    }
    
    function sendQuickMessage(message) {
        messageInput.value = message;
        chatForm.dispatchEvent(new Event("submit"));
    }
</script>
';
?>
