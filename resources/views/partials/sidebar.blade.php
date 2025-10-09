<div class="col-md-2 sidebar">
    <div class="user-info">
        <div class="d-flex align-items-center mb-4 pb-3">
            <div class="me-3">
                @if (Auth::user()->company_logo)
                    <img src="{{ asset('storage/' . Auth::user()->company_logo) }}" alt="Logo">
                @else
                    <i class="fas fa-user-circle fa-3x text-light"></i>
                @endif
            </div>
            <div>
                <h6>{{ Auth::user()->name }}</h6>
                <small>{{ Auth::user()->email }}</small>
            </div>
        </div>
    </div>

    <nav class="mb-4">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Home
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ url('/history') }}" class="nav-link {{ request()->is('history') ? 'active' : '' }}">
                    <i class="fas fa-history me-2"></i> History
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->is('profile') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i> Profile
                </a>
            </li>
        </ul>
    </nav>

    @if (request()->is('/') || request()->is('invoices/*/edit'))
        <div>
            <label class="form-label small">Currency</label>
            <select class="form-select form-select-sm mb-3" id="currency">
                <option value="$" {{ $invoice?->currency == '$' ? 'selected' : '' }}>USD ($)</option>
                <option value="€" {{ $invoice?->currency == '€' ? 'selected' : '' }}>EUR (€)</option>
                <option value="£" {{ $invoice?->currency == '£' ? 'selected' : '' }}>GBP (£)</option>
                <option value="৳" {{ $invoice?->currency == '৳' ? 'selected' : '' }}>BDT (৳)</option>
                <option value="₹" {{ $invoice?->currency == '₹' ? 'selected' : '' }}>INR (₹)</option>
            </select>

            <div class="d-grid gap-2">
                <button class="btn btn-success btn-sm" id="downloadPdfBtn">
                    <i class="fas fa-download me-1"></i> Download PDF
                </button>
                <button class="btn btn-outline-light btn-sm" id="printBtn">
                    <i class="fas fa-print me-1"></i> Print
                </button>
                <button class="btn btn-outline-danger btn-sm" id="resetBtn">
                    <i class="fas fa-redo me-1"></i> Reset
                </button>
            </div>
        </div>
    @endif

    <div class="logout mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
