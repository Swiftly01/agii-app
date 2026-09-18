<!-- resources/views/profile/edit.blade.php -->
@extends('layout.layout')
@section('title', 'Edit Profile - Agii')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-gear me-2"></i>
                            <h4 class="mb-0">Edit Your Profile</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Profile Image -->
                            <div class="row mb-4">
                                <div class="col-md-3 text-center">
                                    <div class="profile-image-container mb-3">
                                        @if ($user->profile_image)
                                            <img src="{{ url($user->profile_image) }}" alt="Profile Image"
                                                class="profile-image-preview rounded-circle border"
                                                id="profileImagePreview">
                                        @else
                                            <div class="profile-image-placeholder rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                                style="width: 150px; height: 150px;">
                                                <i class="bi bi-person fs-1 text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="profile_image" class="form-label small">Profile Picture</label>
                                        <input type="file" class="form-control form-control-sm" id="profile_image"
                                            name="profile_image" accept="image/*">
                                        <div class="form-text">Max 2MB • JPG, PNG, GIF</div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="first_name" class="form-label required-field">First Name</label>
                                                <input type="text"
                                                    class="form-control @error('first_name') is-invalid @enderror"
                                                    id="first_name" name="first_name"
                                                    value="{{ old('first_name', $user->first_name) }}" required>
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="last_name" class="form-label required-field">Last Name</label>
                                                <input type="text"
                                                    class="form-control @error('last_name') is-invalid @enderror"
                                                    id="last_name" name="last_name"
                                                    value="{{ old('last_name', $user->last_name) }}" required>
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label required-field">Email
                                                    Address</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label required-field">Phone Number</label>
                                                <input type="tel"
                                                    class="form-control @error('phone') is-invalid @enderror" id="phone"
                                                    name="phone" value="{{ old('phone', $user->phone) }}" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Location Information</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="state" class="form-label required-field">State</label>
                                        <select id="state" name="state"
                                            class="form-control @error('state') is-invalid @enderror" required
                                            onchange="populateLGAs()">
                                            <option value="">-- Select State --</option>
                                            @if (old('state', $user->state))
                                                <option value="{{ old('state', $user->state) }}" selected>
                                                    {{ old('state', $user->state) }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="local_government" class="form-label required-field">Local
                                            Government</label>
                                        <select id="local_government" name="local_government"
                                            class="form-control @error('local_government') is-invalid @enderror" required>
                                            <option value="">-- Select Local Government --</option>
                                            @if (old('local_government', $user->local_government))
                                                <option value="{{ old('local_government', $user->local_government) }}"
                                                    selected>
                                                    {{ old('local_government', $user->local_government) }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('local_government')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="city" class="form-label required-field">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city', $user->city) }}"
                                            required>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Full Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                            placeholder="Enter your complete address">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Business Information (for vendors) -->
                            @if ($user->user_type === 'vendor')
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="border-bottom pb-2 mb-3">Business Information</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_name" class="form-label">Business Name</label>
                                            <input type="text"
                                                class="form-control @error('business_name') is-invalid @enderror"
                                                id="business_name" name="business_name"
                                                value="{{ old('business_name', $user->business_name) }}"
                                                placeholder="Your business name">
                                            @error('business_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="business_type" class="form-label">Business Type</label>
                                            <input type="text"
                                                class="form-control @error('business_type') is-invalid @enderror"
                                                id="business_type" name="business_type"
                                                value="{{ old('business_type', $user->business_type) }}"
                                                placeholder="e.g., Individual, Company">
                                            @error('business_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="business_category" class="form-label">Business Category</label>
                                            <select class="form-control @error('business_category') is-invalid @enderror"
                                                id="business_category" name="business_category">
                                                <option value="">Select your business category</option>
                                                <option value="electronics"
                                                    {{ old('business_category', $user->business_category) == 'electronics' ? 'selected' : '' }}>
                                                    Electronics</option>
                                                <option value="fashion"
                                                    {{ old('business_category', $user->business_category) == 'fashion' ? 'selected' : '' }}>
                                                    Fashion</option>
                                                <option value="home"
                                                    {{ old('business_category', $user->business_category) == 'home' ? 'selected' : '' }}>
                                                    Home & Garden</option>
                                                <option value="vehicles"
                                                    {{ old('business_category', $user->business_category) == 'vehicles' ? 'selected' : '' }}>
                                                    Vehicles</option>
                                                <option value="properties"
                                                    {{ old('business_category', $user->business_category) == 'properties' ? 'selected' : '' }}>
                                                    Properties</option>
                                                <option value="services"
                                                    {{ old('business_category', $user->business_category) == 'services' ? 'selected' : '' }}>
                                                    Services</option>
                                            </select>
                                            @error('business_category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Social Media Links -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Social Media Links</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="facebook_url" class="form-label">Facebook URL</label>
                                        <input type="url"
                                            class="form-control @error('facebook_url') is-invalid @enderror"
                                            id="facebook_url" name="facebook_url"
                                            value="{{ old('facebook_url', $user->facebook_url) }}"
                                            placeholder="https://facebook.com/yourpage">
                                        @error('facebook_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="instagram_url" class="form-label">Instagram URL</label>
                                        <input type="url"
                                            class="form-control @error('instagram_url') is-invalid @enderror"
                                            id="instagram_url" name="instagram_url"
                                            value="{{ old('instagram_url', $user->instagram_url) }}"
                                            placeholder="https://instagram.com/yourprofile">
                                        @error('instagram_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="twitter_url" class="form-label">Twitter URL</label>
                                        <input type="url"
                                            class="form-control @error('twitter_url') is-invalid @enderror"
                                            id="twitter_url" name="twitter_url"
                                            value="{{ old('twitter_url', $user->twitter_url) }}"
                                            placeholder="https://twitter.com/yourprofile">
                                        @error('twitter_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                        <input type="text"
                                            class="form-control @error('whatsapp_number') is-invalid @enderror"
                                            id="whatsapp_number" name="whatsapp_number"
                                            value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                                            placeholder="+234 800 000 0000">
                                        @error('whatsapp_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Password Change (Optional) -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Change Password</h5>
                                    <p class="text-muted small">Leave blank to keep current password</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="password-input-group">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" placeholder="Enter new password">
                                            <button type="button" class="password-toggle">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <div class="password-input-group">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Confirm new password">
                                            <button type="button" class="password-toggle">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .required-field::after {
            content: " *";
            color: #e74c3c;
        }

        .profile-image-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .profile-image-placeholder {
            width: 150px;
            height: 150px;
            border: 2px dashed #dee2e6;
        }

        .password-input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
        }

        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile image preview
            const profileImageInput = document.getElementById('profile_image');
            const profileImagePreview = document.getElementById('profileImagePreview');

            if (profileImageInput && profileImagePreview) {
                profileImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            profileImagePreview.src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Password toggle
            document.querySelectorAll('.password-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const passwordInput = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });

            // Initialize states and LGAs
            populateStates();

            // Set initial LGA if state is already selected
            const currentState = document.getElementById('state').value;
            if (currentState) {
                setTimeout(() => {
                    populateLGAs();
                }, 100);
            }
        });

        // Your existing states and LGAs functions
        const lgas = {
            "Abia": ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North",
                "Isiala Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma", "Ugwunagbo", "Ukwa East",
                "Ukwa West", "Umuahia North", "Umuahia South", "Umu Nneochi"
            ],
            "Adamawa": ["Demsa", "Fufore", "Ganye", "Girei", "Gombi", "Guyuk", "Hong", "Jada", "Lamurde", "Madagali",
                "Maiha", "Mayo-Belwa", "Michika", "Mubi North", "Mubi South", "Numan", "Shelleng", "Song", "Toungo",
                "Yola North", "Yola South"
            ],
            "Akwa Ibom": ["Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Etinan", "Ibeno",
                "Ibesikpo Asutan", "Ibiono Ibom", "Ika", "Ikono", "Ikot Abasi", "Ikot Ekpene", "Ini", "Itu", "Mbo",
                "Mkpat Enin", "Nsit Atai", "Nsit Ibom", "Nsit Ubium", "Obot Akara", "Okobo", "Onna", "Oron",
                "Oruk Anam", "Udung Uko", "Ukanafun", "Uruan", "Urue-Offong/Oruko", "Uyo"
            ],
            "Anambra": ["Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum",
                "Dunukofia", "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North",
                "Nnewi South", "Ogbaru", "Onitsha North", "Onitsha South", "Orumba North", "Orumba South", "Oyi"
            ],
            "Bauchi": ["Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade",
                "Itas/Gadau", "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro",
                "Warji", "Zaki"
            ],
            "Bayelsa": ["Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw",
                "Yenagoa"
            ],
            "Benue": ["Ado", "Agatu", "Apa", "Buruku", "Gboko", "Guma", "Gwer East", "Gwer West", "Katsina-Ala",
                "Konshisha", "Kwande", "Logo", "Makurdi", "Obi", "Ogbadibo", "Ohimini", "Oju", "Okpokwu", "Otukpo",
                "Tarka", "Ukum", "Ushongo", "Vandeikya"
            ],
            "Borno": ["Abadam", "Askira/Uba", "Bama", "Bayo", "Biu", "Chibok", "Damboa", "Dikwa", "Gubio", "Guzamala",
                "Gwoza", "Hawul", "Jere", "Kaga", "Kala/Balge", "Konduga", "Kukawa", "Kwaya Kusar", "Mafa",
                "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"
            ],
            "Cross River": ["Abi", "Akamkpa", "Akpabuyo", "Bakassi", "Bekwarra", "Biase", "Boki", "Calabar Municipal",
                "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Obudu", "Odukpani", "Ogoja", "Yakurr",
                "Yala"
            ],
            "Delta": ["Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ethiope East", "Ethiope West",
                "Ika North East", "Ika South", "Isoko North", "Isoko South", "Ndokwa East", "Ndokwa West", "Okpe",
                "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North", "Ughelli South",
                "Ukwuani", "Uvwie", "Warri North", "Warri South", "Warri South West"
            ],
            "Ebonyi": ["Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo",
                "Ishielu", "Ivo", "Izzi", "Ohaozara", "Ohaukwu", "Onicha"
            ],
            "Edo": ["Akoko-Edo", "Egor", "Esan Central", "Esan North-East", "Esan South-East", "Esan West",
                "Etsako Central", "Etsako East", "Etsako West", "Igueben", "Ikpoba-Okha", "Orhionmwon", "Oredo",
                "Ovia North-East", "Ovia South-West", "Owan East", "Owan West", "Uhunmwonde"
            ],
            "Ekiti": ["Ado Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Emure", "Gbonyin",
                "Ido-Osi", "Ijero", "Ikere", "Ikole", "Ilejemeje", "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"
            ],
            "Enugu": ["Aninri", "Awgu", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo Etiti",
                "Igbo Eze North", "Igbo Eze South", "Isi Uzo", "Nkanu East", "Nkanu West", "Nsukka", "Oji River",
                "Udenu", "Udi", "Uzo-Uwani"
            ],
            "FCT": ["Abaji", "Bwari", "Gwagwalada", "Kuje", "Kwali", "Municipal Area Council"],
            "Gombe": ["Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada",
                "Shongom", "Yamaltu/Deba"
            ],
            "Imo": ["Aboh Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South",
                "Ihitte/Uboma", "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor Okpala", "Njaba", "Nkwerre",
                "Nwangele", "Obowo", "Oguta", "Ohaji/Egbema", "Okigwe", "Onuimo", "Orlu", "Orsu", "Oru East",
                "Oru West", "Owerri Municipal", "Owerri North", "Owerri West"
            ],
            "Jigawa": ["Auyo", "Babura", "Biriniwa", "Birnin Kudu", "Buji", "Dutse", "Gagarawa", "Garki", "Gumel",
                "Guri", "Gwaram", "Gwiwa", "Hadejia", "Jahun", "Kafin Hausa", "Kazaure", "Kiri Kasama", "Kiyawa",
                "Kaugama", "Maigatari", "Malam Madori", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura",
                "Yankwashi"
            ],
            "Kaduna": ["Birnin Gwari", "Chikun", "Giwa", "Igabi", "Ikara", "Jaba", "Jema'a", "Kachia", "Kaduna North",
                "Kaduna South", "Kagarko", "Kajuru", "Kaura", "Kauru", "Kubau", "Kudan", "Lere", "Makarfi",
                "Sabon Gari", "Sanga", "Soba", "Zangon Kataf", "Zaria"
            ],
            "Kano": ["Ajingi", "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Dawakin Kudu",
                "Dawakin Tofa", "Doguwa", "Fagge", "Gabasawa", "Garko", "Garum Mallam", "Gaya", "Gezawa", "Gwale",
                "Gwarzo", "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Kura",
                "Madobi", "Makoda", "Minjibir", "Nasarawa", "Rano", "Rimin Gado", "Rogo", "Shanono", "Sumaila",
                "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo", "Warawa", "Wudil"
            ],
            "Katsina": ["Bakori", "Batagarawa", "Batsari", "Baure", "Bindawa", "Charanchi", "Dandume", "Danja",
                "Dan Musa", "Daura", "Dutsi", "Dutsin Ma", "Faskari", "Funtua", "Ingawa", "Jibia", "Kafur", "Kaita",
                "Kankara", "Kankia", "Katsina", "Kurfi", "Kusada", "Mai'Adua", "Malumfashi", "Mani", "Mashi",
                "Matazu", "Musawa", "Rimi", "Sabuwa", "Safana", "Sandamu", "Zango"
            ],
            "Kebbi": ["Aleiro", "Arewa Dandi", "Argungu", "Augie", "Bagudo", "Birnin Kebbi", "Bunza", "Dandi", "Fakai",
                "Gwandu", "Jega", "Kalgo", "Koko/Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru",
                "Wasagu/Danko", "Yauri", "Zuru"
            ],
            "Kogi": ["Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela-Odolu", "Ijumu",
                "Kabba/Bunu", "Kogi", "Lokoja", "Mopa-Muro", "Ofu", "Ogori/Magongo", "Okehi", "Okene", "Olamaboro",
                "Omala", "Yagba East", "Yagba West"
            ],
            "Kwara": ["Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West",
                "Irepodun", "Isin", "Kaiama", "Moro", "Offa", "Oke Ero", "Oyun", "Pategi"
            ],
            "Lagos": ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti Osa",
                "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland",
                "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"
            ],
            "Nasarawa": ["Akwanga", "Awe", "Doma", "Karu", "Keana", "Keffi", "Kokona", "Lafia", "Nasarawa",
                "Nasarawa Egon", "Obi", "Toto", "Wamba"
            ],
            "Niger": ["Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha",
                "Kontagora", "Lapai", "Lavun", "Magama", "Mariga", "Mashegu", "Mokwa", "Moya", "Paikoro", "Rafi",
                "Rijau", "Shiroro", "Suleja", "Tafa", "Wushishi"
            ],
            "Ogun": ["Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Egbado North", "Egbado South", "Ewekoro",
                "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon",
                "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu"
            ],
            "Ondo": ["Akoko North-East", "Akoko North-West", "Akoko South-West", "Akoko South-East", "Akure North",
                "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile Oluji/Okeigbo", "Irele", "Odigbo",
                "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"
            ],
            "Osun": ["Atakunmosa East", "Atakunmosa West", "Aiyedaade", "Aiyedire", "Boluwaduro", "Boripe", "Ede North",
                "Ede South", "Egbedore", "Ejigbo", "Ife Central", "Ife East", "Ife North", "Ife South", "Ifedayo",
                "Ifelodun", "Ila", "Ilesa East", "Ilesa West", "Irepodun", "Irewole", "Isokan", "Iwo", "Obokun",
                "Odo Otin", "Ola Oluwa", "Olorunda", "Oriade", "Orolu", "Osogbo"
            ],
            "Oyo": ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East",
                "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East",
                "Ibarapa North", "Ido", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu",
                "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope",
                "Ori Ire", "Oyo East", "Oyo West", "Saki East", "Saki West", "Surulere"
            ],
            "Plateau": ["Barkin Ladi", "Bassa", "Bokkos", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke",
                "Langtang North", "Langtang South", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam",
                "Wase"
            ],
            "Rivers": ["Abua/Odual", "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Asari-Toru", "Bonny",
                "Degema", "Eleme", "Emuoha", "Etche", "Gokana", "Ikwerre", "Khana", "Obio/Akpor",
                "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omuma", "Opobo/Nkoro", "Oyigbo", "Port Harcourt", "Tai"
            ],
            "Sokoto": ["Binji", "Bodinga", "Dange Shuni", "Gada", "Goronyo", "Gudu", "Gwadabawa", "Illela", "Isa",
                "Kebbe", "Kware", "Rabah", "Sabon Birni", "Shagari", "Silame", "Sokoto North", "Sokoto South",
                "Tambuwal", "Tangaza", "Tureta", "Wamako", "Wurno", "Yabo"
            ],
            "Taraba": ["Ardo Kola", "Bali", "Donga", "Gashaka", "Gassol", "Ibi", "Jalingo", "Karim Lamido", "Kurmi",
                "Lau", "Sardauna", "Takum", "Ussa", "Wukari", "Yorro", "Zing"
            ],
            "Yobe": ["Bade", "Bursari", "Damaturu", "Fika", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa",
                "Machina", "Nangere", "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yusufari"
            ],
            "Zamfara": ["Anka", "Bakura", "Birnin Magaji/Kiyaw", "Bukkuyum", "Bungudu", "Gummi", "Gusau",
                "Kaura Namoda", "Maradun", "Maru", "Shinkafi", "Talata Mafara", "Tsafe", "Zurmi"
            ]
        };



        function populateStates() {
            const stateSelect = document.getElementById('state');
            if (stateSelect) {
                for (let state in lgas) {
                    let option = document.createElement('option');
                    option.value = state;
                    option.textContent = state;
                    stateSelect.appendChild(option);
                }

                // Set current state value
                const currentState = "{{ old('state', $user->state) }}";
                if (currentState) {
                    stateSelect.value = currentState;
                }
            }
        }

        function populateLGAs() {
            const lgaSelect = document.getElementById('local_government');
            const stateSelect = document.getElementById('state');

            if (!lgaSelect || !stateSelect) return;

            const selectedState = stateSelect.value;

            // Clear previous LGAs
            lgaSelect.innerHTML = '<option value="">-- Select Local Government --</option>';

            if (selectedState && lgas[selectedState]) {
                lgas[selectedState].forEach(lga => {
                    const option = document.createElement('option');
                    option.value = lga;
                    option.textContent = lga;
                    lgaSelect.appendChild(option);
                });
            }

            // Set current LGA value
            const currentLGA = "{{ old('local_government', $user->local_government) }}";
            if (currentLGA) {
                lgaSelect.value = currentLGA;
            }
        }
    </script>


@endsection
