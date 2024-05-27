<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
        

        <form action="">
            <label>
                Nom sentier <input type="text" name="" id="">
            </label>
            <label>
                Description sentier <input type="text" name="" id="">
            </label>
            <p>Séléction d'un thème</p>
            <div>
                <!-- Thème -->
                <label><input type="radio" name="" id=""><img src="" alt=""><p>Nom du Thème</p></label>
            </div>
            <p>Critères</p>
            <div>
                <!-- Critères -->
                <label><input type="checkbox" name="" id="">Nom du crtière</label>
            </div>
            <p>Mot clés</p>
            <div>
                <!-- Mot clés -->
                <label><input type="checkbox" name="" id="">Nom du mot clés</label>
            </div>
            <p>image</p>
            <input type="file" name="" id="">
        </form>

    </div>
</x-app-layout>