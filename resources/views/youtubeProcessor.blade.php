<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Youtube Processor') }}</title>
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>
    <body class="h-full">
        <div class="min-h-full bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        YouTube JSON Reader
                    </h1>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Paste a video ID to pull its details from the YouTube Data API.
                    </p>
                </div>

                <div class="mx-auto mt-8 max-w-xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 sm:p-8 dark:bg-white/5 dark:ring-white/10">
                    <form id="youtubeForm">
                        <div>
                            <label for="videoID" class="block text-sm/6 font-medium text-gray-900 dark:text-white">
                                YouTube Video ID
                            </label>
                            <div class="mt-2">
                                <input id="videoID" type="text" name="videoID" placeholder="e.g. qxoFXWyFVUg" required
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 dark:bg-indigo-500 dark:shadow-none dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                                Process
                            </button>
                        </div>
                    </form>
                </div>

                <div id="videoResultWrapper" class="mt-8 hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 sm:p-8 dark:bg-white/5 dark:ring-white/10">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        Details
                    </h2>
                    <div id="videoResult" class="mt-4 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <table class="w-full text-left">
                            <thead class="bg-white dark:bg-gray-900">
                                <tr>
                                    <th scope="col" class="relative isolate py-3.5 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                        Key
                                    </th>
                                    <th scope="col" class="relative isolate py-3.5 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                        Value
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="video-result-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>