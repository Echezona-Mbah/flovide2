   <div class="app-sidebar sidebar-shadow">
                <div class="app-header__logo">
                    <div class="logo-src"></div>
                    <div class="header__pane ms-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>   
                 <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <li class="app-sidebar__heading">Menu</li>

                            <li>
                                <a href="{{route('admin.dashboard')}}" >
                                    <i class="metismenu-icon pe-7s-graph2"></i>Dashboards
                                </a>
                            </li>

                            <li class="mm-active"  >
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-rocket"></i>Account Types
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul      class="mm-show"  >
                                    <li>
                                        <a href="{{route('admin.business-account')}}" >
                                            <i class="metismenu-icon"></i>business account
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('admin.personal-account')}}" >
                                            <i class="metismenu-icon"></i>personal account
                                        </a>
                                    </li>
 
                                </ul>
                            </li>

                            <li >
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-light"></i> Account Management
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{route('admin.allaccount')}}" >
                                            <i class="metismenu-icon"></i>Account
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('admin.allsubaccount')}}" >
                                            <i class="metismenu-icon"></i>Sub Account
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('admin.allbeneficias')}}" >
                                            <i class="metismenu-icon"></i>Beneficias
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('admin.allcustomer')}}" >
                                            <i class="metismenu-icon"></i>customer
                                        </a>
                                    </li>

                                </ul>
                            </li>
                         
                           {{-- <li class="app-sidebar__heading">Revenue Management</li>

                            <li>
                                <a href="{{route('admin.remita')}}" >
                                    <i class="metismenu-icon pe-7s-graph1"></i>Remita
                                </a>
                            </li>

                            <li>
                                <a href="{{route('admin.subscription')}}" >
                                    <i class="metismenu-icon pe-7s-graph1"></i>Subscription
                                </a>
                            </li>

                            <li>
                                <a href="{{route('admin.donation')}}" >
                                    <i class="metismenu-icon pe-7s-graph1"></i>Donation
                                </a>
                            </li>

                            <li>
                                <a href="{{route('admin.invoice')}}" >
                                    <i class="metismenu-icon pe-7s-graph1"></i>Invoice
                                </a>
                            </li>

                            <li>
                                <a href="{{route('admin.payment')}}" >
                                    <i class="metismenu-icon pe-7s-graph1"></i>Payment
                                </a>
                            </li> --}}


                            <li class="app-sidebar__heading">Transactions & Billing</li>
                            <li>
                                <a href="{{route('admin.transactionhistory')}}" >
                                    <i class="metismenu-icon pe-7s-graph"></i>Transaction History
                                </a>
                            </li>
                            {{-- <li>
                                <a href="{{route('admin.allbillpayment')}}" >
                                    <i class="metismenu-icon pe-7s-way"></i>Bill Payment
                                </a>
                            </li> --}}


                            <li class="app-sidebar__heading">Charts</li>


                            <li>
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-browser"></i>ChargeBack / Refund
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul  
                                      
                                    >
                                    <li>
                                        <a href="{{route('admin.chargeback')}}" >
                                            <i class="metismenu-icon"></i> ChargeBack
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('admin.refund')}}" >
                                            <i class="metismenu-icon"></i>Refund
                                        </a>
                                    </li>
                                   
                                </ul>
                            </li>


                            <li>
                                <a href="#"
                                onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                    <i class="metismenu-icon pe-7s-power"></i> Logout
                                </a>

                                <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>


                        </ul>



                    </div>
                </div>
            </div>