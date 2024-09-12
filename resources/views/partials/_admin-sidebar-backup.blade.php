<div id="sidebar-menu" style="">
    <ul class="metismenu list-unstyled" id="side-menu">

            <li>
                <a href="{{route('timeline')}}" class="waves-effect">
                    <i class="bx bx-news"></i>
                    <span key="t-chat">Newsfeed</span>
                </a>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-home-alt"></i>
                    <span key="t-properties"> Properties </span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('add-new-property')}}" key="t-properties">Add New</a></li>
                    <li><a href="{{route('manage-properties','all')}}" key="t-properties">All Properties</a></li>
                    <li><a href="{{route('manage-properties', 'sold')}}" key="t-properties">Sold</a></li>
                    <li><a href="{{route('manage-properties', 'rented')}}" key="t-properties">Rented</a></li>
                    <li><a href="{{route('manage-properties', 'available')}}" key="t-properties">Available</a></li>
                </ul>
            </li>
        <li>
            <a href="{{route('estates')}}" class="waves-effect">
                <i class="bx bxs-building-house"></i>
                <span key="t-chat">Estates</span>
            </a>
        </li>
        <li>
            <a href="{{route('workflow')}}" class="waves-effect">
                <i class="bx bx-loader"></i>
                <span key="t-chat">Workflow</span>
            </a>
        </li>
        <li class="menu-title" key="t-menu">Marketing</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-transfer"></i>
                <span key="t-engagement"> Engagements </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('leads')}}" key="t-engagement">Customers</a></li>
                @can('add-client')<li><a href="{{route('marketing-dashboard')}}" key="t-engagement">Dashboard</a></li>@endcan
                <li><a href="{{route('marketing-messaging')}}" key="t-engagement">Messaging</a></li>
                @can('add-client')<li><a href="{{route('schedule-follow-up')}}" key="t-engagement">New Schedule</a></li>@endcan
                @can('add-client')<li><a href="{{route('manage-schedule')}}" key="t-engagement">Manage Schedule</a></li>@endcan
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-mail-send"></i>
                <span key="t-bulksms"> Bulk SMS </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('topup-bulksms')<li><a href="{{route('top-up')}}" key="t-bulksms">Top-up</a></li>@endcan
                @can('access-bulksms-wallet')<li><a href="{{route('top-up-transactions')}}" key="t-bulksms">Wallet</a></li>@endcan
                @can('send-bulksms')<li><a href="{{route('compose-sms')}}" key="t-bulksms">Compose</a></li>@endcan
                <li><a href="{{route('schedule-sms')}}" key="t-bulksms">Schedule</a></li>
                @can('bulksms-phonegroup') <li><a href="{{route('bulksms-messages')}}" key="t-bulksms">Messages</a></li> @endcan
                @can('bulksms-phonegroup') <li><a href="{{route('phone-groups')}}" key="t-bulksms">Phone Group</a></li> @endcan
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-wallet-alt"></i>
                <span key="t-invoice"> Invoice </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('manage-invoices', 'invoices')}}" key="t-invoice">All Invoices</a></li>
                <li><a href="{{route('new-invoice')}}" key="t-invoice">New Invoice</a></li>
                <li><a href="{{route('manage-invoices', 'fully-paid')}}" key="t-invoice">Fully-Paid</a></li>
                <li><a href="{{route('manage-invoices', 'partly-paid')}}" key="t-invoice">Partly-Paid</a></li>
                <li><a href="{{route('manage-invoices', 'pending')}}" key="t-invoice">Pending</a></li>
                <li><a href="{{route('manage-invoices', 'verified')}}" key="t-invoice">Verified</a></li>
                <li><a href="{{route('manage-invoices', 'declined')}}" key="t-invoice">Declined</a></li>
            </ul>
        </li>
        <li>
            <a href="{{route('show-manage-receipts')}}" class="waves-effect">
                <i class="bx bx-receipt"></i>
                <span key="t-chat">Receipts</span>
            </a>
        </li>
        <li>
            <a href="{{route('tickets')}}" class="waves-effect">
                <i class="bx bx-support"></i>
                <span key="t-chat">Support Ticket</span>
            </a>
        </li>

        <li>
            <a href="{{route('faqs')}}" class="waves-effect">
                <i class="bx bx-question-mark"></i>
                <span key="t-chat">FAQs</span>
            </a>
        </li>


        <li class="menu-title" key="t-pages">TIME</li>
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
        </li>
        <li class="menu-title">Human Resource</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-user-rectangle"></i>
                <span key="t-employee"> Employee </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('add-new-pastor')}}" key="t-employee">New Employee</a></li>
                <li><a href="{{route('pastors')}}" key="t-employee">Manage Employee</a></li>
                <li><a href="{{route('schedule-sms')}}" key="t-employee">Query</a></li>
            </ul>
        </li>
        <li>
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
        </li>
        <li>
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
        </li>
        <li class="menu-title">Accounting</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-business"></i>
                <span key="t-account"> Account </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('chart-of-accounts')}}" key="t-account">Chart of Accounts</a></li>
                <li><a href="{{route('journal-voucher')}}" key="t-account">Journal Voucher</a></li>
                <li><a href="{{route('trial-balance')}}" key="t-account">Trial Balance</a></li>
                <li><a href="{{route('balance-sheet')}}" key="t-account">Balance Sheet</a></li>
                <li><a href="{{route('profit-or-loss')}}" key="t-account">Profit/Loss</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-wallet"></i>
                <span key="t-payroll"> Payroll </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('chart-of-accounts')}}" key="t-payroll">Payment Definition</a></li>
                <li><a href="{{route('journal-voucher')}}" key="t-payroll">Tax Rates</a></li>
                <li><a href="{{route('trial-balance')}}" key="t-payroll">Salary Structure</a></li>
                <li><a href="{{route('balance-sheet')}}" key="t-payroll">Payroll Setup</a></li>
                <li><a href="{{route('profit-or-loss')}}" key="t-payroll">Salary Grades</a></li>
                <li><a href="{{route('profit-or-loss')}}" key="t-payroll">Salary Increment</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-chart"></i>
                <span key="t-reports"> Reports </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('top-up')}}" key="t-reports">Property</a></li>
                <li><a href="{{route('top-up-transactions')}}" key="t-reports">Customer</a></li>
            </ul>
        </li>
        <li>
            <a href="{{route('accounting-settings')}}" class="waves-effect">
                <i class="bx bx-cog"></i>
                <span key="t-chat">Settings</span>
            </a>
        </li>

        <li class="menu-title">Extras</li>
        @can('access-documents')
            <li>
                <a href="{{route('cloud-storage')}}" class="waves-effect">
                    <i class="bx bx-file"></i>
                    <span key="t-chat">Documents</span>
                </a>
            </li>
        @endcan
        <li>
            <a href="{{route('my-notifications')}}" class="waves-effect">
                <i class="bx bx-alarm"></i>
                <span key="t-chat">Notifications</span>
            </a>
        </li>
        @can('access-settings')
            <li class="menu-title">Administration</li>
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-cog"></i>
                    <span key="t-settings">Settings</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('sms-settings')}}" key="t-settings">SMS</a></li>
                </ul>
            </li>
        @endcan
    </ul>
</div>
