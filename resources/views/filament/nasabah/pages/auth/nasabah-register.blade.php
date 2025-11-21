<x-filament-panels::layout.base>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    
        <div class="card shadow-sm p-4" style="width: 900px; max-width: 95%; border-radius: 1rem;">
    
            <h3 class="mb-3 text-center fw-bold">
                Sign Up
            </h3>
    
            {{ $this->form }}
    
            <div class="mt-4">
                {{ $this->getFooter() }}
            </div>
    
        </div>
    
    </div>
</x-filament-panels::layout.base>