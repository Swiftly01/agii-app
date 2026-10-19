<style>
    :root { --primary-color: #8fc74a; --secondary-color: #7ab436; --primary-light: #f0f7e6; --dark-color: #2c3e50; --border-color: #dee2e6; }
    body { background: linear-gradient(135deg, var(--primary-light) 0%, #f8f9fa 100%); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px 12px; }
    .phone-card { background: #fff; border-radius: 20px; box-shadow: 0 5px 15px rgba(0, 0, 0, .08); border: 1px solid var(--border-color); overflow: hidden; max-width: 520px; width: 100%; }
    .phone-header { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: #fff; padding: 28px 20px; text-align: center; }
    .phone-icon { width: 72px; height: 72px; border-radius: 50%; background: rgba(255, 255, 255, .2); display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 10px; }
    .phone-body { padding: 28px; }
    .btn-primary { background: var(--primary-color); border-color: var(--primary-color); font-weight: 600; border-radius: 8px; }
    .btn-primary:hover, .btn-primary:focus { background: var(--secondary-color); border-color: var(--secondary-color); }
    .form-control, .form-select { border-radius: 8px; }
    .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 .2rem rgba(143, 199, 74, .25); }
    .otp-input { letter-spacing: .6em; text-align: center; font-size: 1.6rem; font-weight: 600; }
    a { color: var(--secondary-color); }
</style>
