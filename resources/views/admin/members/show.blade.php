@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Detail Anggota</h2>
        </div>
        <div class="px-6 py-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                <p class="text-gray-900">{{ $member->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                <p class="text-gray-900">{{ $member->username }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <p class="text-gray-900">{{ $member->email }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                <p class="text-gray-900">{{ $member->role }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Bergabung Sejak:</label>
                <p class="text-gray-900">{{ $member->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('admin.members.edit', $member) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                Edit
            </a>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
