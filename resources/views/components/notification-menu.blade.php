<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge unread">{{ $unread }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">

            <span id="unread" class="unread">
                {{ $unread }}
            </span>
            Notifications</span>

        <div class="dropdown-divider"></div>
        <div id="notifications">
            @foreach ($notifications as $notification)

                <div class="dropdown-divider"></div>
                    <a href="{{ route('notification.read', $notification->id) }}"
                        class="dropdown-item justify-between-content">
                        <i class="fas fa-box"></i> {{ $notification->data['title'] }}

                        @if ($notification->unread())
                            <span class="badge badge-danger">*</span>
                        @endif

                        <span class="float-right text-muted text-sm">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </a>
                <div class="dropdown-divider"></div>
            @endforeach
        </div>

        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
    </div>
</li>
