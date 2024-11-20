<div id="sidebar-menu" style="background: none !important;">
    <ul class="metismenu list-unstyled" id="side-menu" style="background: none !important;">

           {{-- <li>
                <a href="route('timeline')}}" class="waves-effect">
                    <i class="bx bx-news"></i>
                    <span key="t-chat">Newsfeed</span>
                </a>
            </li>--}}
        @can('access-dashboard')
        <li>
            <a href="{{route('marketing-dashboard')}}" class="waves-effect">
                <i class="bx bxs-dashboard"></i>
                <span key="t-chat">Dashboard</span>
            </a>
        </li>
        @endcan
        @can('access-properties')
        <li class="menu-title" key="t-menu">Properties</li>
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-buildings"></i>
                    <span key="t-properties"> Properties </span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                   @can('can-add-property') <li><a href="{{route('add-new-property')}}" key="t-properties"> New</a></li> @endcan
                       @can('access-all-properties') <li><a href="{{route('manage-properties', 'sold')}}" key="t-properties">Sold</a></li>
                    <li><a href="{{route('manage-properties', 'rented')}}" key="t-properties">Rented</a></li>
                    <li><a href="{{route('manage-properties', 'available')}}" key="t-properties">Available</a></li>
                    <li><a href="{{route('manage-properties','all')}}" key="t-properties">All Properties</a></li> @endcan
                </ul>
            </li>
        @endcan
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-key"></i>
                <span key="t-reservations"> Reservations </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('property-reservation')}}" key="t-reservations"> New</a></li>
                @can('access-all-properties')<li><a href="{{route('manage-properties', 'reserved')}}" key="t-reservations">Reservations</a></li>@endcan
                <li><a href="{{route('manage-property-reservation-requests')}}" key="t-reservations">Manage Requests</a></li>
            </ul>
        </li>
            @can('access-import-properties')
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-import"></i>
                    <span key="t-import"> Import Properties </span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    @can('can-import-properties')<li><a href="{{route('show-bulk-property-import-form')}}" key="t-properties">New Import</a></li> @endcan
                    @can('can-manage-property-import')<li><a href="{{route('show-imported-properties')}}" key="t-properties">Manage Import</a></li> @endcan
                </ul>
            </li>
            @endcan
            @can('access-property-allocation')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-collection"></i>
                        <span key="t-allocation"> Allocation </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @can('can-allocate-property')<li><a href="{{route('property-allocation')}}" key="t-properties">New</a></li> @endcan
                        @can('can-manage-allocation')<li><a href="{{route('manage-property-allocations')}}" key="t-properties">Manage Alloc.</a></li> @endcan
                    </ul>
                </li>
            @endcan
        @can('access-estates')
        <li class="menu-title" key="t-menu">Locations</li>
            <li>
            <a href="{{route('estates')}}" class="waves-effect">
                <i class="bx bxs-building-house"></i>
                <span key="t-chat">Estates</span>
            </a>
        </li>
        @endcan
       {{-- <li>
            <a href="route('workflow')}}" class="waves-effect">
                <i class="bx bx-loader"></i>
                <span key="t-chat">Workflow</span>
            </a>
        </li>--}}
        @can('access-engagements')
        <li class="menu-title" key="t-menu">Sales</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-transfer"></i>
                <span key="t-engagement"> Engagements </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('access-customers')<li><a href="{{route('leads')}}" key="t-engagement">Customers</a></li>@endcan
                @can('add-client')<li><a href="{{route('marketing-dashboard')}}" key="t-engagement">Dashboard</a></li>@endcan
               {{-- <li><a href="{{route('marketing-messaging')}}" key="t-engagement">Messaging</a></li>--}}
                @can('can-setup-schedule')<li><a href="{{route('schedule-follow-up')}}" key="t-engagement">New Schedule</a></li>@endcan
                @can('access-schedule')<li><a href="{{route('manage-schedule')}}" key="t-engagement">Manage Schedule</a></li>@endcan
            </ul>
        </li>
        @endcan
        @can('access-bulk-sms')
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-mail-send"></i>
                <span key="t-bulksms"> Bulk SMS </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('can-top-up')<li><a href="{{route('top-up')}}" key="t-bulksms">Top-up</a></li>@endcan
                @can('access-wallet')<li><a href="{{route('top-up-transactions')}}" key="t-bulksms">Wallet</a></li>@endcan
                @can('can-compose-sms')<li><a href="{{route('compose-sms')}}" key="t-bulksms">Compose</a></li>@endcan
                @can('can-schedule-sms')<li><a href="{{route('schedule-sms')}}" key="t-bulksms">Schedule</a></li>@endcan
                 <li><a href="{{route('bulksms-messages')}}" key="t-bulksms">Messages</a></li>
                @can('can-setup-phone-group') <li><a href="{{route('phone-groups')}}" key="t-bulksms">Phone Group</a></li> @endcan
            </ul>
        </li>
        @endcan
        @can('access-invoice')
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-wallet-alt"></i>
                <span key="t-invoice"> Invoice </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('manage-invoices', 'expiring-soon')}}" key="t-invoice">Expiring Soon</a></li>
            @can('can-setup-invoice')<li><a href="{{route('new-invoice')}}" key="t-invoice">New Invoice</a></li>@endcan
                @can('can-manage-invoice')<li><a href="{{route('manage-invoices', 'invoices')}}" key="t-invoice">All Invoices</a></li>
                <li><a href="{{route('manage-invoices', 'fully-paid')}}" key="t-invoice">Fully-Paid</a></li>
                <li><a href="{{route('manage-invoices', 'partly-paid')}}" key="t-invoice">Partly-Paid</a></li>
                <li><a href="{{route('manage-invoices', 'pending')}}" key="t-invoice">Pending</a></li>
                <li><a href="{{route('manage-invoices', 'verified')}}" key="t-invoice">Verified</a></li>
                <li><a href="{{route('manage-invoices', 'declined')}}" key="t-invoice">Declined</a></li> @endcan
            </ul>
        </li>
        @endcan
        @can('access-receipts')
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-receipt"></i>
                <span key="t-receipt"> Receipts  </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
               @can('can-issue-receipt') <li><a href="{{route('show-new-receipt-form')}}" key="t-receipt">New</a></li> @endcan
               @can('can-manage-receipt') <li><a href="{{route('show-manage-receipts')}}" key="t-receipt">Manage Receipts</a></li> @endcan
            </ul>
        </li>
        @endcan
        @can('can-process-refund')
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-loader"></i>
                <span key="t-refund"> Refund  </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('show-new-refund-form')}}" key="t-refund">New</a></li>
                @can('access-all-refunds')<li><a href="{{route('show-all-refunds')}}" key="t-refund">All Refunds</a></li> @endcan
               @can('can-manage-refund-requests') <li><a href="{{route('manage-refund-requests')}}" key="t-refund">Manage Requests</a></li> @endcan
            </ul>
        </li>
        @endcan
      {{--  <li>
            <a href="{route('tickets')}}" class="waves-effect">
                <i class="bx bx-support"></i>
                <span key="t-chat">Support Ticket</span>
            </a>
        </li>

        <li>
            <a href="{route('faqs')}}" class="waves-effect">
                <i class="bx bx-question-mark"></i>
                <span key="t-chat">FAQs</span>
            </a>
        </li>--}}


      {{--  <li class="menu-title" key="t-pages">TIME</li>
        @can('access-calendar')
            <li>
                <a href="{{route('calendar')}}" class="waves-effect">
                    <i class="bx bx-calendar"></i>
                    <span key="t-chat">Calendar</span>
                </a>
            </li>
        @endcan
        <li>
            <a href="{{route('show-appointments')}}" class="waves-effect">
                <i class="bx bx-timer"></i>
                <span key="t-chat">Events</span>
            </a>
        </li>--}}
        @can('can-manage-employee')
        <li class="menu-title">Human Resource</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-user-rectangle"></i>
                <span key="t-employee"> Employee </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('can-add-employee')<li><a href="{{route('add-new-pastor')}}" key="t-employee">New Employee</a></li> @endcan
                @can('can-manage-employee')<li><a href="{{route('pastors')}}" key="t-employee">Manage Employee</a></li> @endcan
               {{-- <li><a href="{{route('schedule-sms')}}" key="t-employee">Query</a></li>--}}
            </ul>
        </li>
        @endcan
       {{-- <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-calendar"></i>
                <span key="t-leave"> Leave Management </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('top-up')}}" key="t-leave">All Leave Applications</a></li>
                <li><a href="{{route('top-up-transactions')}}" key="t-leave">Leave Accrual</a></li>
                <li><a href="{{route('compose-sms')}}" key="t-leave">Leave Types</a></li>
                <li><a href="{{route('schedule-sms')}}" key="t-leave">Public Holidays</a></li>
            </ul>
        </li>--}}
       {{-- <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-cog"></i>
                <span key="t-hrsettings"> HR Settings </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('branches-settings')}}" key="t-hrsettings">Departments</a></li>
                <li><a href="{{route('church-branches')}}" key="t-employee">Supervisors</a></li>
                <li><a href="{{route('top-up-transactions')}}" key="t-hrsettings">Job Roles</a></li>
                <li><a href="{{route('compose-sms')}}" key="t-hrsettings">Locations</a></li>
                <li><a href="{{route('schedule-sms')}}" key="t-hrsettings">Public Holidays</a></li>
            </ul>
        </li>--}}
        @can('access-account')
        <li class="menu-title">Accounting</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-business"></i>
                <span key="t-account"> Account </span>
            </a>
            @can('access-account-reports')
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('chart-of-accounts')}}" key="t-account">Chart of Accounts</a></li>
                @can('can-use-jv')<li><a href="{{route('journal-voucher')}}" key="t-account">Journal Voucher</a></li>@endcan
                <li><a href="{{route('trial-balance')}}" key="t-account">Trial Balance</a></li>
                <li><a href="{{route('balance-sheet')}}" key="t-account">Statement of Fin. Pos.</a></li>
                <li><a href="{{route('profit-or-loss')}}" key="t-account">Income Statement</a></li>
            </ul>
            @endcan
        </li>
        @can('can-post-invoice')
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-send"></i>
                <span key="t-postings"> Postings  </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
               @can('can-post-invoice') <li><a href="{{route('post-invoice')}}" key="t-postings">Invoice</a></li>@endcan
                @can('can-post-receipt')<li><a href="{{route('post-receipt')}}" key="t-postings">Receipt</a></li>@endcan
            </ul>
        </li>
            @endcan
        @can('access-account-settings')
        <li>
            <a href="{{route('accounting-settings')}}" class="waves-effect">
                <i class="bx bx-cog"></i>
                <span key="t-chat">Settings</span>
            </a>
        </li>
            @endcan
        @endcan
        @can('access-payroll-process')
        <li class="menu-title">Payroll</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-calculator"></i>
                <span key="t-payroll-process"> Payroll Process </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('salary-structures')}}" key="t-payroll-process">Salary Structures</a></li>
                <li><a href="{{ route('payroll-routine') }}" key="t-payroll-process">Payroll Routine</a></li>
                <li><a href="{{ route('approve-payroll-routine') }}" key="t-payroll-process">Approve Routine</a></li>
                <li><a href="{{ route('payroll-report') }}" key="t-payroll-process">Payroll Reports</a></li>
            </ul>
        </li>
        @endcan
        @can('access-payroll-settings')
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-cog"></i>
                <span key="t-payroll"> Payroll Settings </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('payment-definition')}}" key="t-payroll">Payment Definition</a></li>
                <li><a href="{{route('salary-structure')}}" key="t-payroll">Salary Structure</a></li>
                <li><a href="{{route('salary-allowances')}}" key="t-payroll">Salary Allowances</a></li>
                <li><a href="{{route('payroll-month-year')}}" key="t-payroll">Payroll Month/Year</a></li>
            </ul>
        </li>
        @endcan
        <li class="menu-title">Inventory</li>
        @can('access-payroll-settings')
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bxl-product-hunt"></i>
                    <span key="t-inventory"> Products </span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('all-products')}}" key="t-inventory">Add Product</a></li>
                    <li><a href="{{route('all-products')}}" key="t-inventory">All Products</a></li>
                </ul>
            </li>
        @endcan
        @can('access-payroll-settings')
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bxs-hand-up"></i>
                    <span key="t-vendor"> Vendors </span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    {{--<li><a href="{{route('payment-definition')}}" key="t-vendor">Add Vendor</a></li>--}}
                    <li><a href="{{route('clients')}}" key="t-vendor">All Vendors</a></li>
                </ul>
            </li>
        @endcan
        {{--@can('access-account-settings')
            <li>
                <a href="{{route('accounting-settings')}}" class="waves-effect">
                    <i class="bx bxs-hourglass-bottom"></i>
                    <span key="t-chat">Warehouse</span>
                </a>
            </li>
        @endcan--}}
        <li class="menu-title">Reports</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-chart"></i>
                <span key="t-reports"> Reports </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="#" key="t-reports">Property</a></li>
                <li><a href="#" key="t-reports">Customer</a></li>
                <li><a href="#" key="t-reports">Sales</a></li>
            </ul>
        </li>


     {{--   <li class="menu-title">Extras</li>
        @can('access-documents')
            <li>
                <a href="{{route('cloud-storage')}}" class="waves-effect">
                    <i class="bx bx-file"></i>
                    <span key="t-chat">Documents</span>
                </a>
            </li>
        @endcan--}}
        <li>
            <a href="{{route('my-notifications')}}" class="waves-effect">
                <i class="bx bx-alarm"></i>
                <span key="t-chat">Notifications</span>
            </a>
        </li>

            <li class="menu-title">Administration</li>
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-cog"></i>
                    <span key="t-settings">Settings</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('sms-settings')}}" key="t-settings">SMS</a></li>
                    <li><a href="{{route('branches-settings')}}" key="t-settings">Department</a></li>
                    <li><a href="{{route('accounting-settings')}}" key="t-settings">Accounting</a></li>
                    <li><a href="{{route('church-branches')}}" key="t-settings">Supervisor</a></li>
                    {{--<li><a href="route('general-settings')}}" key="t-settings">General Settings</a></li>--}}
                </ul>
            </li>

    </ul>
</div>
