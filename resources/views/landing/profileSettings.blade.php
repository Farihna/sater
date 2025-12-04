@extends('landing.layouts.app')
@section('title', 'Pengaturan Akun')
@section('content')
<section id="profile-settings" class="page-section">
    <div class="container py-5">
        <h2 class="section-title">Pengaturan Akun</h2>
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="profile-menu-item active" onclick="showProfileTab('general')">
                        <i class="bi bi-person"></i> Profil Saya
                    </div>
                    <div class="profile-menu-item" onclick="showProfileTab('security')">
                        <i class="bi bi-shield-lock"></i> Keamanan Akun
                    </div>
                    <div class="profile-menu-item" onclick="showProfileTab('addresses')">
                        <i class="bi bi-geo-alt"></i> Alamat
                    </div>
                    <div class="profile-menu-item" onclick="showProfileTab('payment')">
                        <i class="bi bi-credit-card"></i> Kartu / Rekening Bank
                    </div>
                    @if (Auth::user()->isPartner())
                        <div class="profile-menu-item" onclick="showProfileTab('payout')">
                            <i class="bi bi-wallet2"></i> Pengaturan Pembayaran </div>
                        <div class="profile-menu-item" onclick="showProfileTab('business')">
                            <i class="bi bi-shop"></i> Profil Usaha
                        </div>
                    @endif
                    <div class="profile-menu-item" onclick="showProfileTab('notifications')">
                        <i class="bi bi-bell"></i> Pengaturan Notifikasi
                    </div>
                    <div class="profile-menu-item" onclick="showProfileTab('privacy')">
                        <i class="bi bi-lock"></i> Pengaturan Privasi
                    </div>
                    <div class="profile-menu-item" onclick="showProfileTab('activity')">
                        <i class="bi bi-clock-history"></i> Riwayat Aktifitas
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="col-md-9">
                <div class="profile-content">
                    <!-- General Tab -->
                    <div id="general-tab" class="tab-content active">
                        <h4 class="mb-4">General Information</h4>
                        
                        <div class="profile-picture-container">
                            <img src="https://picsum.photos/seed/user-avatar/150/150.jpg" alt="Profile" class="profile-picture" id="profilePictureImg">
                            <div class="profile-picture-upload" onclick="document.getElementById('profilePictureInput').click()">
                                <i class="bi bi-camera"></i>
                            </div>
                            <input type="file" id="profilePictureInput" style="display: none;" accept="image/*" onchange="updateProfilePicture(event)">
                        </div>
                        
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" value="John">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" value="Doe">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="john.doe@example.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" value="+1 (555) 123-4567">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control" rows="3" placeholder="Tell us about yourself...">Passionate farmer with 10+ years of experience in cattle management.</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" value="1985-06-15">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <select class="form-select">
                                    <option>English</option>
                                    <option>Spanish</option>
                                    <option>French</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                    
                    <!-- Security Tab -->
                    <div id="security-tab" class="tab-content">
                        <h4 class="mb-4">Security Settings</h4>
                        
                        <div class="settings-section">
                            <h5>Change Password</h5>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </div>
                        
                        <div class="settings-section">
                            <h5>Two-Factor Authentication</h5>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Add an extra layer of security to your account with 2FA.
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                                <label class="form-check-label" for="twoFactorAuth">
                                    Enable Two-Factor Authentication
                                </label>
                            </div>
                            <button class="btn btn-outline-primary">Setup 2FA</button>
                        </div>
                        
                        <div class="settings-section">
                            <h5>Active Sessions</h5>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Chrome on Windows</h6>
                                            <small class="text-muted">192.168.1.1 • Last active: 2 hours ago</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger">Terminate</button>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Safari on iPhone</h6>
                                            <small class="text-muted">192.168.1.2 • Last active: 1 day ago</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger">Terminate</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Addresses Tab -->
                    <div id="addresses-tab" class="tab-content">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">My Addresses</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-circle"></i> Add Address
                            </button>
                        </div>
                        
                        <div class="address-card default">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-2">Home Address</h6>
                                    <p class="mb-1">John Doe</p>
                                    <p class="mb-1">123 Farm Road</p>
                                    <p class="mb-1">Rural Area, State 12345</p>
                                    <p class="mb-0">+1 (555) 123-4567</p>
                                </div>
                                <div>
                                    <span class="badge bg-success">Default</span>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-primary me-1">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="address-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-2">Farm Address</h6>
                                    <p class="mb-1">John Doe</p>
                                    <p class="mb-1">456 Cattle Lane</p>
                                    <p class="mb-1">Farm County, State 67890</p>
                                    <p class="mb-0">+1 (555) 987-6543</p>
                                </div>
                                <div>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-primary me-1">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        <button class="btn btn-sm btn-success">Set Default</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Methods Tab -->
                    <div id="payment-tab" class="tab-content">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">Payment Methods</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                                <i class="bi bi-plus-circle"></i> Add Payment Method
                            </button>
                        </div>
                        
                        <div class="payment-method-card default">
                            <div>
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-credit-card" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                                    <div>
                                        <h6 class="mb-1">Visa ending in 4242</h6>
                                        <small class="text-muted">Expires 12/25</small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-success">Default</span>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary me-1">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Remove</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-method-card">
                            <div>
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-paypal" style="font-size: 1.5rem; color: #0070ba;"></i>
                                    <div>
                                        <h6 class="mb-1">PayPal</h6>
                                        <small class="text-muted">john.doe@example.com</small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary me-1">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    <button class="btn btn-sm btn-success">Set Default</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payout Settings Tab -->
                     <div id="payout-tab" class="tab-content">
                        @livewire('edit-payout-settings')
                    </div>
                    
                    <!-- Business Information Tab -->
                    <div id="business-tab" class="tab-content">
                        <h4 class="mb-4">Profil Bisnis dan Verifikasi</h4>
                        <p class="text-muted">Data ini diverifikasi oleh sistem. Perubahan alamat mungkin memengaruhi tarif pengiriman.</p>
                        
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama_bisnis" class="form-label">Nama Bisnis/Toko</label>
                                <input type="text" name="nama_bisnis" id="nama_bisnis" class="form-control" 
                                    value="" required>
                            </div>

                            <div class="mb-3">
                                <label for="full_address" class="form-label">Alamat Lengkap Peternakan/Gudang</label>
                                <textarea name="full_address" id="full_address" class="form-control" rows="3" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status Verifikasi KTP (Terkunci)</label>
                                <input type="text" class="form-control" disabled value="">
                            </div>

                            <div class="mb-3">
                                <label for="ktp_document" class="form-label">Unggah Ulang Dokumen KTP</label>
                                <input type="file" name="ktp_document" id="ktp_document" class="form-control" accept="image/*">
                                <small class="text-muted">Unggah hanya jika ingin mengganti dokumen verifikasi.</small>
                            </div>

                            <button type="submit" class="btn btn-success mt-3">Simpan Profil Bisnis</button>
                        </form>
                    </div>

                    <!-- Notifications Tab -->
                    <div id="notifications-tab" class="tab-content">
                        <h4 class="mb-4">Notification Preferences</h4>
                        
                        <div class="notification-item">
                            <div>
                                <h6 class="mb-1">Order Updates</h6>
                                <small class="text-muted">Get notified about your order status</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="orderUpdates" checked>
                                <label class="form-check-label" for="orderUpdates"></label>
                            </div>
                        </div>
                        
                        <div class="notification-item">
                            <div>
                                <h6 class="mb-1">Promotional Offers</h6>
                                <small class="text-muted">Receive special deals and discounts</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="promotionalOffers">
                                <label class="form-check-label" for="promotionalOffers"></label>
                            </div>
                        </div>
                        
                        <div class="notification-item">
                            <div>
                                <h6 class="mb-1">New Products</h6>
                                <small class="text-muted">Be the first to know about new arrivals</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="newProducts" checked>
                                <label class="form-check-label" for="newProducts"></label>
                            </div>
                        </div>
                        
                        <div class="notification-item">
                            <div>
                                <h6 class="mb-1">Newsletter</h6>
                                <small class="text-muted">Weekly newsletter with farming tips</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="newsletter" checked>
                                <label class="form-check-label" for="newsletter"></label>
                            </div>
                        </div>
                        
                        <div class="notification-item">
                            <div>
                                <h6 class="mb-1">Account Activity</h6>
                                <small class="text-muted">Security alerts and login notifications</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="accountActivity" checked>
                                <label class="form-check-label" for="accountActivity"></label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Privacy Tab -->
                    <div id="privacy-tab" class="tab-content">
                        <h4 class="mb-4">Privacy Settings</h4>
                        
                        <div class="settings-section">
                            <h5>Profile Visibility</h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="profileVisibility" id="publicProfile" checked>
                                <label class="form-check-label" for="publicProfile">
                                    Public - Anyone can view your profile
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="profileVisibility" id="privateProfile">
                                <label class="form-check-label" for="privateProfile">
                                    Private - Only you can view your profile
                                </label>
                            </div>
                        </div>
                        
                        <div class="settings-section">
                            <h5>Data & Analytics</h5>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="analyticsTracking" checked>
                                <label class="form-check-label" for="analyticsTracking">
                                    Allow analytics tracking to improve our services
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="personalizedAds">
                                <label class="form-check-label" for="personalizedAds">
                                    Show personalized advertisements
                                </label>
                            </div>
                        </div>
                        
                        <div class="settings-section">
                            <h5>Download Your Data</h5>
                            <p class="text-muted mb-3">Request a copy of all your personal data</p>
                            <button class="btn btn-outline-primary">Request Data Download</button>
                        </div>
                        
                        <div class="settings-section">
                            <h5>Delete Account</h5>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Warning: This action cannot be undone. All your data will be permanently deleted.
                            </div>
                            <button class="btn btn-danger">Delete Account</button>
                        </div>
                    </div>
                    
                    <!-- Activity Log Tab -->
                    <div id="activity-tab" class="tab-content">
                        <h4 class="mb-4">Activity Log</h4>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Order Placed</h6>
                                <p class="mb-1">Order #ORD-2023-1236 for Angus Beef Cattle</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-pencil"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Profile Updated</h6>
                                <p class="mb-1">Updated phone number and address</p>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Payment Method Added</h6>
                                <p class="mb-1">Added Visa card ending in 4242</p>
                                <small class="text-muted">3 days ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Login</h6>
                                <p class="mb-1">Logged in from Chrome on Windows</p>
                                <small class="text-muted">5 days ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-heart"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Added to Wishlist</h6>
                                <p class="mb-1">Added Holstein Dairy Cow to wishlist</p>
                                <small class="text-muted">1 week ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function showProfileTab(tabId) {
        const menuItems = document.querySelectorAll('.profile-menu-item');
        menuItems.forEach(item => {
            item.classList.remove('active');
        });
        const activeMenuItem = document.querySelector(`.profile-menu-item[onclick="showProfileTab('${tabId}')"]`);
        if (activeMenuItem) {
            activeMenuItem.classList.add('active');
        }
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.style.display = 'none';
            content.classList.remove('active'); 
        });

        const targetContent = document.getElementById(tabId + '-tab');
        if (targetContent) {
            targetContent.style.display = 'block';
            targetContent.classList.add('active');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        showProfileTab('general'); 
    });
</script>
@endsection