<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inay Kitchen - Menjual cireng krispi dengan berbagai varian rasa premium dan saus rujak khas.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inay Kitchen | Cireng Premium & Otentik</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Cart Styles */
        .cart-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary-color);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(255, 71, 87, 0.4);
            z-index: 100;
            transition: transform 0.3s ease;
        }
        .cart-btn:hover { transform: scale(1.1); }
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--accent);
            color: black;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.9rem;
            font-weight: bold;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }
        .modal-content {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
        }
        .close-modal {
            position: absolute;
            top: 15px; right: 20px;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        .modal h2 { color: var(--primary-color); margin-bottom: 20px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: var(--text-secondary); }
        .form-group input, .form-group textarea {
            width: 100%; padding: 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 5px;
        }
        .btn-submit {
            background: var(--secondary-color);
            color: white; border: none; padding: 12px; width: 100%;
            border-radius: 50px; font-weight: bold; cursor: pointer;
            margin-top: 10px; font-size: 1.1rem; transition: background 0.3s;
        }
        .btn-submit:hover { background: #26b15f; }

        .cart-items-list { max-height: 200px; overflow-y: auto; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .cart-item { display: flex; justify-content: space-between; margin-bottom: 10px; }

        .variant-select, .payment-select { width: 100%; padding: 8px; margin-bottom: 15px; border-radius: 5px; background: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.2); outline: none; font-family: 'Outfit'; }
        .variant-select option, .payment-select option { color: black; }
        
        #dana-details { display: none; background: rgba(52, 152, 219, 0.1); padding: 15px; border-radius: 10px; border: 1px dashed #3498db; margin-bottom: 15px; }
        #dana-details h4 { color: #3498db; margin-bottom: 5px; }

        /* Contact & About Section */
        .about-section, .contact-section { padding: 5rem 5%; text-align: center; }
        .about-section { background: var(--bg-color); }
        .contact-section { background: #0b0b0b; }
        .about-text { max-width: 800px; margin: 0 auto; color: var(--text-secondary); font-size: 1.1rem; line-height: 1.8; }
        .contact-grid { display: flex; justify-content: center; gap: 2rem; margin-top: 2rem; }
        .contact-card {
            background: var(--card-bg);
            padding: 2rem; border-radius: 15px;
            text-align: center; border: 1px solid rgba(255,255,255,0.05);
            transition: transform 0.3s ease;
            width: 250px;
        }
        .contact-card:hover { transform: translateY(-5px); border-color: var(--primary-color); }
        .contact-card h3 { margin-bottom: 10px; color: white; }
        .contact-card p, .contact-card a { color: var(--accent); text-decoration: none; font-size: 1.2rem; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <a href="#" class="logo">
            Inay<span>Kitchen</span>
        </a>
        <ul class="nav-links">
            <li><a href="#home">Beranda</a></li>
            <li><a href="#menu">Varian Cireng</a></li>
            <li><a href="#about">Tentang Kami</a></li>
            <li><a href="#contact">Kontak</a></li>
        </ul>
        <a href="#menu" class="btn-order-nav">Pesan Sekarang</a>
    </nav>

    <!-- Floating Cart Button -->
    <div class="cart-btn" onclick="openCheckout()">
        🛒
        <div class="cart-badge" id="cartCount">0</div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Cita Rasa<br><span>Cireng Premium</span></h1>
            <p class="hero-desc">
                Rasakan sensasi renyah di luar dan kenyal di dalam dengan resep rahasia bumbu rujak khas Inay Kitchen yang akan membuat Anda ketagihan pada gigitan pertama.
            </p>
            <div class="hero-btns">
                <a href="#menu" class="btn-primary">Lihat Menu</a>
                <a href="#about" class="btn-secondary">Kisah Kami</a>
            </div>
        </div>
        <div class="hero-image-container">
            <img src="{{ asset('images/cireng_hero_1775648255191.png') }}" alt="Cireng Premium Inay Kitchen" class="hero-image">
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="menu">
        <h2 class="section-title">Menu <span>Spesial Kami</span></h2>
        
        <div class="menu-grid">
            <!-- Produk 1 -->
            <div class="menu-card">
                <span class="badge-popular">Best Seller</span>
                <img src="{{ asset('images/cireng_rujak_1775648289742.png') }}" alt="Cireng Bumbu Rujak" class="menu-img">
                <div class="menu-info">
                    <h3 class="menu-title">Cireng Kuah Creamy</h3>
                    <p class="menu-desc">Cireng original super krispi disajikan dengan kuah creamy dan chilli oil yang kental dan menggugah selera.</p>
                    <div class="menu-price">Rp 10.000</div>
                    <select id="variant-creamy" class="variant-select">
                        <option value="Tanpa Isi (Original)">Varian: Tanpa Isi</option>
                        <option value="Ayam Suwir Pedas">Varian: Ayam Suwir Pedas</option>
                        <option value="Jando Pedas">Varian: Jando Pedas</option>
                        <option value="Keju Lumer">Varian: Keju Lumer</option>
                    </select>
                    <button class="btn-card" onclick="addToCartWithVariant('Cireng Kuah Creamy', 10000, 'variant-creamy')" style="border:none; cursor:pointer;">Tambah ke Keranjang</button>
                </div>
            </div>

            <!-- Produk 2 -->
            <div class="menu-card">
                <img src="{{ asset('images/cireng_isi_1775648321534.png') }}" alt="Cireng Isi Ayam Suwir" class="menu-img">
                <div class="menu-info">
                    <h3 class="menu-title">Cireng Chilli Oil</h3>
                    <p class="menu-desc">Potongan cireng kenyal dengan bumbu pedas rahasia Inay Kitchen yang melimpah.</p>
                    <div class="menu-price">Rp 10.000</div>
                    <select id="variant-chilli" class="variant-select">
                        <option value="Tanpa Isi (Original)">Varian: Tanpa Isi</option>
                        <option value="Ayam Suwir Pedas">Varian: Ayam Suwir Pedas</option>
                        <option value="Jando Pedas">Varian: Jando Pedas</option>
                        <option value="Sosis Pedas">Varian: Sosis Pedas</option>
                    </select>
                    <button class="btn-card" onclick="addToCartWithVariant('Cireng Chilli Oil', 10000, 'variant-chilli')" style="border:none; cursor:pointer;">Tambah ke Keranjang</button>
                </div>
            </div>

            <!-- Produk 3 -->
            <div class="menu-card">
                <img src="{{ asset('images/cireng_hero_1775648255191.png') }}" alt="Cireng Mozzarella Lumer" class="menu-img">
                <div class="menu-info">
                    <h3 class="menu-title">Cireng Original</h3>
                    <p class="menu-desc">Cireng mentah isi 5 pcs Cireng goreng isi 4 pcs dengan berkualitas. Favorit semua umur!</p>
                    <div class="menu-price">Rp 5.000 </div>
                    <select id="variant-mentah" class="variant-select">
                        <option value="Mix Varian">Varian: Campur (Mix)</option>
                        <option value="Original">Varian: Original</option>
                        <option value="Ayam Suwir">Varian: Ayam Suwir</option>
                        <option value="Keju">Varian: Keju</option>
                    </select>
                    <button class="btn-card" onclick="addToCartWithVariant('Cireng Mentah & Original', 5000, 'variant-mentah')" style="border:none; cursor:pointer;">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <h2 class="section-title">Tentang <span>Kami</span></h2>
        <p class="about-text">
            <strong>Inay Kitchen</strong> bermula dari kecintaan kami terhadap jajanan tradisional Indonesia. Kami berkomitmen untuk mengangkat derajat "Cireng" menjadi cemilan premium namun tetap otentik. Menggunakan bahan tapioka pilihan dan diracik dengan bumbu rahasia keluarga, setiap gigitan cireng kami menjanjikan perpaduan tekstur krispi di luar dan kenyal di dalam. Pesan sekarang dan rasakan bedanya!
        </p>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <h2 class="section-title">Hubungi <span>Kami</span></h2>
        <div class="contact-grid">
            <div class="contact-card">
                <h3>📱 WhatsApp</h3>
                <p><a href="https://wa.me/6281287986107" target="_blank">0812-8798-6107</a></p>
            </div>
            <div class="contact-card">
                <h3>📸 Instagram</h3>
                <p><a href="https://instagram.anti/inaykitchen" target="_blank">@inaykitchen</a></p>
            </div>
        </div>
    </section>

    <footer style="text-align: center; padding: 2rem; background: var(--bg-glass); border-top: 1px solid rgba(255,255,255,0.05); color: var(--text-secondary);">
        <p>&copy; 2026 Inay Kitchen. Dibuat dengan 💖 untuk Pecinta Cireng.</p>
    </footer>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeCheckout()">&times;</span>
            <h2>Detail Pesanan Anda</h2>
            <div class="cart-items-list" id="cartItemsList">
                <!-- Cart items load here -->
            </div>
            <h3 style="text-align: right; margin-bottom: 20px;">Total: Rp <span id="cartTotal">0</span></h3>

            <form id="checkoutForm">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" id="customer_name" required placeholder="Mis: Budi Santoso">
                </div>
                <div class="form-group">
                    <label>Nomor WhatsApp</label>
                    <input type="text" id="customer_whatsapp" required placeholder="Mis: 08123456789">
                </div>
                <div class="form-group">
                    <label>Alamat Pengiriman</label>
                    <textarea id="address" rows="3" required placeholder="Detail alamat lengkap..."></textarea>
                </div>
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select id="payment_method" class="payment-select" required onchange="toggleDanaDetails()">
                        <option value="COD">Cash on Delivery (COD)</option>
                        <option value="DANA">Transfer DANA</option>
                    </select>
                </div>
                <div id="dana-details">
                    <h4>Informasi Pembayaran DANA</h4>
                    <p>Silakan transfer ke nomor DANA berikut:</p>
                    <h3 style="margin: 10px 0; color: white;">0812-8798-6107</h3>
                    <p style="font-size: 0.9rem; color: var(--text-secondary);">a.n Inay Kitchen. Mohon simpan bukti transfer untuk dikirimkan ke WhatsApp admin.</p>
                </div>
                <button type="submit" class="btn-submit">Proses Pembayaran</button>
            </form>
        </div>
    </div>

    <script>
        function toggleDanaDetails() {
            const method = document.getElementById('payment_method').value;
            const details = document.getElementById('dana-details');
            if (method === 'DANA') {
                details.style.display = 'block';
            } else {
                details.style.display = 'none';
            }
        }
        let cart = [];

        function addToCart(name, price) {
            const existing = cart.find(item => item.name === name);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({ name, price, quantity: 1 });
            }
            updateCartCount();
            alert(name + " berhasil ditambahkan ke keranjang!");
        }

        function addToCartWithVariant(baseName, price, selectId) {
            const variantBox = document.getElementById(selectId);
            const variantValue = variantBox.options[variantBox.selectedIndex].value;
            const finalName = baseName + ' (' + variantValue + ')';
            addToCart(finalName, price);
        }

        function updateCartCount() {
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').innerText = count;
        }

        function openCheckout() {
            if (cart.length === 0) {
                alert('Keranjang belanja Anda masih kosong.');
                return;
            }
            
            const list = document.getElementById('cartItemsList');
            let total = 0;
            list.innerHTML = '';
            
            cart.forEach(item => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                list.innerHTML += `
                    <div class="cart-item">
                        <span>${item.name} (x${item.quantity})</span>
                        <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
                    </div>
                `;
            });
            
            document.getElementById('cartTotal').innerText = total.toLocaleString('id-ID');
            document.getElementById('checkoutModal').style.display = 'flex';
        }

        function closeCheckout() {
            document.getElementById('checkoutModal').style.display = 'none';
        }

        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('customer_name').value;
            const wa = document.getElementById('customer_whatsapp').value;
            const address = document.getElementById('address').value;
            const payment_method = document.getElementById('payment_method').value;
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            // Sending to Laravel Controller
            fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    customer_name: name,
                    customer_whatsapp: wa,
                    address: address,
                    payment_method: payment_method,
                    order_details: JSON.stringify(cart),
                    total_price: total
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Pesanan berhasil dibuat dengan ID: ' + data.order_id + '. Admin akan segera menghubungi WhatsApp Anda.');
                    cart = [];
                    updateCartCount();
                    closeCheckout();
                    // Optional: redirect ke halaman admin untuk cek
                    // window.location.href = '/admin/orders';
                } else {
                    alert('Terjadi kesalahan saat memproses pesanan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengirim pesanan.');
            });
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
