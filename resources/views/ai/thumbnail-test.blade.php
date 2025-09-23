<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Thumbnail Generator - Fixed Version</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-left: 4px solid #667eea;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input, select, button {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .loading { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }
        .emoji {
            font-size: 1.5em;
            margin-right: 10px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left-color: #17a2b8;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🤖 AI Thumbnail Generator</h1>
        <p>Tạo thumbnail cho blog và sản phẩm source game bằng AI</p>
    </div>

    <!-- Success Alert -->
    <div class="alert alert-success">
        <strong>✅ Fixed Version!</strong> Sử dụng CSRF token để tránh lỗi authentication.
        <br><strong>🔧 API Status:</strong> Sử dụng proper Laravel CSRF protection.
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stat-card">
            <span class="stat-number" id="blogTotal">-</span>
            <div>Total Blogs</div>
        </div>
        <div class="stat-card">
            <span class="stat-number" id="blogWithThumbnails">-</span>
            <div>Blogs có thumbnail</div>
        </div>
        <div class="stat-card">
            <span class="stat-number" id="productTotal">-</span>
            <div>Total Products</div>
        </div>
        <div class="stat-card">
            <span class="stat-number" id="productWithImages">-</span>
            <div>Products có hình ảnh</div>
        </div>
    </div>

    <div class="grid">
        <!-- Blog Thumbnail Generator -->
        <div class="card">
            <h2><span class="emoji">📝</span>Generate Blog Thumbnail</h2>
            <form id="blogForm">
                <div class="form-group">
                    <label for="blogId">Blog ID:</label>
                    <input type="number" id="blogId" name="blog_id" min="1" max="77" placeholder="Nhập ID của blog (1-77)" required>
                </div>
                <div class="form-group">
                    <label for="blogQuality">Quality Level:</label>
                    <select id="blogQuality" name="quality_level">
                        <option value="low">Low (Nhanh, file nhỏ)</option>
                        <option value="medium" selected>Medium (Cân bằng)</option>
                        <option value="high">High (Chất lượng cao)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="blogForce" name="force" style="width: auto; margin-right: 8px;">
                        Force regenerate (tạo lại nếu đã có thumbnail)
                    </label>
                </div>
                <button type="submit" id="blogBtn">🎨 Generate Blog Thumbnail</button>
            </form>
            <div id="blogResult"></div>
        </div>

        <!-- Product Thumbnail Generator -->
        <div class="card">
            <h2><span class="emoji">🎮</span>Generate Product Thumbnail</h2>
            <form id="productForm">
                <div class="form-group">
                    <label for="productId">Product ID:</label>
                    <input type="number" id="productId" name="product_id" min="16" max="20" placeholder="Nhập ID của product (16-20)" required>
                </div>
                <div class="form-group">
                    <label for="productQuality">Quality Level:</label>
                    <select id="productQuality" name="quality_level">
                        <option value="low">Low (Nhanh, file nhỏ)</option>
                        <option value="medium">Medium (Cân bằng)</option>
                        <option value="high" selected>High (Chất lượng cao)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="productForce" name="force" style="width: auto; margin-right: 8px;">
                        Force regenerate (tạo lại nếu đã có hình ảnh)
                    </label>
                </div>
                <button type="submit" id="productBtn">🖼️ Generate Product Thumbnail</button>
            </form>
            <div id="productResult"></div>
        </div>
    </div>

    <script>
        // Get CSRF token from Laravel
        function getCSRFToken() {
            const token = document.querySelector('meta[name="csrf-token"]');
            return token ? token.getAttribute('content') : '';
        }

        // Load statistics on page load
        loadStatistics();

        // Form handlers
        document.getElementById('blogForm').addEventListener('submit', generateBlogThumbnail);
        document.getElementById('productForm').addEventListener('submit', generateProductThumbnail);

        async function loadStatistics() {
            try {
                // Use the original thumbnail controller for statistics (GET request, no CSRF needed)
                const response = await fetch('/ai/thumbnails/statistics');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('blogTotal').textContent = data.statistics.blogs.total;
                    document.getElementById('blogWithThumbnails').textContent = data.statistics.blogs.with_thumbnails;
                    document.getElementById('productTotal').textContent = data.statistics.products.total;
                    document.getElementById('productWithImages').textContent = data.statistics.products.with_images;
                }
            } catch (error) {
                console.error('Failed to load statistics:', error);
            }
        }

        async function generateBlogThumbnail(event) {
            event.preventDefault();
            
            const btn = document.getElementById('blogBtn');
            const result = document.getElementById('blogResult');
            const formData = new FormData(event.target);
            
            btn.disabled = true;
            btn.textContent = '⏳ Đang tạo thumbnail...';
            result.innerHTML = '<div class="loading">🤖 AI đang tạo thumbnail cho blog, vui lòng đợi... (có thể mất 10-30 giây)</div>';

            try {
                // Use the original thumbnail controller with CSRF token
                const response = await fetch('/ai/thumbnails/blog', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCSRFToken()
                    },
                    body: JSON.stringify({
                        blog_id: parseInt(formData.get('blog_id')),
                        quality_level: formData.get('quality_level'),
                        force: formData.get('force') === 'on'
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    result.innerHTML = `<div class="success">✅ Thành công!

Blog ID: ${data.blog_id}
Thumbnail Path: ${data.image_path}
Image URL: ${data.image_url}
Prompt: ${data.prompt}

${data.ai_info ? `AI Info: ${JSON.stringify(data.ai_info, null, 2)}` : ''}</div>`;
                    loadStatistics(); // Refresh stats
                } else {
                    result.innerHTML = `<div class="error">❌ Lỗi: ${data.message || data.error}
                    
Có thể do:
- OpenAI API key chưa được cấu hình
- Blog ID không tồn tại
- Blog đã có thumbnail (thử bật Force regenerate)
- Hạn mức API đã hết</div>`;
                }
            } catch (error) {
                result.innerHTML = `<div class="error">❌ Lỗi kết nối: ${error.message}
                
Kiểm tra:
- Network connection
- Server status
- CSRF token có hợp lệ?</div>`;
            } finally {
                btn.disabled = false;
                btn.textContent = '🎨 Generate Blog Thumbnail';
            }
        }

        async function generateProductThumbnail(event) {
            event.preventDefault();
            
            const btn = document.getElementById('productBtn');
            const result = document.getElementById('productResult');
            const formData = new FormData(event.target);
            
            btn.disabled = true;
            btn.textContent = '⏳ Đang tạo thumbnail...';
            result.innerHTML = '<div class="loading">🤖 AI đang tạo thumbnail cho product, vui lòng đợi... (có thể mất 10-30 giây)</div>';

            try {
                // Use the original thumbnail controller with CSRF token
                const response = await fetch('/ai/thumbnails/product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCSRFToken()
                    },
                    body: JSON.stringify({
                        product_id: parseInt(formData.get('product_id')),
                        quality_level: formData.get('quality_level'),
                        force: formData.get('force') === 'on'
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    result.innerHTML = `<div class="success">✅ Thành công!

Product ID: ${data.product_id}
SKU: ${data.product_sku}
Images: ${JSON.stringify(data.images, null, 2)}
Prompt: ${data.prompt}

${data.ai_info ? `AI Info: ${JSON.stringify(data.ai_info, null, 2)}` : ''}</div>`;
                    loadStatistics(); // Refresh stats
                } else {
                    result.innerHTML = `<div class="error">❌ Lỗi: ${data.message || data.error}
                    
Có thể do:
- OpenAI API key chưa được cấu hình  
- Product ID không tồn tại
- Product đã có images (thử bật Force regenerate)
- Hạn mức API đã hết</div>`;
                }
            } catch (error) {
                result.innerHTML = `<div class="error">❌ Lỗi kết nối: ${error.message}
                
Kiểm tra:
- Network connection
- Server status  
- CSRF token có hợp lệ?</div>`;
            } finally {
                btn.disabled = false;
                btn.textContent = '🖼️ Generate Product Thumbnail';
            }
        }
    </script>
</body>
</html>
