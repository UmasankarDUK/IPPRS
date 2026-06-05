@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-6 bg-gradient-to-r from-amber-500 to-orange-600 text-white">
            <h2 class="text-2xl font-black tracking-tight">Edit Entry</h2>
            <p class="text-sm text-amber-100 mt-1">Updating record in {{ $schema['title'] }}</p>
        </div>

        <form action="{{ route('study.update', ['table' => $table, 'id' => $record->id]) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PATCH')

            <!-- Block Selection (Jurisdiction) -->
            <div>
                <label for="block_int_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                    Jurisdiction Block
                </label>
                <select name="block_int_id" id="block_int_id" required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 text-sm text-gray-950 dark:text-white shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    @foreach($blocks as $b)
                        <option value="{{ $b->block_int_id }}" @if($b->block_int_id == old('block_int_id', $record->block_int_id)) selected @endif>
                            {{ $b->name }} Block (ID: {{ $b->block_int_id }})
                        </option>
                    @endforeach
                </select>
                @error('block_int_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dynamic Schema Fields -->
            @foreach($schema['fields'] as $fieldName => $field)
                <div>
                    <label for="{{ $fieldName }}" class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        {{ $field['label'] }}
                    </label>
                    
                    @if($field['type'] === 'textarea')
                        <textarea name="{{ $fieldName }}" id="{{ $fieldName }}" rows="4" required
                                  class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 text-sm text-gray-950 dark:text-white shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old($fieldName, $record->$fieldName) }}</textarea>
                    @elseif($field['type'] === 'number')
                        <input type="number" name="{{ $fieldName }}" id="{{ $fieldName }}" value="{{ old($fieldName, $record->$fieldName) }}" required min="0"
                               class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 text-sm text-gray-950 dark:text-white shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    @else
                        <input type="text" name="{{ $fieldName }}" id="{{ $fieldName }}" value="{{ old($fieldName, $record->$fieldName) }}" required
                               class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 text-sm text-gray-950 dark:text-white shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    @endif

                    @error($fieldName)
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <!-- Action Buttons -->
            <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ url()->previous() }}" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold shadow-md transition">
                    Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
