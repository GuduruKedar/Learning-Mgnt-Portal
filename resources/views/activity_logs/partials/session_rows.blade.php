@forelse($sessions as $session)
    @php
        $badge = $session->status_badge;
        $isLive = $session->is_online;
    @endphp
    <tr class="hover:bg-indigo-50/30 transition-colors">
        <!-- User / Actor -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr {{ $session->user_role === 'sa' ? 'from-amber-500 to-rose-600' : ($session->user_role === 'admin' ? 'from-indigo-500 to-purple-600' : ($session->user_role === 'sta' ? 'from-cyan-500 to-blue-600' : 'from-emerald-500 to-teal-600')) }} text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                    {{ substr($session->user_name ?? ($session->user?->username ?? 'U'), 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-gray-900 flex items-center gap-1.5">
                        <span>{{ $session->user_name ?? ($session->user?->username ?? 'Unknown') }}</span>
                        @if($isLive)
                            <span class="inline-flex items-center px-1.5 py-0.2 text-[9px] font-black uppercase tracking-wider rounded-md bg-emerald-100 text-emerald-800 border border-emerald-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping mr-1"></span> Live
                            </span>
                        @endif
                    </div>
                    <div class="text-[10px] text-gray-400 font-mono flex items-center gap-1">
                        <span>ID: {{ $session->user?->username ?? ($session->user_id ? '#' . $session->user_id : 'N/A') }}</span>
                        <span>•</span>
                        <span class="uppercase font-semibold text-indigo-600">{{ $session->role_label }}</span>
                    </div>
                </div>
            </div>
        </td>

        <!-- Department -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            @if($session->department)
                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    {{ $session->department->name }}
                </div>
                <div class="text-[11px] text-gray-400 pl-3">{{ $session->department->school->name ?? $session->department->school_id }}</div>
            @elseif($session->department_id)
                <div class="font-bold text-gray-900">{{ strtoupper(str_replace('_', ' ', $session->department_id)) }}</div>
                <div class="text-[11px] text-gray-400">Direct Department</div>
            @else
                <div class="font-bold text-gray-600">Central / System</div>
                <div class="text-[11px] text-gray-400">University-Wide</div>
            @endif
        </td>

        <!-- Session Started (Login) -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs text-gray-600">
            @if($session->login_at)
                <div class="font-semibold text-gray-900">{{ $session->login_at->diffForHumans() }}</div>
                <div class="text-[11px] text-gray-400 font-mono">{{ $session->login_at->format('M d, Y h:i A') }}</div>
            @else
                <span class="text-gray-400 italic">Unknown</span>
            @endif
        </td>

        <!-- Last Activity / Logout -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs text-gray-600">
            @if($session->logout_at)
                <div class="font-semibold text-gray-900 flex items-center gap-1 text-gray-700">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Logged Out {{ $session->logout_at->diffForHumans() }}</span>
                </div>
                <div class="text-[11px] text-gray-400 font-mono">{{ $session->logout_at->format('M d, Y h:i A') }}</div>
            @elseif($session->last_activity_at)
                <div class="font-semibold text-gray-900">
                    {{ $isLive ? 'Active Just Now' : 'Last seen ' . $session->last_activity_at->diffForHumans() }}
                </div>
                <div class="text-[11px] text-gray-400 font-mono">{{ $session->last_activity_at->format('M d, Y h:i A') }}</div>
            @else
                <span class="text-gray-400 italic">N/A</span>
            @endif
        </td>

        <!-- Duration Spent -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl font-bold font-mono text-xs shadow-2xs {{ $isLive ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-indigo-50 text-indigo-900 border border-indigo-200' }}">
                <svg class="w-3.5 h-3.5 {{ $isLive ? 'text-emerald-600 animate-spin' : 'text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $session->formatted_duration }}</span>
            </div>
            <div class="text-[10px] text-gray-400 mt-0.5 pl-1 font-mono">
                {{ number_format($session->active_duration_seconds) }}s elapsed
            </div>
        </td>

        <!-- Action / Request Count -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                ⚡ {{ number_format($session->actions_count) }} {{ \Illuminate\Support\Str::plural('action', $session->actions_count) }}
            </span>
        </td>

        <!-- Status -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $badge['bg'] }}">
                <span class="w-2 h-2 rounded-full mr-1.5 {{ $badge['dot'] }}"></span>
                {{ $badge['text'] }}
            </span>
        </td>

        <!-- Inspect Session Details -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs text-right">
            <button type="button" 
                    onclick="inspectUserSession({{ $session->id }}, '{{ addslashes($session->user_name ?? 'User') }}', '{{ $session->role_label }}', '{{ $session->department->name ?? ($session->department_id ?? 'Central') }}', '{{ $session->login_at ? $session->login_at->format('M d, Y h:i:s A') : 'N/A' }}', '{{ $session->last_activity_at ? $session->last_activity_at->format('M d, Y h:i:s A') : 'N/A' }}', '{{ $session->logout_at ? $session->logout_at->format('M d, Y h:i:s A') : 'Active / None' }}', '{{ $session->formatted_duration }}', {{ $session->actions_count }}, '{{ $badge['text'] }}', '{{ $session->ip_address ?? '127.0.0.1' }}')"
                    class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-700 font-semibold rounded-lg transition-colors shadow-2xs cursor-pointer">
                <svg class="w-3.5 h-3.5 mr-1 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Inspect
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            No user session duration logs found matching the selected filter criteria.
        </td>
    </tr>
@endforelse

