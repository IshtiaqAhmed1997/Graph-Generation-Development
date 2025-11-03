<section class="bg-white shadow-sm rounded-lg p-6 space-y-6">
    <header class="border-bottom mb-4 pb-2">
        <h2 class="text-lg font-semibold text-[#1565c0]">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 leading-relaxed">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <!-- Delete Button -->
    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-300 px-4 py-2 rounded-md shadow-sm text-white font-medium">
        {{ __('Delete Account') }}
    </x-danger-button>

    <!-- Confirmation Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white rounded-lg shadow-sm">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold mb-2" style="color: #1565c0;">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 leading-relaxed mb-4">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <!-- Password Field -->
            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Password') }}" class="text-gray-700 font-medium" />
                <x-text-input id="password" name="password" type="password"
                    class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
                    placeholder="{{ __('Enter your password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600" />
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <x-secondary-button x-on:click="$dispatch('close')"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>