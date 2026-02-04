<div class="fade-in">
    <!-- Hero Section with Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="table-card p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex align-items-center justify-content-center me-3" 
                                 style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; backdrop-filter: blur(10px);">
                                <i class="fas fa-robot" style="color: white; font-size: 2rem;"></i>
                            </div>
                            <div>
                                <h1 class="fw-bold mb-1" style="color: white; font-size: 2rem;">AI Business Assistant</h1>
                                <p class="mb-0" style="color: rgba(255,255,255,0.9);">Asisten pintar untuk analisis dan insight bisnis Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2 justify-content-md-end">
                            <button onclick="clearChat()" class="btn btn-light btn-sm">
                                <i class="fas fa-trash me-1"></i> Clear Chat
                            </button>
                            <button onclick="exportChat()" class="btn btn-light btn-sm">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="row g-3 mt-2">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-line me-2" style="color: #4ade80; font-size: 1.5rem;"></i>
                                <div>
                                    <div class="small" style="color: rgba(255,255,255,0.8);">Sales Today</div>
                                    <div class="fw-bold" style="color: white;">Rp <?= number_format($stats['sales_today'], 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-box me-2" style="color: #fb923c; font-size: 1.5rem;"></i>
                                <div>
                                    <div class="small" style="color: rgba(255,255,255,0.8);">Low Stock</div>
                                    <div class="fw-bold" style="color: white;"><?= $stats['low_stock'] ?> items</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-2" style="color: #60a5fa; font-size: 1.5rem;"></i>
                                <div>
                                    <div class="small" style="color: rgba(255,255,255,0.8);">Employees</div>
                                    <div class="fw-bold" style="color: white;"><?= $stats['employees'] ?> active</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shopping-cart me-2" style="color: #a78bfa; font-size: 1.5rem;"></i>
                                <div>
                                    <div class="small" style="color: rgba(255,255,255,0.8);">Orders Today</div>
                                    <div class="fw-bold" style="color: white;"><?= $stats['pending_orders'] ?> orders</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chat Interface -->
        <div class="col-12 col-lg-8">
            <div class="table-card" style="height: 700px; display: flex; flex-direction: column;">
                <!-- Chat Header -->
                <div class="p-3 border-bottom" style="background: var(--surface-1);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="position-relative">
                                <div class="d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%;">
                                    <i class="fas fa-robot" style="color: white;"></i>
                                </div>
                                <span class="position-absolute bottom-0 end-0" 
                                      style="width: 12px; height: 12px; background: #4ade80; border: 2px solid white; border-radius: 50%;"></span>
                            </div>
                            <div>
                                <div class="fw-semibold" style="color: var(--text-primary);">AI Assistant</div>
                                <div class="small" style="color: var(--text-tertiary);">
                                    <i class="fas fa-circle" style="font-size: 6px; color: #4ade80;"></i> Online
                                </div>
                            </div>
                        </div>
                        <div class="small" style="color: var(--text-tertiary);">
                            <i class="fas fa-info-circle me-1"></i> AI-Powered
                        </div>
                    </div>
                </div>
                
                <!-- Chat Messages -->
                <div id="chatMessages" class="flex-grow-1 p-4 overflow-auto" style="background: var(--surface-1); max-height: 520px;">
                    <?php if (empty($conversations)): ?>
                        <!-- Welcome Screen -->
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center" style="max-width: 500px;">
                                <div class="mb-4" style="animation: float 3s ease-in-out infinite;">
                                    <i class="fas fa-robot" style="font-size: 5rem; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                </div>
                                <h3 class="fw-bold mb-3" style="color: var(--text-primary);">Selamat Datang! 👋</h3>
                                <p style="color: var(--text-secondary); margin-bottom: 2rem;">Saya adalah AI Business Assistant Anda. Tanyakan apapun tentang:</p>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background: var(--surface-2); border: 1px solid var(--border-color);">
                                            <i class="fas fa-chart-line text-success mb-1"></i>
                                            <div class="small fw-medium">Penjualan</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background: var(--surface-2); border: 1px solid var(--border-color);">
                                            <i class="fas fa-boxes text-warning mb-1"></i>
                                            <div class="small fw-medium">Inventory</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background: var(--surface-2); border: 1px solid var(--border-color);">
                                            <i class="fas fa-wallet text-primary mb-1"></i>
                                            <div class="small fw-medium">Keuangan</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background: var(--surface-2); border: 1px solid var(--border-color);">
                                            <i class="fas fa-users text-info mb-1"></i>
                                            <div class="small fw-medium">HR</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_reverse($conversations) as $conv): ?>
                            <!-- User Message -->
                            <div class="d-flex justify-content-end mb-3 fade-in">
                                <div class="message-bubble user-message" style="max-width: 70%;">
                                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);">
                                        <?= nl2br(htmlspecialchars($conv['message'])) ?>
                                        <div class="text-end mt-1">
                                            <small style="opacity: 0.8; font-size: 0.75rem;">
                                                <?= date('H:i', strtotime($conv['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- AI Response -->
                            <div class="d-flex justify-content-start mb-3 fade-in">
                                <div class="d-flex align-items-start" style="max-width: 75%;">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="d-flex align-items-center justify-content-center" 
                                             style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);">
                                            <i class="fas fa-robot" style="color: white; font-size: 0.9rem;"></i>
                                        </div>
                                    </div>
                                    <div class="message-bubble ai-message">
                                        <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                            <div style="color: #1e293b; line-height: 1.6;">
                                                <?= $conv['response'] ?>
                                            </div>
                                            <div class="mt-2">
                                                <small style="color: #64748b; font-size: 0.75rem;">
                                                    <i class="fas fa-clock me-1"></i><?= date('H:i', strtotime($conv['created_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
        
                <!-- Input Form -->
                <div class="p-3 border-top" style="background: var(--surface-2);">
                    <form id="chatForm" class="d-flex gap-2">
                        <input 
                            type="text" 
                            id="messageInput" 
                            placeholder="Ketik pesan Anda..." 
                            class="form-control form-control-lg"
                            style="border-radius: 12px; border: 1px solid var(--border-color); background: white;"
                            required
                            autocomplete="off"
                        >
                        <button 
                            type="submit" 
                            class="btn btn-lg px-4"
                            style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 12px; min-width: 100px;"
                        >
                            <i class="fas fa-paper-plane me-1"></i> Send
                        </button>
                    </form>
                    
                    <!-- Typing Indicator -->
                    <div id="typingIndicator" class="mt-2 small" style="color: var(--text-tertiary); display: none;">
                        <i class="fas fa-circle-notch fa-spin me-1"></i> AI sedang mengetik...
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Insights & Quick Actions Sidebar -->
        <div class="col-12 col-lg-4">
            <!-- Business Insights -->
            <div class="table-card mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary);">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Business Insights
                    </h5>
                </div>
                <div class="p-3">
                    <?php if (!empty($insights)): ?>
                        <?php foreach ($insights as $insight): ?>
                            <div class="mb-3 p-3 rounded-3 insight-card" 
                                 style="border-left: 4px solid <?= $insight['type'] == 'success' ? '#4ade80' : ($insight['type'] == 'warning' ? '#fb923c' : ($insight['type'] == 'danger' ? '#ef4444' : '#60a5fa')) ?>; background: var(--surface-1);">
                                <div class="d-flex align-items-start mb-2">
                                    <i class="fas <?= $insight['icon'] ?> me-2 mt-1" 
                                       style="color: <?= $insight['type'] == 'success' ? '#4ade80' : ($insight['type'] == 'warning' ? '#fb923c' : ($insight['type'] == 'danger' ? '#ef4444' : '#60a5fa')) ?>; font-size: 1.2rem;"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold mb-1" style="color: var(--text-primary);">
                                            <?= htmlspecialchars($insight['title']) ?>
                                        </div>
                                        <div class="small mb-2" style="color: var(--text-secondary);">
                                            <?= htmlspecialchars($insight['description']) ?>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge" style="background: <?= $insight['type'] == 'success' ? '#4ade80' : ($insight['type'] == 'warning' ? '#fb923c' : ($insight['type'] == 'danger' ? '#ef4444' : '#60a5fa')) ?>; color: white; font-size: 0.75rem;">
                                                <?= htmlspecialchars($insight['value']) ?>
                                            </span>
                                            <small style="color: var(--text-tertiary);">
                                                <i class="fas fa-arrow-right me-1"></i><?= htmlspecialchars($insight['action']) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4" style="color: var(--text-tertiary);">
                            <i class="fas fa-chart-line mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="mb-0 small">Tidak ada insight saat ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="table-card">
                <div class="p-3 border-bottom">
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary);">
                        <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="p-3">
                    <div class="d-grid gap-2">
                        <button onclick="sendQuickMessage('Bagaimana penjualan bulan ini?')" 
                                class="btn btn-outline-primary text-start d-flex align-items-center quick-action-btn">
                            <i class="fas fa-chart-line me-2" style="width: 20px;"></i>
                            <span>Analisis Penjualan</span>
                        </button>
                        
                        <button onclick="sendQuickMessage('Tampilkan produk dengan stok rendah')" 
                                class="btn btn-outline-warning text-start d-flex align-items-center quick-action-btn">
                            <i class="fas fa-boxes me-2" style="width: 20px;"></i>
                            <span>Cek Inventory</span>
                        </button>
                        
                        <button onclick="sendQuickMessage('Berikan saya laporan keuangan')" 
                                class="btn btn-outline-success text-start d-flex align-items-center quick-action-btn">
                            <i class="fas fa-wallet me-2" style="width: 20px;"></i>
                            <span>Laporan Keuangan</span>
                        </button>
                        
                        <button onclick="sendQuickMessage('Bagaimana performa karyawan?')" 
                                class="btn btn-outline-info text-start d-flex align-items-center quick-action-btn">
                            <i class="fas fa-users me-2" style="width: 20px;"></i>
                            <span>Status HR</span>
                        </button>
                        
                        <button onclick="sendQuickMessage('Berikan rekomendasi untuk bisnis saya')" 
                                class="btn btn-outline-secondary text-start d-flex align-items-center quick-action-btn">
                            <i class="fas fa-magic me-2" style="width: 20px;"></i>
                            <span>Rekomendasi AI</span>
                        </button>
                        
                        <button onclick="sendQuickMessage('Analisis performa keseluruhan')" 
                                class="btn btn-outline-dark text-start d-flex align-items-center quick-action-btn">
                            <i class="fas fa-tachometer-alt me-2" style="width: 20px;"></i>
                            <span>Performance Overview</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.message-bubble {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.quick-action-btn {
    transition: all 0.3s ease;
    border-radius: 8px;
}

.quick-action-btn:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.insight-card {
    transition: all 0.3s ease;
}

.insight-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

#chatMessages {
    scroll-behavior: smooth;
}

#chatMessages::-webkit-scrollbar {
    width: 6px;
}

#chatMessages::-webkit-scrollbar-track {
    background: var(--surface-1);
}

#chatMessages::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

#chatMessages::-webkit-scrollbar-thumb:hover {
    background: var(--text-tertiary);
}
</style>

<?php
$additionalScripts = '
<script>
    const chatForm = document.getElementById("chatForm");
    const messageInput = document.getElementById("messageInput");
    const chatMessages = document.getElementById("chatMessages");
    const typingIndicator = document.getElementById("typingIndicator");
    
    // Handle chat message submission
    async function handleChatSubmit(message) {
        if (!message || !message.trim()) return;
        
        const trimmedMessage = message.trim();
        
        // Add user message to UI
        addMessage(trimmedMessage, "user");
        messageInput.value = "";
        
        // Show typing indicator
        typingIndicator.style.display = "block";
        
        try {
            const response = await fetch("' . base_url('ai-assistant/chat') . '", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "message=" + encodeURIComponent(trimmedMessage)
            });
            
            const data = await response.json();
            
            // Hide typing indicator
            typingIndicator.style.display = "none";
            
            if (data.success) {
                // Add AI response with delay for natural feel
                setTimeout(() => {
                    addMessage(data.response, "ai");
                    
                    // Show suggestions if available
                    if (data.suggestions && data.suggestions.length > 0) {
                        addSuggestions(data.suggestions);
                    }
                }, 500);
            } else {
                showToast(data.message || "Terjadi kesalahan", "error");
            }
            
        } catch (error) {
            typingIndicator.style.display = "none";
            console.error("Error:", error);
            showToast("Terjadi kesalahan koneksi", "error");
        }
    }
    
    chatForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        await handleChatSubmit(messageInput.value);
    });
    
    function addMessage(text, sender) {
        const now = new Date();
        const time = now.getHours().toString().padStart(2, "0") + ":" + 
                     now.getMinutes().toString().padStart(2, "0");
        
        const messageDiv = document.createElement("div");
        messageDiv.className = "fade-in mb-3 " + (sender === "user" ? "d-flex justify-content-end" : "d-flex justify-content-start");
        
        if (sender === "user") {
            messageDiv.innerHTML = `
                <div class="message-bubble user-message" style="max-width: 70%;">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);">
                        ${text}
                        <div class="text-end mt-1">
                            <small style="opacity: 0.8; font-size: 0.75rem;">${time}</small>
                        </div>
                    </div>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="d-flex align-items-start" style="max-width: 75%;">
                    <div class="flex-shrink-0 me-2">
                        <div class="d-flex align-items-center justify-content-center" 
                             style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);">
                            <i class="fas fa-robot" style="color: white; font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="message-bubble ai-message">
                        <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <div style="color: #1e293b; line-height: 1.6;">
                                ${text}
                            </div>
                            <div class="mt-2">
                                <small style="color: #64748b; font-size: 0.75rem;">
                                    <i class="fas fa-clock me-1"></i>${time}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function addSuggestions(suggestions) {
        const suggestionsDiv = document.createElement("div");
        suggestionsDiv.className = "fade-in mb-3 d-flex justify-content-start";
        
        let suggestionsHTML = `
            <div class="d-flex align-items-start" style="max-width: 75%;">
                <div class="flex-shrink-0 me-2" style="width: 36px;"></div>
                <div class="d-flex flex-wrap gap-2">
        `;
        
        suggestions.forEach(suggestion => {
            suggestionsHTML += `
                <button onclick="sendQuickMessage(\'${suggestion.replace(/\'/g, "\\\'")}\')" 
                        class="btn btn-sm btn-outline-secondary" 
                        style="border-radius: 20px; font-size: 0.85rem;">
                    ${suggestion}
                </button>
            `;
        });
        
        suggestionsHTML += `
                </div>
            </div>
        `;
        
        suggestionsDiv.innerHTML = suggestionsHTML;
        chatMessages.appendChild(suggestionsDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function sendQuickMessage(message) {
        messageInput.value = message;
        messageInput.focus();
        
        // Call the same handler function used by form submit
        handleChatSubmit(message);
    }
    
    async function clearChat() {
        if (!confirm("Apakah Anda yakin ingin menghapus semua riwayat chat?")) {
            return;
        }
        
        try {
            const response = await fetch("' . base_url('ai-assistant/clear-history') . '", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Clear chat messages
                chatMessages.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-center" style="max-width: 500px;">
                            <div class="mb-4" style="animation: float 3s ease-in-out infinite;">
                                <i class="fas fa-robot" style="font-size: 5rem; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            </div>
                            <h3 class="fw-bold mb-3" style="color: var(--text-primary);">Selamat Datang! 👋</h3>
                            <p style="color: var(--text-secondary); margin-bottom: 2rem;">Saya adalah AI Business Assistant Anda. Tanyakan apapun tentang bisnis Anda!</p>
                        </div>
                    </div>
                `;
                showToast("Riwayat chat berhasil dihapus", "success");
            } else {
                showToast(data.message || "Gagal menghapus riwayat", "error");
            }
        } catch (error) {
            console.error("Error:", error);
            showToast("Terjadi kesalahan", "error");
        }
    }
    
    function exportChat() {
        // Get all messages
        const messages = [];
        const messageBubbles = document.querySelectorAll(".message-bubble");
        
        messageBubbles.forEach(bubble => {
            const text = bubble.textContent.trim();
            const isUser = bubble.classList.contains("user-message");
            messages.push({
                sender: isUser ? "User" : "AI Assistant",
                message: text
            });
        });
        
        if (messages.length === 0) {
            showToast("Tidak ada pesan untuk diekspor", "warning");
            return;
        }
        
        // Create export content
        let exportContent = "AI Assistant Chat History\\n";
        exportContent += "Exported: " + new Date().toLocaleString() + "\\n";
        exportContent += "=".repeat(50) + "\\n\\n";
        
        messages.forEach(msg => {
            exportContent += msg.sender + ":\\n";
            exportContent += msg.message + "\\n\\n";
        });
        
        // Download as text file
        const blob = new Blob([exportContent], { type: "text/plain" });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "ai-chat-history-" + Date.now() + ".txt";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        showToast("Chat history berhasil diekspor", "success");
    }
    
    // Auto-focus on input
    messageInput.focus();
    
    // Scroll to bottom on page load
    chatMessages.scrollTop = chatMessages.scrollHeight;
</script>
';
?>
