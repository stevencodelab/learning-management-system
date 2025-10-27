@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'mb-4 sm:mb-6']) }}>
        @if (str_contains($status, 'error') || str_contains($status, 'invalid') || str_contains($status, 'incorrect') || str_contains($status, 'failed'))
            <!-- Error Notification -->
            <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-xl shadow-lg animate-slide-down">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm sm:text-base font-semibold text-red-800 mb-1">Login Failed</h3>
                    <p class="text-xs sm:text-sm text-red-700">{{ $status }}</p>
                </div>
                <div class="flex-shrink-0">
                    <button type="button" class="text-red-400 hover:text-red-600 transition-colors duration-200" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @else
            <!-- Success Notification -->
            <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-xl shadow-lg animate-slide-down">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm sm:text-base font-semibold text-green-800 mb-1">Success</h3>
                    <p class="text-xs sm:text-sm text-green-700">{{ $status }}</p>
                </div>
                <div class="flex-shrink-0">
                    <button type="button" class="text-green-400 hover:text-green-600 transition-colors duration-200" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
@endif
