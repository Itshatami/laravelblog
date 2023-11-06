<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2>Here's a list of your articles {{ auth()->user()->name }}</h2>
                    <div>
                        
                        @if(count($articles) > 0)
                        @foreach($articles as $article)
                            <div>
                                <div>
                                    <a href="{{ route('articles.edit', $article->slug) }}"
                                        class="inline-flex text-md pb-6 pt-8 items-center py-2 leading-4 font-medium rounded-md text-orange-400 hover:text-orange-300 focus:outline-none transition ease-in-out duration-150 float-right">
                                        Update
                                    </a>
                                    <form action="{{ route('articles.destroy' , $article->slug) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex text-md pb-6 pt-8 py-2 leading-4 font-medium text-red-400 hover:text-red-300 transition ease-in-out duration 150 float-right pr-3">Destroy</button>
                                    </form>
                                </div>
                                <a href="{{ route('articles.show' , $article->slug) }}">
                                    <h2 class="inline-flex text-lg pb-6 pt-8 items-center py-2 leading-4 font-medium rounded-md text-gray-400 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        {{ $article->title }}
                                        <span class="italic text-gray-800 text-sm pl-2">
                                            Created on  {{ $article->created_at->format("M jS Y") }}
                                        </span>
                                    </h2>
                                </a>
                                <hr class="border border-b-2 border-gray-700">
                            </div>
                        @endforeach                      
                        @else
                            <p>You have not yet created an article!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
