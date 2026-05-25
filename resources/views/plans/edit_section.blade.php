@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('plans.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700">Archive</a>
                </li>
                <li class="flex items-center">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <a href="{{ route('plans.show', ['type' => $type, 'id' => $entity->id, 'sectionId' => $section->id]) }}" class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700">{{ $entity->name }}</a>
                </li>
                <li class="flex items-center">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-2 text-sm font-bold text-gray-900 dark:text-white">Edit Chapter</span>
                </li>
            </ol>
        </nav>

        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-8">
                
                <div class="mb-6">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Edit Plan Chapter</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Modify the content and details of this chapter for {{ $entity->name }}.</p>
                </div>

                <form action="{{ route('plans.sections.update', ['sectionId' => $section->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Chapter Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Chapter Title</label>
                        <input type="text" name="title" id="title" required value="{{ old('title', $section->title) }}" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-650 bg-white dark:bg-gray-750 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('title') border-red-500 focus:ring-red-500 @enderror"
                               placeholder="e.g. Surge Surveillance and Early Warning">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Body -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Content Body (HTML or Raw Text)</label>
                        <p class="text-xs text-gray-400 mb-2">Use paragraphs `<p class="mb-4">` and simple HTML markup to format the content, matching standard document guidelines.</p>
                        <textarea name="content" id="content" rows="15" required
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-650 bg-white dark:bg-gray-750 text-gray-900 dark:text-white shadow-sm font-mono text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('content') border-red-500 focus:ring-red-500 @enderror"
                                  placeholder="<p class='mb-4'>Add paragraph text here...</p>">{{ old('content', $section->content) }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 border-t border-gray-100 dark:border-gray-700 pt-6">
                        <a href="{{ route('plans.show', ['type' => $type, 'id' => $entity->id, 'sectionId' => $section->id]) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 focus:outline-none transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition cursor-pointer">
                            Save Changes
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
