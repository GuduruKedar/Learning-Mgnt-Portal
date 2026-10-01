<div class="theme-widget-container">
    
    <!-- Left Side: 6-Theme Color Selector -->
    <div class="flex items-center gap-1.5 sm:gap-2" role="group" aria-label="Select Theme Color">
        <!-- 1. Blue Theme -->
        <button 
            type="button"
            onclick="setColorTheme('blue')"
            data-color="blue"
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center"
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
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center"
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
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-violet-600 to-fuchsia-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center"
            title="Theme 3: Violet (Violet & Fuchsia)"
            aria-label="Select Violet Color Theme"
        >
            <svg class="w-3 h-3 text-white hidden select-check-icon pointer-events-none drop-shadow-xs" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </button>

        <!-- 4. Orange Theme -->
        <button 
            type="button"
            onclick="setColorTheme('orange')"
            data-color="orange"
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-orange-500 to-amber-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center"
            title="Theme 4: Orange (Sunset Orange & Amber)"
            aria-label="Select Orange Color Theme"
        >
            <svg class="w-3 h-3 text-white hidden select-check-icon pointer-events-none drop-shadow-xs" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </button>

        <!-- 5. Rose Theme -->
        <button 
            type="button"
            onclick="setColorTheme('rose')"
            data-color="rose"
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-rose-600 to-pink-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center"
            title="Theme 5: Rose (Ruby Rose & Pink)"
            aria-label="Select Rose Color Theme"
        >
            <svg class="w-3 h-3 text-white hidden select-check-icon pointer-events-none drop-shadow-xs" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </button>

        <!-- 6. Indigo Theme -->
        <button 
            type="button"
            onclick="setColorTheme('indigo')"
            data-color="indigo"
            class="color-theme-btn relative w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-indigo-600 to-sky-400 focus:outline-none cursor-pointer shadow-xs flex items-center justify-center"
            title="Theme 6: Indigo (Electric Indigo & Sky Blue)"
            aria-label="Select Indigo Color Theme"
        >
            <svg class="w-3 h-3 text-white hidden select-check-icon pointer-events-none drop-shadow-xs" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </button>
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
            <svg class="w-3.5 h-3.5 theme-switch-icon-sun dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <!-- Moon Icon (Dark Mode) -->
            <svg class="w-3.5 h-3.5 theme-switch-icon-moon hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </span>
    </button>
</div>
