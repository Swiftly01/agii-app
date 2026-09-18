<!-- Share Referral Modal -->
<div class="modal fade modal-modern" id="shareReferralModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share Your Referral</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Share your referral code with potential vendors to start earning commissions!</p>

                <div class="mb-3">
                    <label class="form-label">Referral Code:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $marketer->referral_code ?? 0 }}" readonly>
                        <button class="btn btn-outline-primary" type="button"
                            onclick="copyText('{{ $marketer->referral_code ?? '' }}')">
                            Copy
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Referral Link:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="modalReferralLink"
                            value="{{ url('/register?ref=' . ($marketer->referral_code ?? '')) }}" readonly>
                        <button class="btn btn-outline-primary" type="button" onclick="copyReferralLink()">
                            Copy
                        </button>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="shareOnWhatsApp()">
                        <i class="fab fa-whatsapp me-1"></i> Share on WhatsApp
                    </button>
                    <button class="btn btn-primary" onclick="shareOnFacebook()">
                        <i class="fab fa-facebook me-1"></i> Share on Facebook
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade modal-modern" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdraw Earnings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    You can withdraw your pending earnings. Minimum withdrawal amount is ₦5,000.
                </div>

                <div class="mb-3">
                    <label class="form-label">Available for Withdrawal:</label>
                    <h4 class="text-success">₦{{ number_format($stats['pending_earnings'] ?? 0) }}</h4>
                </div>

                @if ($stats['pending_earnings'] ?? 0 >= 5000)
                    <form id="withdrawForm">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount to Withdraw:</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="5000"
                                max="{{ $stats['pending_earnings'] ?? 0 }}" required
                                value="{{ $stats['pending_earnings'] ?? 0 >= 5000 ? 5000 : 0 }}">
                            <div class="form-text">Minimum: ₦5,000</div>
                        </div>

                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name:</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="account_number" class="form-label">Account Number:</label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="account_name" class="form-label">Account Name:</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-money-bill-wave me-1"></i> Request Withdrawal
                            </button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle mb-2 d-block fs-1"></i>
                        You need at least ₦5,000 in pending earnings to make a withdrawal.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
