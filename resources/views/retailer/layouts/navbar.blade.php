<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ url('admin/dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets') }}/images/logo-light.png" alt="" height="80">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets') }}/images/logo-dark.png" alt="" height="80">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ url('admin/dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets') }}/images/logo-light.png" alt="" height="80">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets') }}/images/logo-light.png" alt="" height="80">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('retailer/dashboard') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('retailer/recharge') }}">
                        <i class="ri-pencil-ruler-2-line"></i> <span data-key="t-base-ui">Recharge</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('retailer/dth-recharge') }}" class="nav-link menu-link">
                        <i class="ri-apps-2-line"></i> <span data-key="t-apps">DTH</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLanding" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarLanding">
                        <i class="ri-rocket-line"></i> <span data-key="t-landing">BBPS</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLanding">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('retailer/electricity-bill-payment') }}" class="nav-link" data-key="t-one-page">
                                    Electricity </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/cylinder-gas-recharge') }}" class="nav-link"
                                    data-key="t-nft-landing">Gas Cylinder</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/insurance-premium-payment') }}" class="nav-link"
                                    data-key="t-nft-landing">Insurance Premium</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarForms" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarForms">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-forms">AEPS</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarForms">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('retailer/cash-withdrawal') }}" class="nav-link" data-key="t-basic-elements">Cash Withdrawal</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/balance-enquiry') }}" class="nav-link" data-key="t-form-select"> Balance Enquiry </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/mini-statement') }}" class="nav-link" data-key="t-checkboxs-radios">Mini Statement</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-pickers.html" class="nav-link" data-key="t-pickers"> Aadhaar Pay </a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-masks.html" class="nav-link" data-key="t-input-masks">Cash Deposit</a>
                            </li>
                             <li class="nav-item">
                                <a href="{{ url('retailer/onboarding-web') }}" class="nav-link"
                                    data-key="t-nft-landing">Onboarding Web</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/onboarding-transaction') }}" class="nav-link"
                                    data-key="t-nft-landing">Onboarding Transaction</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/onboard-status') }}" class="nav-link"
                                    data-key="t-nft-landing">Onboard Status</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/bank2-registration') }}" class="nav-link"
                                    data-key="t-nft-landing">Bank2 Registration</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/bank2-authenticate') }}" class="nav-link"
                                    data-key="t-nft-landing">Bank2 Authenticate</a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ url('retailer/enquiry') }}" class="nav-link"
                                    data-key="t-nft-landing">Enquiry</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/withdrawl') }}" class="nav-link"
                                    data-key="t-nft-landing">Withdrawl</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/mini-statement1') }}" class="nav-link"
                                    data-key="t-nft-landing">Mini Statement1</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/cash-withdraw') }}" class="nav-link"
                                    data-key="t-nft-landing">Cash Withdraw</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/aadhar-pay') }}" class="nav-link"
                                    data-key="t-nft-landing">Aadhar Pay</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/generate-onboarding') }}" class="nav-link"
                                    data-key="t-nft-landing">Generate Onboarding</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/onboard-status-check') }}" class="nav-link"
                                    data-key="t-nft-landing">Onboard Status Check</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/bank4-authentication') }}" class="nav-link"
                                    data-key="t-nft-landing">Bank4 Authentication</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/balance-enquiry-new') }}" class="nav-link"
                                    data-key="t-nft-landing">Balance Enquiry</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/bank4-cash-withdraw') }}" class="nav-link"
                                    data-key="t-nft-landing">Bank4 Cash Withdraw</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('retailer/bank4-mini-statement') }}" class="nav-link"
                                    data-key="t-nft-landing">Bank4 Mini Statement</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDMT" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDMT">
                        <i class="ri-layout-grid-line"></i> <span data-key="t-tables">DMT</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDMT">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{url('retailer/remitter-kyc')}}" class="nav-link" data-key="t-one-page"> Remitter EKYC </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('retailer/register-beneficiary')}}" class="nav-link" data-key="t-nft-landing"> Register Beneficiary </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
