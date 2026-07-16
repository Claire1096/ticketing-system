<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">
            Add Knowledge Base Article
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-200 rounded-lg p-8">

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('faqs.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Question</label>
                        <input type="text" name="question" value="{{ old('question') }}"
                            placeholder="e.g. My label printer keeps jamming mid-batch"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Answer</label>
                        <textarea name="answer" rows="5"
                            placeholder="Write the fix or explanation here."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500" required>{{ old('answer') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500" required>
                            <option value="">Select category</option>
                            @foreach (['Hardware', 'Software', 'Network', 'Printer', 'Internet', 'Others'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="bg-pink-700 hover:bg-pink-800 text-white font-bold px-6 py-3 rounded-md">
                            Publish article
                        </button>
                        <a href="{{ route('faqs.index') }}"
                            class="border-2 border-gray-300 text-gray-600 font-bold px-6 py-3 rounded-md hover:border-gray-400">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>