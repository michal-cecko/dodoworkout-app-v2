@extends('layouts.web')

@section('body')
<section id="blog" class="container-wrapper pt-10 pb-12 pb-sm-24 relative">
  <span class="block text-primary font-semibold mx-auto w-fit mb-7 uppercase">blog</span>
  <h1 class="hfont text-4xl font-bold mb-7 text-center uppercase">Articles for Every Athlete</h1>
  <p class="text-textSecondary text-xl mb-7 text-center mx-auto max-w-[640px] mb-28">
  Explore my insights on workouts, skills, and mindset to elevate your training and transform your approach to calisthenics.
  </p>
  <div class="flex gap-14 max-lg:flex-col max-lg:gap-0">
    <aside class="w-[222px] shrink-0 max-lg:w-full">
      <div class="flex gap-2 mt-2">
        <label class="relative hidden max-lg:flex items-center gap-3 shrink-0 text-xs text-textSecondary bg-white border border-[#EDEDED] rounded-lg px-2 py-1 font-medium">
          {!! svgIcon("icon/icon-settings.svg", ['class' => ['']]) !!}
          Filter by
          <input id="filter-visible" type="checkbox" class="absolute invisible opacity-0 scale-0" name="filter-visible" />
        </label>

        <div class="w-full max-lg:mb-0 border border-[#DDDDDDEE] rounded-lg text-sm overflow-hidden flex pl-3 h-[36px] bg-white items-center focus-within:ring-2 focus-within:ring-primary mb-8">
          {!! svgIcon("icon/icon-search.svg", ['class' => ['text-[#AAAAAAEE]']]) !!}

          <input type="search" class="w-full bg-tranparent border-none outline-none pl-3" placeholder="Vyhľadať" />
        </div>
      </div>

      <div class="filter-wrapper" data-visible="false">
        <div class="filter-header">
          <span class="title">Filter</span>
          <button class="reset-button">RESET</button>
          <label for="filter-visible">&times;</label>
        </div>
        <div class="filter-container-wrapper">
          <div class="filter-container">
            <h5 class="filter-heading">Availability</h5>

            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="availability" checked value="free">
              Free
            </label>

            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="availability" value="premium">
              Premium
            </label>
          </div>

          <div class="filter-container">
            <h5 class="filter-heading">Tags</h5>

            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="tags" checked value="achievements">
              Achievements
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="tags" value="mindset">
              Mindset
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="tags" value="workout">
              Workout
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="tags" value="skills">
              Skills
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="tags" value="recovery">
              Recovery
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="tags" value="planche">
              Planche
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="tags" value="coaching">
              Coaching
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="tags" value="competitions">
              Competitions
            </label>
          </div>
        </div>
        <div class="filter-footer">
          <button class="btn" data-variant="primary">APPLY & SHOW RESULTS</button>
        </div>
      </div>
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
