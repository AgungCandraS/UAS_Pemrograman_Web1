<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Bisnisku' ?> - Bisnisku Web App</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Smooth Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-bg-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .gradient-bg-3 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .gradient-bg-4 {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Loading Spinner */
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Card Hover Effect */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* Button Animations */
        .btn-animate {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-animate::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-animate:hover::before {
            width: 300px;
            height: 300px;
        }
    </style>
    
    <?= $additionalHead ?? '' ?>
</head>
<body class="bg-gray-50">
    <?= $content ?>
    
    <!-- Footer -->
    <footer class="text-center py-4 text-sm text-gray-500 border-t border-gray-200 mt-8">
        @Copyright by 23552011272_Agung Candra Saputra_TIF-RP223 CNS B_UAS-WEB1
    </footer>
    
    <!-- Toast Notification -->
    <div id="toast" class="fixed top-5 right-5 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-4 flex items-center space-x-3 fade-in">
            <div id="toast-icon" class="flex-shrink-0"></div>
            <div id="toast-message" class="text-sm font-medium text-gray-800"></div>
            <button onclick="closeToast()" class="ml-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <!-- Custom Scripts -->
    <script>
        // Toast Notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toast-icon');
            const msg = document.getElementById('toast-message');
            
            const icons = {
                success: '<i class="fas fa-check-circle text-green-500 text-xl"></i>',
                error: '<i class="fas fa-times-circle text-red-500 text-xl"></i>',
                warning: '<i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>',
                info: '<i class="fas fa-info-circle text-blue-500 text-xl"></i>'
            };
            
            icon.innerHTML = icons[type] || icons.info;
            msg.textContent = message;
            toast.classList.remove('hidden');
            
            setTimeout(() => {
                closeToast();
            }, 5000);
        }
        
        function closeToast() {
            document.getElementById('toast').classList.add('hidden');
        }
        
        // Show flash messages
        <?php if ($msg = flash('success')): ?>
            showToast('<?= $msg ?>', 'success');
        <?php endif; ?>
        
        <?php if ($msg = flash('error')): ?>
            showToast('<?= $msg ?>', 'error');
        <?php endif; ?>
        
        <?php if ($msg = flash('warning')): ?>
            showToast('<?= $msg ?>', 'warning');
        <?php endif; ?>
        
        <?php if ($msg = flash('info')): ?>
            showToast('<?= $msg ?>', 'info');
        <?php endif; ?>
        
        // Loading Overlay
        function showLoading() {
            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            overlay.innerHTML = '<div class="spinner"></div>';
            document.body.appendChild(overlay);
        }
        
        function hideLoading() {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        }
        
        // Confirm Dialog
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
        
        // Format Currency
        function formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
        
        // Format Date
        function formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
    </script>
    
    <?= $additionalScripts ?? '' ?>
</body>
</html>
