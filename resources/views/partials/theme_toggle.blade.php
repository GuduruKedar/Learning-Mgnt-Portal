<div class="theme-widget-container relative">
    
    <!-- DESKTOP COLOR PALETTE: 3-Theme Color Selector (sm and larger screens) -->
    <div class="hidden sm:flex items-center gap-1.5 sm:gap-2" role="group" aria-label="Select Theme Color">
        <!-- 1. Blue Theme -->
        <button 
            type="button"
            onclick="setColorTheme('blue')"
            data-color="blue"
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center shrink-0"
            title="Theme 1: Blue (Royal Blue & Cyan)"
            aria-label="Select Blue Color Theme"
        >
            <svg class="w-3 h-3 text-white hidden select-check-icon pointer-events-none drop-shadow-xs" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </button>

        <!-- 2. Green Theme -->
        <button 
            type="button"
            onclick="setColorTheme('green')"
            data-color="green"
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center shrink-0"
            title="Theme 2: Green (Emerald & Teal)"
            aria-label="Select Green Color Theme"
        >
            <svg class="w-3 h-3 text-white hidden select-check-icon pointer-events-none drop-shadow-xs" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </button>

        <!-- 3. Violet Theme -->
        <button 
            type="button"
            onclick="setColorTheme('violet')"
            data-color="violet"
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-violet-600 to-fuchsia-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center shrink-0"
            title="Theme 3: Violet (Violet & Fuchsia)"
            aria-label="Select Violet Color Theme"
        >
            <svg class="w-3 h-3 text-white hidden select-check-icon pointer-events-none drop-shadow-xs" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>

    <!-- MOBILE COLOR PALETTE TRIGGER (compact on mobile) -->
    <div class="relative sm:hidden flex items-center">
        <button 
            type="button" 
            id="mobileThemePaletteTrigger"
            onclick="toggleMobileThemePalette(event)"
            class="flex items-center gap-1 p-0.5 rounded-full hover:bg-slate-100 dark:hover:bg-[#1C2430] transition-colors focus:outline-none"
            title="Choose Theme Color"
            aria-label="Choose Theme Color"
            aria-haspopup="true"
        >
            <span id="mobileActiveThemeDot" class="w-5 h-5 rounded-full shadow-xs flex items-center justify-center border border-white/50 dark:border-white/20 transition-all duration-300" style="background: var(--theme-gradient);">
                <svg class="w-2.5 h-2.5 text-white drop-shadow-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21a4 4 0 01-4-4 11.18 11.18 0 014-9.39A11.18 11.18 0 0116.39 3 4 4 0 0121 7.61a11.18 11.18 0 01-4.61 9.39A4 4 0 017 21z"/>
                </svg>
            </span>
            <svg class="w-3 h-3 text-slate-400 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <!-- Mobile Color Dropdown Popover -->
        <div 
            id="mobileThemeColorPopover" 
            class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-[#151B23] rounded-2xl shadow-2xl border border-slate-200 dark:border-[#273244] p-1.5 z-50 hidden origin-top-right transition-all backdrop-blur-md"
            role="menu"
            aria-label="Select Theme Color"
        >
            <div class="px-2.5 py-1.5 mb-1 border-b border-slate-100 dark:border-[#273244] text-[10px] font-bold text-slate-400 dark:text-[#64748B] uppercase tracking-wider flex items-center justify-between">
                <span>Theme Color</span>
                <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-slate-100 dark:bg-[#1C2430] text-slate-500 dark:text-slate-400 font-semibold">3 Themes</span>
            </div>
            <div class="space-y-0.5">
                <!-- 1. Blue -->
                <button type="button" onclick="setColorTheme('blue'); closeMobileThemePalette();" data-color="blue" class="mobile-theme-item w-full flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-[#94A3B8] hover:bg-slate-100 dark:hover:bg-[#1C2430] hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer text-left">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-400 shadow-xs shrink-0"></span>
                        <span>Royal Blue</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 mobile-theme-check hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </button>

                <!-- 2. Green -->
                <button type="button" onclick="setColorTheme('green'); closeMobileThemePalette();" data-color="green" class="mobile-theme-item w-full flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-[#94A3B8] hover:bg-slate-100 dark:hover:bg-[#1C2430] hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer text-left">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-400 shadow-xs shrink-0"></span>
                        <span>Emerald Green</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 mobile-theme-check hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </button>

                <!-- 3. Violet -->
                <button type="button" onclick="setColorTheme('violet'); closeMobileThemePalette();" data-color="violet" class="mobile-theme-item w-full flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-[#94A3B8] hover:bg-slate-100 dark:hover:bg-[#1C2430] hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer text-left">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-gradient-to-tr from-violet-600 to-fuchsia-400 shadow-xs shrink-0"></span>
                        <span>Violet Fuchsia</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400 mobile-theme-check hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Vertical Separator Divider -->
    <div class="theme-widget-divider"></div>

    <!-- Right Side: Dark / Night Mode Animated Transform Sliding Switch -->
    <button 
        id="themeToggleBtn" 
        type="button"
        onclick="toggleThemeMode()" 
        class="theme-switch-track"
        title="Toggle Light / Dark Mode"
        aria-label="Toggle Dark / Light Mode"
    >
        <!-- Sliding Animated Knob Thumb -->
        <span class="theme-switch-knob">
            <!-- Sun Icon (Light Mode) -->
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 theme-switch-icon-sun dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <!-- Moon Icon (Dark Mode) -->
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 theme-switch-icon-moon hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </span>
    </button>
</div>
