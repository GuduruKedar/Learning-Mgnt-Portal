@forelse($logs as $log)
    <tr class="hover:bg-indigo-50/30 transition-colors">
        <!-- Timestamp -->
        <td class="px-4 py-2.5 sm:px-5 sm:py-2.5 whitespace-nowrap text-xs text-gray-500">
            <div class="font-semibold text-gray-900">{{ $log->created_at->diffForHumans() }}</div>
            <div class="text-[11px] text-gray-400">{{ $log->created_at->format('M d, Y h:i A') }}</div>
        </td>

        <!-- Department & School -->
        <td class="px-4 py-2.5 sm:px-5 sm:py-2.5 whitespace-nowrap text-xs">
            @if($log->department)
                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    {{ $log->department->name }}
                </div>
                <div class="text-[11px] text-gray-400 pl-3">{{ $log->department->school->name ?? $log->department->school_id }}</div>
            @elseif($log->department_id)
                <div class="font-bold text-gray-900">{{ strtoupper(str_replace('_', ' ', $log->department_id)) }}</div>
                <div class="text-[11px] text-gray-400">Direct Department</div>
            @else
                <div class="font-bold text-gray-600">Central / System</div>
                <div class="text-[11px] text-gray-400">Global Service</div>
            @endif
        </td>

        <!-- User / Actor -->
        <td class="px-4 py-2.5 sm:px-5 sm:py-2.5 whitespace-nowrap text-xs">
            <div class="flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                    {{ substr($log->user_name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-900">{{ $log->user_name ?? 'System' }}</div>
                    <div class="text-[10px] text-gray-400 uppercase font-semibold">{{ $log->user_role ?? 'system' }}</div>
                </div>
            </div>
        </td>

        <!-- Module & Action -->
        <td class="px-4 py-2.5 sm:px-5 sm:py-2.5 whitespace-nowrap text-xs">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $log->module_badge }}">
                {{ $log->module }}
            </span>
            <div class="mt-0.5 font-semibold text-gray-800 flex items-center gap-1.5 flex-wrap">
                <span>{{ $log->action_title }}</span>
                @if(str_starts_with($log->action, 'cascade_'))
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200 uppercase tracking-tight shadow-2xs">
                        ⚡ Cascade
                    </span>
                @endif
            </div>
        </td>

        <!-- Description & Target -->
        <td class="px-4 py-2.5 sm:px-5 sm:py-2.5 text-xs text-gray-600 max-w-xs sm:max-w-sm">
            <div class="line-clamp-2">{{ $log->description }}</div>
            @if($log->entity_name)
                <div class="mt-0.5 inline-flex items-center text-[10px] font-mono-code bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded border border-gray-200">
                    🎯 {{ $log->entity_name }}
                </div>
            @endif
        </td>

        <!-- Status / Severity -->
        <td class="px-4 py-2.5 sm:px-5 sm:py-2.5 whitespace-nowrap text-xs">
            @if($log->severity === 'success')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Success
                </span>
            @elseif($log->severity === 'warning')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span> Warning
                </span>
            @elseif($log->severity === 'danger')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-rose-500"></span> Critical
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-blue-500"></span> Info
                </span>
            @endif
        </td>

        <!-- Inspect Action -->
        <td class="px-4 py-2.5 sm:px-5 sm:py-2.5 whitespace-nowrap text-xs text-right">
            <button onclick="openLogDetailsModal({{ $log->id }})" class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-700 font-semibold rounded-lg transition-colors shadow-2xs">
                <svg class="w-3.5 h-3.5 mr-1 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Inspect
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            No activity logs found matching the selected filter criteria.
        </td>
    </tr>
@endforelse
