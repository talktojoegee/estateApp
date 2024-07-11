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
                    <span key="t-bulksms"> Properties </span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('add-new-property')}}" key="t-bulksms">Add New</a></li>
                    <li><a href="{{route('manage-properties')}}" key="t-bulksms">All Properties</a></li>
                    <li><a href="{{route('certificates', 'expired')}}" key="t-bulksms">Sold</a></li>
                    <li><a href="{{route('certificates', 'expired')}}" key="t-bulksms">Rented</a></li>
                    <li><a href="{{route('certificates', 'expired')}}" key="t-bulksms">Available</a></li>
                    <li><a href="{{route('certificates', 'expired')}}" key="t-bulksms">Property Assignment</a></li>
                </ul>
            </li>
        <li>
            <a href="{{route('estates')}}" class="waves-effect">
                <i class="bx bxs-building-house"></i>
                <span key="t-chat">Estates</span>
            </a>
        </li>
        <li class="menu-title" key="t-menu">Marketing</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-transfer"></i>
                <span key="t-bulksms"> Engagements </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('leads')}}" key="t-wallet">Customers</a></li>
                @can('add-client')<li><a href="{{route('marketing-dashboard')}}" key="t-wallet">Dashboard</a></li>@endcan
                <li><a href="{{route('marketing-messaging')}}" key="t-wallet">Messaging</a></li>
                @can('add-client')<li><a href="{{route('schedule-follow-up')}}" key="t-wallet">New Schedule</a></li>@endcan
                @can('add-client')<li><a href="{{route('manage-schedule')}}" key="t-wallet">Manage Schedule</a></li>@endcan
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
                <span key="t-bulksms"> Invoice </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('manage-invoices', 'invoices')}}" key="t-bulksms">All Invoices</a></li>
                <li><a href="{{route('new-invoice')}}" key="t-bulksms">New Invoice</a></li>
                <li><a href="{{route('manage-invoices', 'paid')}}" key="t-bulksms">Paid</a></li>
                <li><a href="{{route('manage-invoices', 'pending')}}" key="t-bulksms">Pending</a></li>
                <li><a href="{{route('manage-invoices', 'verified')}}" key="t-bulksms">Verified</a></li>
                <li><a href="{{route('manage-invoices', 'declined')}}" key="t-bulksms">Declined</a></li>
                <li><a href="{{route('invoice-service')}}" key="t-bulksms">Service/Product</a></li>
            </ul>
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
                <span key="t-bulksms"> Employee </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('topup-bulksms')<li><a href="{{route('top-up')}}" key="t-bulksms">New Employee</a></li>@endcan
                @can('access-bulksms-wallet')<li><a href="{{route('top-up-transactions')}}" key="t-bulksms">Manage Employee</a></li>@endcan
                @can('send-bulksms')<li><a href="{{route('compose-sms')}}" key="t-bulksms">Supervisors</a></li>@endcan
                <li><a href="{{route('schedule-sms')}}" key="t-bulksms">Supervisor Assignment</a></li>
                <li><a href="{{route('schedule-sms')}}" key="t-bulksms">Query</a></li>
                <li><a href="{{route('schedule-sms')}}" key="t-bulksms">Settings</a></li>
            </ul>
        </li>
        <li class="menu-title">Account</li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-dollar-circle"></i>
                <span key="t-bulksms"> Employee </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('chart-of-accounts')}}" key="t-bulksms">Chart of Accounts</a></li>
                <li><a href="{{route('journal-voucher')}}" key="t-bulksms">Journal Voucher</a></li>
                <li><a href="{{route('trial-balance')}}" key="t-bulksms">Trial Balance</a></li>
                <li><a href="{{route('balance-sheet')}}" key="t-bulksms">Balance Sheet</a></li>
                <li><a href="{{route('profit-or-loss')}}" key="t-bulksms">Profit/Loss</a></li>
            </ul>
        </li>
         <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bx-calendar"></i>
                <span key="t-bulksms"> Leave Management </span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('topup-bulksms')<li><a href="{{route('top-up')}}" key="t-bulksms">Applications</a></li>@endcan
                @can('access-bulksms-wallet')<li><a href="{{route('top-up-transactions')}}" key="t-bulksms">All</a></li>@endcan
                @can('send-bulksms')<li><a href="{{route('compose-sms')}}" key="t-bulksms">Resigned</a></li>@endcan
                <li><a href="{{route('schedule-sms')}}" key="t-bulksms">Terminated</a></li>
            </ul>
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
                    <li><a href="{{route('branches-settings')}}" key="t-settings">Sections</a></li>
                    <li><a href="{{route('workflow-settings')}}" key="t-settings">Workflow</a></li>
                    <li><a href="{{route('church-branches')}}" key="t-settings">Section Heads</a></li>
                </ul>
            </li>
        @endcan
    </ul>
</div>
