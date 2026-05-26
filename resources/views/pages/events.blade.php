@extends('layouts.web')

@section('body')
<section id="bootcamps" class="container-wrapper pt-10 pb-12 pb-sm-24 relative">
  <span class="block text-primary font-semibold mx-auto w-fit mb-7">BOOTCAMPS & CERTIFICATIONS</span>
  <h1 class="hfont text-4xl font-bold mb-7 text-center uppercase">UPCOMING BOOTCAMPS</h1>
  <p class="text-textSecondary text-xl text-center mx-auto max-w-[640px] mb-24">
    Explore our workshops, seminars, and bootcamps to elevate your fitness, mindset, and skills.
    Expert-led sessions for all levels to push your limits.
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
              <input type="checkbox" class="filter-checkbox" name="availability" checked value="all">
              Available
            </label>

            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="availability" value="booked">
              Fully booked
            </label>
          </div>

          <div class="filter-container">
            <h5 class="filter-heading">Category</h5>

            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="category" checked value="category-1">
              Category #1
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="category" value="category-2">
              Category #2
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="category" value="category-3">
              Category #3
            </label>
          </div>

          <div class="filter-container">
            <h5 class="filter-heading">Place</h5>

            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="place" value="cadca">
              Čadca
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="place" checked value="online">
              Online
            </label>
            <label class="filter-label">
              <input type="checkbox" class="filter-checkbox" name="place" value="banske-bystrica">
              Banská Bystrica
            </label>
          </div>
        </div>
        <div class="filter-footer">
          <button class="btn" data-variant="primary">APPLY & SHOW RESULTS</button>
        </div>
      </div>
    </aside>

    <div class="pt-4 w-full">
      <div class="grid grid-cols-2 gap-10 max-lg:grid-cols-1">
        @forelse($events as $event)
          @include('parts.event-card', ['event' => $event])
        @empty
          <p class="text-textSecondary col-span-2 max-lg:col-span-1">{{ __('no_events_yet') }}</p>
        @endforelse
      </div>
    </div>
  </div>

  @if($events->hasPages())
    <div class="flex justify-center mt-auto pt-24">
      {{ $events->onEachSide(1)->links() }}
    </div>
  @endif

  {!! svgIcon("svg/triangle-mesh.svg", ['class' => ['max-lg:hidden absolute top-[60%] w-[calc(100vw * 2)] text-[#FFC1C1]']]) !!}
  {!! svgIcon("svg/dots-mesh.svg", ['class' => ['max-lg:hidden absolute right-10 top-[10%] w-[calc(100vw * 2)] text-[#D9D9D9]']]) !!}
  {!! svgIcon("svg/dots-mesh.svg", ['class' => ['max-lg:hidden z-[-1] absolute top-1/5 right-20 w-[calc(100vw * 2)] text-[#D9D9D9]']]) !!}

</section>

@endsection
