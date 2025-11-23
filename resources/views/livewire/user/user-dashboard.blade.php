<div>
    <h2 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-6">Quick Actions</h2>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-1 md:grid-col-1 lg:grid-cols-4 xl:grid-cols-4 2xl:grid-cols-4">
        @can('add payments')
        <div class="p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg w-full flex flex-col h-full space-y-6 transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200">
            <!-- Header -->
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-lime-100 dark:bg-lime-700 rounded-full flex items-center justify-center">
                    <flux:icon.credit-card class="w-6 h-6 text-lime-600 dark:text-lime-100" />
                </div>
                <h2 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                    Payment
                </h2>
            </div>

            <!-- Description (optional) -->
            <p class="text-zinc-600 dark:text-zinc-300 text-sm">
                Add a new payment for your subscribers to keep their accounts up to date and ensure uninterrupted service.
            </p>

            <!-- Action Button -->
            <div class="flex justify-end mt-auto">
                <flux:button 
                    variant="primary" 
                    wire:click="redirectTo('payment.add')"
                    color="lime"
                    class="px-6 py-2 text-sm md:text-base font-medium transition-transform hover:scale-105"
                >
                    Add Payment
                </flux:button>
            </div>

        </div>
        @endcan
        @can('view billings')
        <div class="p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg w-full flex flex-col h-full space-y-6 transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200">
            <!-- Header -->
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-lime-100 dark:bg-lime-700 rounded-full flex items-center justify-center">
                    <flux:icon.credit-card class="w-6 h-6 text-lime-600 dark:text-lime-100" />
                </div>
                <h2 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                    View Billings
                </h2>
            </div>

            <!-- Description (optional) -->
            <p class="text-zinc-600 dark:text-zinc-300 text-sm">
                Keep track of your subscribers’ financial activity by reviewing their billing history and upcoming charges.
            </p>

            <!-- Action Button -->
            <div class="flex justify-end mt-auto">
                <flux:button 
                    variant="primary" 
                    wire:click="redirectTo('subscribers')"
                    color="lime"
                    class="px-6 py-2 text-sm md:text-base font-medium transition-transform hover:scale-105"
                >
                    View
                </flux:button>
            </div>

        </div>
        @endcan
    </div>
</div>
