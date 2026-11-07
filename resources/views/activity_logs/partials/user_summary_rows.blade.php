@forelse($userSummaries as $u)
    @php
        $roleMap = [
            'sa'          => 'Super Admin',
            'admin'       => 'Dept Coordinator',
            'sta'         => 'Faculty / Staff',
            'staff'       => 'Faculty / Staff',
            'stu'         => 'Student',
            'student'     => 'Student',
            'civil_admin' => 'Civil Admin',
        ];
        $roleLabel = $roleMap[$u->user_role] ?? ucfirst($u->user_role ?? 'User');
        $isOnline = $u->latest_activity && \Carbon\Carbon::parse($u->latest_activity)->gt(\Carbon\Carbon::now()->subMinutes(15));
    @endphp
    <tr class="hover:bg-indigo-50/30 transition-colors">
        <!-- User / Actor -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr {{ $u->user_role === 'sa' ? 'from-amber-500 to-rose-600' : ($u->user_role === 'admin' ? 'from-indigo-500 to-purple-600' : ($u->user_role === 'sta' ? 'from-cyan-500 to-blue-600' : 'from-emerald-500 to-teal-600')) }} text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                    {{ substr($u->user_name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-gray-900 flex items-center gap-1.5">
                        <span>{{ $u->user_name ?? 'Anonymous User' }}</span>
                        @if($isOnline)
                            <span class="inline-flex items-center px-1.5 py-0.2 text-[9px] font-black uppercase tracking-wider rounded-md bg-emerald-100 text-emerald-800 border border-emerald-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping mr-1"></span> Online
                            </span>
                        @endif
                    </div>
                    <div class="text-[10px] text-gray-400 font-mono">
                        <span class="uppercase font-semibold text-indigo-600">{{ $roleLabel }}</span>
                        @if($u->user_id)
                            <span> • User #{{ $u->user_id }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </td>

        <!-- Department -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            @if($u->department_id && isset($deptMap[$u->department_id]))
                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    {{ $deptMap[$u->department_id]->name }}
                </div>
                <div class="text-[11px] text-gray-400 pl-3">{{ $deptMap[$u->department_id]->school->name ?? $deptMap[$u->department_id]->code }}</div>
            @elseif($u->department_id)
                <div class="font-bold text-gray-900">{{ strtoupper(str_replace('_', ' ', $u->department_id)) }}</div>
                <div class="text-[11px] text-gray-400">Direct Department</div>
            @else
                <div class="font-bold text-gray-600">Central / System</div>
                <div class="text-[11px] text-gray-400">University-Wide</div>
            @endif
        </td>

        <!-- Total Sessions -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-2xs font-mono">
                🔑 {{ number_format($u->total_sessions) }} {{ \Illuminate\Support\Str::plural('session', $u->total_sessions) }}
            </span>
        </td>

        <!-- Total Platform Time Used -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl font-extrabold font-mono text-xs shadow-2xs bg-emerald-100 text-emerald-900 border border-emerald-300">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ \App\Models\UserSession::formatDuration($u->total_duration_seconds) }}</span>
            </div>
            <div class="text-[10px] text-gray-400 mt-0.5 pl-1 font-mono">
                {{ number_format($u->total_duration_seconds) }} seconds total
            </div>
        </td>

        <!-- Average Session Length -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg font-bold font-mono text-xs bg-gray-100 text-gray-800 border border-gray-200">
                <span>⏱️</span>
                <span>{{ \App\Models\UserSession::formatDuration($u->avg_duration_seconds) }} / session</span>
            </div>
        </td>

        <!-- Total Actions Done -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs">
            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-200">
                ⚡ {{ number_format($u->total_actions) }} ops
            </span>
        </td>

        <!-- Last Activity -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs text-gray-600">
            @if($u->latest_activity)
                @php $lat = \Carbon\Carbon::parse($u->latest_activity); @endphp
                <div class="font-semibold text-gray-900">{{ $lat->diffForHumans() }}</div>
                <div class="text-[11px] text-gray-400 font-mono">{{ $lat->format('M d, Y h:i A') }}</div>
            @else
                <span class="text-gray-400 italic">No activity</span>
            @endif
        </td>

        <!-- Filter / Filter this user -->
        <td class="px-4 py-3 sm:px-5 sm:py-3 whitespace-nowrap text-xs text-right">
            <button type="button" 
                    onclick="filterBySpecificUser('{{ addslashes($u->user_name ?? '') }}')"
                    class="inline-flex items-center px-3 py-1 bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-semibold rounded-lg transition-all shadow-2xs cursor-pointer">
                <span>View Sessions</span>
                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            No user usage records found matching the selected filter criteria.
        </td>
    </tr>
@endforelse

