<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User / Edit
            </h2>
            <a href="{{ route('users.index') }}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-2">Go to list</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('users.update',$user->id) }}" method="post">
                        @csrf
                        <div>
                            <label for="" class="text-lg font-medium">Name</label>
                            <div class="my-3">
                                <input type="text" value="{{ old('name',$user->name) }}" name="name" class="border-gray-300 shadow-sm w-1/2 rounded-lg" placeholder="Enter name">
                                @error('name')
                                    <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Email</label>
                            <div class="my-3">
                                <input type="email" value="{{ old('email',$user->email) }}" name="email" class="border-gray-300 shadow-sm w-1/2 rounded-lg" placeholder="Enter email">
                                @error('email')
                                    <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-4 mb-3">
                                @if ($roles->isNotempty())
                                    @foreach ($roles as $role)
                                        <div class="mt-3">
                                            <input {{ $hasRoles->contains($role->id) ? 'checked' : '' }} type="checkbox" id="role-{{$role->id}}" class="rounded" name="role[]" value="{{ $role->name }}">
                                            <label for="role-{{$role->id}}">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                @endif                                
                            </div>

                            <button type="submit" class="bg-slate-700 hover:bg-slate-500 text-sm rounded-md text-white px-5 py-3">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
