<div class="col-md-2 bg-dark text-white d-flex flex-column justify-content-between p-3"
                style="min-height: 100vh; position: fixed; width: 16.6667%;">

                {{-- Top Section: User Info --}}
                <div>
                    <div class="d-flex align-items-center mb-4 border-bottom border-secondary pb-3">
                        <div class="me-2">
                            <i class="fas fa-user-circle fa-2x text-light"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white">{{ Auth::user()->name }}</h6>
                            <small class=" text-white">{{ Auth::user()->email }}</small>
                        </div>
                    </div>

                    {{-- Navigation Menu --}}
                    <nav class="mb-4">
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a href="{{ url('/') }}"
                                    class="nav-link text-white d-flex align-items-center {{ request()->is('/') ? 'active fw-bold' : '' }}">
                                    <i class="fas fa-home me-2"></i> Home
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ url('/history') }}"
                                    class="nav-link text-white d-flex align-items-center {{ request()->is('history') ? 'active fw-bold' : '' }}">
                                    <i class="fas fa-history me-2"></i> History
                                </a>
                            </li>
                        </ul>
                    </nav>

                    {{-- Divider --}}
                    <hr class="border-secondary">

                    {{-- Settings Section (only for Home page) --}}
                    @if (request()->is('/'))
                        <div>
                            <label class="form-label small">Currency</label>
                            <select class="form-select form-select-sm mb-3" id="currency">
                                <option value="$">USD ($)</option>
                                <option value="€">EUR (€)</option>
                                <option value="£">GBP (£)</option>
                                <option value="৳">BDT (৳)</option>
                                <option value="₹">INR (₹)</option>
                                <option value="¥">JPY (¥)</option>
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
                </div>

                {{-- Bottom Section: Logout --}}
                <div class="mt-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>