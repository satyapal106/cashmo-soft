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
                    <a class="nav-link menu-link" href="{{ url('admin/dashboard') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLanding" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarLanding">
                        <i class="ri-rocket-line"></i> <span data-key="t-landing">Recharge Plans</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLanding">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('admin/add-rechargeplan') }}" class="nav-link" data-key="t-one-page">
                                    Add Recharge Plan </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/recharge-plan-list') }}" class="nav-link"
                                    data-key="t-nft-landing"> Recharge Plan List </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('admin/all-retailer') }}">
                        <i class="ri-account-circle-line"></i> <span data-key="t-authentication">All Retailer</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarForms" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarForms">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-forms">DTH Plans</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarForms">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('admin/dth-plan') }}" class="nav-link" data-key="t-basic-elements">
                                    Add DTH Plan</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/dth-plan-list') }}" class="nav-link"
                                    data-key="t-form-select">DTH Plans List </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarTables" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarTables">
                        <i class="ri-settings-line"></i><span data-key="t-tables">Setting</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarTables">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ url('admin/services') }}">
                                    <span data-key="t-base-ui">Services</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/providers') }}" class="nav-link menu-link">
                                    <span data-key="t-apps">Providers</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/api-providers') }}" class="nav-link menu-link">
                                    <span data-key="t-apps">API Providers</span>
                                </a>
                            </li>
                             {{-- <li class="nav-item">
                                <a href="{{ url('admin/api-provider-mapping') }}" class="nav-link menu-link">
                                    <span data-key="t-apps">API Providers Mapping</span>
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a href="{{ url('admin/slabs') }}" class="nav-link menu-link">
                                    <span data-key="t-apps">Slabs</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ url('admin/plan-category') }}">
                                    <span data-key="t-pages">Plan Category</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('admin/states') }}">
                                    <span data-key="t-advance-ui">States</span>
                                </a>
                            </li>
            
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ url('admin/districts') }}">
                                    <span data-key="t-widgets">Districts</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/languages') }}" class="nav-link" data-key="t-chartjs"> Languages </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/agent-type') }}" class="nav-link" data-key="t-chartjs"> Agent Type </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/add-package') }}" class="nav-link" data-key="t-chartjs"> Packages </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{--
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCharts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCharts">
                        <i class="ri-pie-chart-line"></i> <span data-key="t-charts">Charts</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCharts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#sidebarApexcharts" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarApexcharts" data-key="t-apexcharts">
                                    Apexcharts
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarApexcharts">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="charts-apex-line.html" class="nav-link" data-key="t-line"> Line
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-area.html" class="nav-link" data-key="t-area"> Area
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-column.html" class="nav-link" data-key="t-column">
                                                Column </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-bar.html" class="nav-link" data-key="t-bar"> Bar </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-mixed.html" class="nav-link" data-key="t-mixed"> Mixed
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-timeline.html" class="nav-link" data-key="t-timeline">
                                                Timeline </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-range-area.html" class="nav-link" data-key="t-range-area">Range Area</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-funnel.html" class="nav-link" data-key="t-funnel">Funnel</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-candlestick.html" class="nav-link" data-key="t-candlstick"> Candlstick </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-boxplot.html" class="nav-link" data-key="t-boxplot">
                                                Boxplot </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-bubble.html" class="nav-link" data-key="t-bubble">
                                                Bubble </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-scatter.html" class="nav-link" data-key="t-scatter">
                                                Scatter </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-heatmap.html" class="nav-link" data-key="t-heatmap">
                                                Heatmap </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-treemap.html" class="nav-link" data-key="t-treemap">
                                                Treemap </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-pie.html" class="nav-link" data-key="t-pie"> Pie </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-radialbar.html" class="nav-link" data-key="t-radialbar"> Radialbar </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-radar.html" class="nav-link" data-key="t-radar"> Radar
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-polar.html" class="nav-link" data-key="t-polar-area">
                                                Polar Area </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-slope.html" class="nav-link"><span data-key="t-slope">Slope</span> <span class="badge badge-pill bg-success" data-key="t-new">New</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="charts-chartjs.html" class="nav-link" data-key="t-chartjs"> Chartjs </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-echarts.html" class="nav-link" data-key="t-echarts"> Echarts </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarIcons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarIcons">
                        <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Icons</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarIcons">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="icons-remix.html" class="nav-link"><span data-key="t-remix">Remix</span> <span class="badge badge-pill bg-info">v4.3</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-boxicons.html" class="nav-link"><span data-key="t-boxicons">Boxicons</span> <span class="badge badge-pill bg-info">v2.1.4</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-materialdesign.html" class="nav-link"><span data-key="t-material-design">Material Design</span> <span class="badge badge-pill bg-info">v7.2.96</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-lineawesome.html" class="nav-link" data-key="t-line-awesome">Line Awesome</a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-feather.html" class="nav-link"><span data-key="t-feather">Feather</span> <span class="badge badge-pill bg-info">v4.29.2</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-crypto.html" class="nav-link"> <span data-key="t-crypto-svg">Crypto SVG</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMaps" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMaps">
                        <i class="ri-map-pin-line"></i> <span data-key="t-maps">Maps</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMaps">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="maps-google.html" class="nav-link" data-key="t-google">
                                    Google
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="maps-vector.html" class="nav-link" data-key="t-vector">
                                    Vector
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="maps-leaflet.html" class="nav-link" data-key="t-leaflet">
                                    Leaflet
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMultilevel">
                        <i class="ri-share-line"></i> <span data-key="t-multi-level">Multi Level</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-key="t-level-1.1"> Level 1.1 </a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarAccount" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAccount" data-key="t-level-1.2"> Level
                                    1.2
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAccount">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" data-key="t-level-2.1"> Level 2.1 </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#sidebarCrm" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCrm" data-key="t-level-2.2"> Level 2.2
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarCrm">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link" data-key="t-level-3.1"> Level 3.1
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link" data-key="t-level-3.2"> Level 3.2
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
