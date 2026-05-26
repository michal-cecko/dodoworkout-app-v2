@extends('layouts.web')

@section('body')
<section id="blog" class="container-wrapper pt-10 pb-12 pb-sm-24 relative">
  <span class="block text-primary font-semibold mx-auto w-fit mb-7 uppercase">{{ __('blog_eyebrow') }}</span>
  <h1 class="hfont text-4xl font-bold mb-7 text-center uppercase">{{ __('blog_heading') }}</h1>
  <p class="text-textSecondary text-xl mb-7 text-center mx-auto max-w-[640px] mb-28">
  {{ __('blog_intro') }}
  </p>
  <div class="flex gap-14 max-lg:flex-col max-lg:gap-0">
    <aside class="w-[222px] shrink-0 max-lg:w-full">
      <form method="GET" class="mt-2">
        <div class="w-full border border-[#DDDDDDEE] rounded-lg text-sm overflow-hidden flex pl-3 h-[36px] bg-white items-center focus-within:ring-2 focus-within:ring-primary mb-8">
          {!! svgIcon("icon/icon-search.svg", ['class' => ['text-[#AAAAAAEE]']]) !!}
          <input type="search" name="q" value="{{ $q }}" class="w-full bg-transparent border-none outline-none pl-3" placeholder="{{ __('search_placeholder') }}" />
        </div>
      </form>
    </aside>

    <div class="pt-2 card-holder white w-full">
      <div class="grid grid-cols-2 gap-10 max-lg:grid-cols-1">
        @forelse($posts as $post)
          @include('parts.post-card', ['post' => $post])
        @empty
          <p class="text-textSecondary col-span-2 max-lg:col-span-1">{{ __('no_posts_yet') }}</p>
        @endforelse
      </div>
    </div>
  </div>

  @if($posts->hasPages())
    <div class="flex justify-center mt-auto pt-24">
      {{ $posts->onEachSide(1)->links() }}
    </div>
  @endif

  {!! svgIcon("svg/triangle-mesh.svg", ['class' => ['max-lg:hidden absolute top-[60%] w-[calc(100vw * 2)] text-[#FFC1C1]']]) !!}
  {!! svgIcon("svg/dots-mesh.svg", ['class' => ['max-lg:hidden absolute right-10 top-[10%] w-[calc(100vw * 2)] text-[#D9D9D9]']]) !!}
  {!! svgIcon("svg/dots-mesh.svg", ['class' => ['max-lg:hidden z-[-1] absolute top-1/5 right-20 w-[calc(100vw * 2)] text-[#D9D9D9]']]) !!}

</section>

@endsection
